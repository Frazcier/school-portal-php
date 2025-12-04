<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class controller {
    private $connection;

    public function __construct() {
        $this->connection = $this->create_connection();
    }

    public function create_connection() {
        if (!defined('DB_HOST')) {
            define ('DB_HOST', 'localhost');
        };

        if (!defined('DB_USER')) {
            define ('DB_USER', 'root');
        };

        if (!defined('DB_PASS')) {
            define ('DB_PASS', '');
        };

        if (!defined('DB_NAME')) {
            define ('DB_NAME', 'school_portal_db');
        };

        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($connection->connect_error) {
            die("Connection Failed: " . $connection->connect_error);
        }

        return $connection;
    }

    public function actionreader() {
        if (isset($_GET['method_finder'])) {
            $action = $_GET['method_finder'];

            if ($action === 'register') {
                $this->register();
            } else if ($action === 'login') {
                $this->login();
            } else if ($action === 'logout') {
                $this->logout();
            } else if ($action === 'update_profile') {
                $this->update_profile();
            } else if ($action === 'manage_users') {
                $this->manage_users();
            } else if ($action === 'update_subject') { 
                $this->update_subject();
            } else if ($action === 'update_grade_record') {
                $this->update_grade_record();
            }
        }
    }
    
    // --- FIXED METHOD: FETCH GRADES (Now includes u.unique_id from users table) ---
    public function get_grades_for_subject($subject_id) {
        $sql = "SELECT 
                    gp.*, 
                    u.unique_id,                     /* <-- FIXED: FETCHING UNIQUE_ID */
                    sp.first_name, 
                    sp.last_name,
                    sp.course,
                    sp.year_level 
                FROM grading_periods gp
                JOIN users u ON gp.student_id = u.user_id
                JOIN student_profiles sp ON u.user_id = sp.user_id
                WHERE gp.subject_id = ?
                ORDER BY sp.last_name ASC";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $subject_id);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function update_grade_record() {
        if (!isset($_POST['period_id'])) {
            header("Location: ../pages/staff/grading.php?error=Invalid record ID");
            exit();
        }
        
        $period_id = $_POST['period_id'];
        $activity_1 = $_POST['activity_1'];
        $activity_2 = $_POST['activity_2'];
        $exam_score = $_POST['exam_score'];

        $max_class_score = 100;
        $max_exam_score = 50;
        $class_standing_ratio = 0.60;
        $exam_standing_ratio = 0.40;

        $total_score = $activity_1 + $activity_2;
        
        $raw_class_standing = ($total_score / $max_class_score) * 4.0;
        $raw_exam_standing = ($exam_score / $max_exam_score) * 4.0;
        
        $final_grade = ($raw_class_standing * $class_standing_ratio) + ($raw_exam_standing * $exam_standing_ratio);
        
        $rounded_grade = round($final_grade * 4) / 4; 
        
        $sql = "UPDATE grading_periods 
                SET activity_1 = ?, activity_2 = ?, exam_score = ?, final_grade = ?, rounded_grade = ?
                WHERE period_id = ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("iiiddi", 
            $activity_1, 
            $activity_2, 
            $exam_score, 
            $final_grade, 
            $rounded_grade, 
            $period_id
        );

        if ($stmt->execute()) {
            header("Location: ../pages/staff/grading.php?success=Grade updated successfully for period ID: $period_id");
            exit();
        } else {
            header("Location: ../pages/staff/grading.php?error=Failed to update grade: " . $this->connection->error);
            exit();
        }
    }
    
    public function register() {
        $role = $_POST['role'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $middle_name = $_POST['middle_name'] ?? '';
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            header("Location: ../pages/auth/signup.php?error=Passwords do not match");
            exit();
        }

        $course = "N/A";
        $year_level = "N/A";
        $department = "N/A";
        $academic_rank = "N/A";
        $prefix = "";
        
        $default_student_pic = '../../assets/img/profile-pictures/profile.svg';
        $default_staff_pic = '../../assets/img/profile-pictures/profile-staff.svg';

        if ($role === 'student') {
            $course = $_POST['course'];
            $year_level = $_POST['year_level'];
            $prefix = "STU";
        } else if ($role === 'teacher'){
            $department = $_POST['department'];
            $academic_rank = $_POST['academic_rank'];
            $prefix = "TCH";
        } else {
            $department = "Administration";
            $academic_rank = 'System Admin';
            $prefix = "ADM";
        }

        $year = date("Y");
        $pattern = $prefix . $year . "%";

        $sql_id = "SELECT unique_id FROM users WHERE unique_id LIKE ? ORDER BY user_id DESC LIMIT 1";
        $stmt_id = $this->connection->prepare($sql_id);
        $stmt_id->bind_param("s", $pattern);
        $stmt_id->execute();
        $result_id = $stmt_id->get_result();

        if ($last_user = $result_id->fetch_assoc()) {
            $last_id = $last_user['unique_id'];
            $last_number = intval(substr($last_id, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        $unique_id = $prefix . $year . sprintf("%04d", $new_number);

        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $status = 'pending';

        $sql_user = "INSERT INTO users (unique_id, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
        $stmt_user = $this->connection->prepare($sql_user);

        if ($stmt_user) {
            $stmt_user->bind_param("sssss", $unique_id, $email, $hashed_pass, $role, $status);

            if ($stmt_user->execute()) {
                $user_id = $this->connection->insert_id;
            }

            if ($role === 'student') {
                $sql_prof = "INSERT INTO student_profiles (user_id, first_name, middle_name, last_name, course, year_level, section, profile_picture) VALUES (?, ?, ?, ?, ?, ?, 'N/A', ?)";
                $stmt_prof = $this->connection->prepare($sql_prof);
                $stmt_prof->bind_param("issssss", $user_id, $first_name, $middle_name, $last_name, $course, $year_level, $default_student_pic);
            } else {
                $sql_prof = "INSERT INTO staff_profiles (user_id, first_name, middle_name, last_name, department, academic_rank, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_prof = $this->connection->prepare($sql_prof);
                $stmt_prof->bind_param("issssss", $user_id, $first_name, $middle_name, $last_name, $department, $academic_rank, $default_staff_pic);
            }

            if ($stmt_prof->execute()) {
                header("Location: ../pages/auth/login.php?success=Account created successfully&new_id=$unique_id");
                exit();
            } else {
                echo "Error inserting profile: " . $this->connection->error;
            }
        } else {
            header("Location: ../pages/auth/signup.php?error=Email already exists");
            exit();     
        }
    }

    public function login() {
        $identifier = $_POST['identifier'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ? OR unique_id = ?";
        $stmt = $this->connection->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                if (password_verify($password, $user['password'])) {
                    if ($user['status'] !== 'active') {
                        header("Location: ../pages/auth/login.php?error=Account is " . $user['status']);
                        exit();
                    }

                    $user_id = $user['user_id'];
                    $role = $user['role'];

                    if ($role === 'student') {
                        $sql_p = "SELECT * FROM student_profiles WHERE user_id = ?";
                    } else {
                        $sql_p = "SELECT * FROM staff_profiles WHERE user_id = ?";
                    }

                    $stmt_p = $this->connection->prepare($sql_p);
                    $stmt_p->bind_param("i", $user_id);
                    $stmt_p->execute();
                    $res_p = $stmt_p->get_result();
                    $profile = $res_p->fetch_assoc();

                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['unique_id'] = $user['unique_id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['email'] = $user['email'];
                    
                    
                    $_SESSION['first_name'] = $profile['first_name'];
                    $_SESSION['last_name'] = $profile['last_name'];
                    $_SESSION['middle_name'] = $profile['middle_name'];
                    $_SESSION['profile_picture'] = $profile['profile_picture'];
                    $_SESSION['academic_rank'] = $profile['academic_rank'];
                    $_SESSION['profile_data'] = $profile;

                    if ($role === 'student') {
                        header("Location: ../pages/student/student-dashboard.php");
                    } else {
                        header("Location: ../pages/staff/staff-dashboard.php");
                    }
                    exit();
                    
                } else {
                    header("Location: ../pages/auth/login.php?error=Incorrect Password");
                    exit();
                }
            } else {
                header("Location: ../pages/auth/login.php?error=User not found");
                exit();
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: ../pages/auth/login.php");
        exit();
    }

    public function update_profile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../pages/auth/login.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];

        $current_pic = $_SESSION['profile_data']['profile_picture'];
        $new_pic = !empty($_POST['profile_picture']) ? $_POST['profile_picture'] : $current_pic;

        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $password_message = "";

        $sql_email = "UPDATE users SET email = ? WHERE user_id = ?";
        $stmt_email = $this->connection->prepare($sql_email);
        $stmt_email->bind_param("si", $email, $user_id);
        $stmt_email->execute();
        $_SESSION['email'] = $email;

        if (!empty($current_password) && !empty($new_password)) {
            $sql_check = "SELECT password FROM users WHERE user_id = ?";
            $stmt_check = $this->connection->prepare($sql_check);
            $stmt_check->bind_param("i", $user_id);
            $stmt_check->execute();
            $result = $stmt_check->get_result();
            $user = $result->fetch_assoc();

            if (password_verify($current_password, $user['password'])) {
                $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $sql_pass = "UPDATE users SET password = ? WHERE user_id = ?";
                $stmt_pass = $this->connection->prepare($sql_pass);
                $stmt_pass->bind_param("si", $new_hashed, $user_id);
                
                if ($stmt_pass->execute()) {
                    $password_message = "&msg=Password updated successfully";
                }
            } else {
                $password_message = "&error=Incorrect current password";
            }
        }

        if ($role === 'student') {
            $sql_prof = 'UPDATE student_profiles SET first_name = ?, middle_name = ?, last_name = ?, profile_picture = ? WHERE user_id = ?';
            $stmt_prof = $this->connection->prepare($sql_prof);
        } else {
            $sql_prof = 'UPDATE staff_profiles SET first_name = ?, middle_name = ?, last_name = ?, profile_picture = ?  WHERE user_id = ?';
            $stmt_prof = $this->connection->prepare($sql_prof);
        }

        $stmt_prof->bind_param("ssssi", $first_name, $middle_name, $last_name, $new_pic, $user_id);

        if ($stmt_prof->execute()) {
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            
            $_SESSION['profile_picture'] = $new_pic;
            $_SESSION['profile_data']['profile_picture'] = $new_pic;

            $_SESSION['profile_data']['first_name'] = $first_name;
            $_SESSION['profile_data']['middle_name'] = $middle_name;
            $_SESSION['profile_data']['last_name'] = $last_name;

            if ($role === 'student') {
                header("Location: ../pages/student/account-settings-student.php?success=Profile updated$password_message");
            } else {
                header("Location: ../pages/staff/account-settings-staff.php?success=Profile updated$password_message");
            }
            exit();
        } else {
            echo "Error updating profile: " . $this->connection->error;
        }
    }

    public function get_users_by_role($role, $status) {
        if ($role === 'student') {
            $table = 'student_profiles';
        } else {
            $table = 'staff_profiles';
        };

        $sql = "SELECT user.user_id, user.unique_id, user.email, user.status, user.role, profile.*
                FROM users user
                JOIN $table profile ON user.user_id = profile.user_id
                WHERE user.role = ? and user.status = ?
                ORDER BY user.created_at DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $role, $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function manage_users() {

        $sub_action = $_POST['sub_action'];
        $target_id = $_POST['target_id'];

        if ($sub_action === 'approve') {
            $sql = "UPDATE users SET status = 'active' WHERE user_id = ?";
            $msg = "User account approved successfully";
        } else if ($sub_action === 'deactivate') {
            $sql = "UPDATE users SET status = 'inactive'WHERE user_id = ?";
            $msg = "User account deactivated";
        } else if ($sub_action === 'reactivate') {
            $sql = "UPDATE users SET status = 'active' WHERE user_id = ?";
            $msg = "User account reactivated";
        } else if ($sub_action === 'delete') {
            $sql = "DELETE FROM users WHERE user_id = ?";
            $msg = "User account permanently deleted";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param('i', $target_id);

        if ($stmt->execute()) {
            header ("Location: ../pages/staff/user-management.php?success=$msg");
        } else {
            header ("Location: ../pages/staff/user-management.php?error=Database error");
        }

        exit();
    }
    
    public function update_subject() {
        if (!isset($_POST['subject_id'])) {
            header("Location: ../pages/staff/subject-management.php?error=Invalid subject data");
            exit();
        }

        $subject_id = $_POST['subject_id'];
        $subject_code = $_POST['subject_code'];
        $subject_name = $_POST['subject_name'];
        $department = $_POST['department'];
        $instructor = $_POST['instructor_id']; 
        $status = $_POST['status']; 

        $sql_sub = "UPDATE subjects 
                    SET subject_code = ?, subject_name = ?, department = ?, status = ?
                    WHERE subject_id = ?";
        
        $stmt_sub = $this->connection->prepare($sql_sub);
        $stmt_sub->bind_param("ssssi", $subject_code, $subject_name, $department, $status, $subject_id);

        if ($stmt_sub->execute()) {
            
            // Placeholder: Update instructor association here if necessary
            // For now, we only update the main subject details.
            
            header("Location: ../pages/staff/subject-management.php?success=Subject '$subject_name' updated successfully!");
            exit();
        } else {
            header("Location: ../pages/staff/subject-management.php?error=Failed to update subject: " . $this->connection->error);
            exit();
        }
    }
}

$controller = new controller();
$controller->actionreader();

?>