<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db_config.php';
class controller {
    private $connection;

    public function __construct() {
        $this->connection = $this->create_connection();
    }

    public function create_connection() {

        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($connection->connect_error) {
            die("Connection Failed: " . $connection->connect_error);
        }

        return $connection;
    }

    public function generate_csrf_token() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public function actionreader() {
        if (isset($_GET['method_finder'])) {
            $action = $_GET['method_finder'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    if ($action !== 'login' && $action !== 'register') {
                        die("Security Error: Invalid Request Token.");
                    }
                }
            }

            switch ($action) {
                case 'register': $this->register(); break;
                case 'login': $this->login(); break;
                case 'logout': $this->logout(); break;
                case 'update_profile': $this->update_profile(); break;

                case 'manage_users': $this->manage_users(); break;
                case 'create_user': $this->create_user(); break;
                case 'update_student_section': $this->update_student_section(); break;
                
                case 'add_subject': $this->add_subject(); break;
                case 'update_subject': $this->update_subject(); break;
                case 'delete_subject': $this->delete_subject(); break;
                
                case 'upload_resource': $this->upload_resource(); break;
                case 'delete_resource': $this->delete_resource(); break;
                case 'toggle_resource_status': $this->toggle_resource_status(); break;
                
                case 'create_announcement': $this->create_announcement(); break;
                case 'update_announcement': $this->update_announcement(); break;
                case 'delete_announcement': $this->delete_announcement(); break;
                
                case 'add_exam': $this->add_exam(); break;
                case 'delete_exam': $this->delete_exam(); break;
                case 'complete_exam': $this->complete_exam(); break;

                case 'submit_payment': $this->submit_payment(); break;
                case 'process_payment': $this->process_payment(); break;
                case 'assign_fee': $this->assign_fee(); break;

                case 'get_more_logs': $this->get_more_logs(); break;
                case 'export_logs': $this->export_logs(); break;
                case 'export_users': $this->export_users(); break;
                case 'get_more_subjects': $this->get_more_subjects(); break;
            }
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
                $this->log_activity($user_id, "Registration", "New user registered: $unique_id ($role)");
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
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                header("Location: ../pages/auth/login.php?error=Account is " . $user['status']);
                exit();
            }

            session_regenerate_id(true);

            if ($user['role'] === 'student') {
                $table = 'student_profiles';
            } else {
                $table = 'staff_profiles';
            }

            $sql_prof = "SELECT * FROM $table WHERE user_id = ?";
            $stmt_prof = $this->connection->prepare($sql_prof);
            $stmt_prof->bind_param("i", $user['user_id']);
            $stmt_prof->execute();
            $profile = $stmt_prof->get_result()->fetch_assoc();

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['unique_id'] = $user['unique_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $profile['first_name'];
            $_SESSION['last_name'] = $profile['last_name'];
            $_SESSION['profile_data'] = $profile;

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $this->log_activity($user['user_id'], "Login", "User logged in successfully");

            if ($user['role'] === 'student') {
                header("Location: ../pages/student/student-dashboard.php");
            } else {
                header("Location: ../pages/staff/staff-dashboard.php");
            }

            exit();
        } else {
            header("Location: ../pages/auth/login.php?error=Invalid Credentials");
            exit();
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

        $sql = "SELECT user.user_id, user.unique_id, user.email, user.status, user.role, user.created_at, profile.*
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
        $current_user_id = $_SESSION['user_id'];

        $sql_info = "SELECT u.unique_id, u.role, s.academic_rank, st.course, st.year_level 
                     FROM users u 
                     LEFT JOIN staff_profiles s ON u.user_id = s.user_id 
                     LEFT JOIN student_profiles st ON u.user_id = st.user_id
                     WHERE u.user_id = ?";
        $stmt_info = $this->connection->prepare($sql_info);
        $stmt_info->bind_param('i', $target_id);
        $stmt_info->execute();
        $target_data = $stmt_info->get_result()->fetch_assoc();
        
        $target_unique_id = $target_data['unique_id'] ?? 'Unknown';
        $target_role = $target_data['role'] ?? 'student';
        $target_rank_title = $target_data['academic_rank'] ?? '';

        $my_role = $_SESSION['role'];
        $my_rank_title = $_SESSION['academic_rank'] ?? ''; 
        $my_score = $this->get_rank_value($my_role, $my_rank_title);
        $target_score = $this->get_rank_value($target_role, $target_rank_title);

        if (($sub_action === 'deactivate' || $sub_action === 'delete') && $target_id != $current_user_id) {
            if ($my_score <= $target_score) {
                $this->log_activity($current_user_id, "Security Alert", "Unauthorized attempt on: $target_unique_id");
                header("Location: ../pages/staff/user-management.php?error=Action Denied: Insufficient privileges.");
                exit();
            }
        }

        $sql = "";
        $msg = "";
        $log_desc = "";

        if ($sub_action === 'approve') {
            if ($target_role === 'student') {
                $course = $target_data['course'];
                $year = $target_data['year_level'];
                
                $sectionA = "$course {$year}A"; 
                $sectionB = "$course {$year}B"; 
                $limit = 50;

                $stmtA = $this->connection->prepare("SELECT COUNT(*) as total FROM student_profiles WHERE section = ?");
                $stmtA->bind_param("s", $sectionA);
                $stmtA->execute();
                $countA = $stmtA->get_result()->fetch_assoc()['total'];

                $stmtB = $this->connection->prepare("SELECT COUNT(*) as total FROM student_profiles WHERE section = ?");
                $stmtB->bind_param("s", $sectionB);
                $stmtB->execute();
                $countB = $stmtB->get_result()->fetch_assoc()['total'];

                if ($countA >= $limit && $countB >= $limit) {
                    header("Location: ../pages/staff/user-management.php?error=Approval Failed: All sections ($sectionA, $sectionB) are full.");
                    exit();
                }

                $assigned_section = ($countA <= $countB) ? $sectionA : $sectionB;

                $sql_assign = "UPDATE student_profiles SET section = ? WHERE user_id = ?";
                $stmt_assign = $this->connection->prepare($sql_assign);
                $stmt_assign->bind_param("si", $assigned_section, $target_id);
                $stmt_assign->execute();
                
                $log_desc_extra = " Assigned to $assigned_section.";
            }

            $sql = "UPDATE users SET status = 'active' WHERE user_id = ?";
            $msg = "User approved" . (isset($assigned_section) ? " and assigned to $assigned_section" : "");
            $log_desc = "Approved registration: $target_unique_id." . ($log_desc_extra ?? "");

        } else if ($sub_action === 'deactivate') {
            $sql = "UPDATE users SET status = 'inactive' WHERE user_id = ?";
            $msg = "User account deactivated";
            $log_desc = "Deactivated user: $target_unique_id";
        } else if ($sub_action === 'reactivate') {
            $sql = "UPDATE users SET status = 'active' WHERE user_id = ?";
            $msg = "User account reactivated";
            $log_desc = "Reactivated user: $target_unique_id";
        } else if ($sub_action === 'delete') {
            $sql = "DELETE FROM users WHERE user_id = ?";
            $msg = "User deleted permanently";
            $log_desc = "Deleted user: $target_unique_id";
        }

        if (!empty($sql)) {
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param('i', $target_id);

            if ($stmt->execute()) {
                $this->log_activity($current_user_id, "User Management", $log_desc);
                header("Location: ../pages/staff/user-management.php?success=$msg");
            } else {
                header("Location: ../pages/staff/user-management.php?error=Database error");
            }
        }

        exit();
    }

    public function create_user() {
        $role = $_POST['role'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $middle_name = $_POST['middle_name'] ?? '';
        $email = $_POST['email'];
        $password = $_POST['password']; 
        $course = $_POST['course'] ?? "N/A";
        $year_level = $_POST['year_level'] ?? "N/A";
        $department = $_POST['department'] ?? "N/A";
        $academic_rank = $_POST['academic_rank'] ?? "N/A";

        $prefix = ($role === 'student') ? "STU" : (($role === 'teacher') ? "TCH" : "ADM");
        $year = date("Y");

        $sql_id = "SELECT unique_id FROM users WHERE unique_id LIKE ? ORDER BY user_id DESC LIMIT 1";
        $stmt_id = $this->connection->prepare($sql_id);
        $pattern = $prefix . $year . "%";
        $stmt_id->bind_param("s", $pattern);
        $stmt_id->execute();
        $result_id = $stmt_id->get_result();

        if ($last_user = $result_id->fetch_assoc()) {
            $last_number = intval(substr($last_user['unique_id'], -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        $unique_id = $prefix . $year . sprintf("%04d", $new_number);

        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $status = 'active';

        $sql_user = "INSERT INTO users (unique_id, email, password, role, status) VALUES (?, ?, ?, ?, ?)";
        $stmt_user = $this->connection->prepare($sql_user);
        $stmt_user->bind_param("sssss", $unique_id, $email, $hashed_pass, $role, $status);

        if ($stmt_user->execute()) {
            $user_id = $this->connection->insert_id;
            $default_pic = ($role === 'student') ? '../../assets/img/profile-pictures/profile.svg' : '../../assets/img/profile-pictures/profile-staff.svg';
            
            if ($role === 'student') {
                $sql_prof = "INSERT INTO student_profiles (user_id, first_name, middle_name, last_name, course, year_level, section, profile_picture) VALUES (?, ?, ?, ?, ?, ?, 'N/A', ?)";
                $stmt_prof = $this->connection->prepare($sql_prof);
                $stmt_prof->bind_param("issssss", $user_id, $first_name, $middle_name, $last_name, $course, $year_level, $default_pic);
            } else {
                $sql_prof = "INSERT INTO staff_profiles (user_id, first_name, middle_name, last_name, department, academic_rank, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_prof = $this->connection->prepare($sql_prof);
                $stmt_prof->bind_param("issssss", $user_id, $first_name, $middle_name, $last_name, $department, $academic_rank, $default_pic);
            }

            if ($stmt_prof->execute()) {
                $this->log_activity($_SESSION['user_id'], "User Management", "Created new user: $unique_id");
                header("Location: ../pages/staff/user-management.php?success=New $role added successfully (ID: $unique_id)");
                exit();
            }
        } 
        
        header("Location: ../pages/staff/user-management.php?error=Failed to create user");
        exit();
    }

    public function update_student_section() {
        $user_id = $_POST['user_id'];
        $section = $_POST['section'];

        $sql = "UPDATE student_profiles SET section = ? WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $section, $user_id);

        if ($stmt->execute()) {
            $name_query = $this->connection->query("SELECT first_name, last_name, unique_id FROM student_profiles JOIN users ON student_profiles.user_id = users.user_id WHERE users.user_id = $user_id");
            $student = $name_query->fetch_assoc();
            $student_label = $student ? $student['unique_id'] : "ID $user_id";

            $this->log_activity($_SESSION['user_id'], "User Management", "Updated section for $student_label to $section");

            header("Location: ../pages/staff/user-management.php?success=Student section updated successfully");
        } else {
            header("Location: ../pages/staff/user-management.php?error=Failed to update section");
        }
        exit();
    }

    public function add_subject() {
        $code = $_POST['subject_code'];
        $desc = $_POST['subject_description'];
        $units = $_POST['units'];
        $instructor = $_POST['instructor_id'];
        $section = $_POST['section_assigned'];
        $time = $_POST['schedule_time'];
        $day = $_POST['schedule_day'];
        $room = $_POST['room'];

        $sql = "INSERT INTO subjects (subject_code, subject_description, units, instructor_id, section_assigned, schedule_time, schedule_day, room)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssisssss", $code, $desc, $units, $instructor, $section, $time, $day, $room);

        if ($stmt->execute()) {
            $this->log_activity($_SESSION['user_id'], "Subject Management", "Added subject: $code");
            header("Location: ../pages/staff/subject-management.php?success=Subject added successfully");
        } else {
            header("Location: ../pages/staff/subject-management.php?error=Failed to add subject");
        }

        exit();
    }

    public function update_subject() {
        $id = $_POST['subject_id'];
        $code = $_POST['subject_code'];
        $desc = $_POST['subject_description'];
        $units = $_POST['units'];
        $instructor = $_POST['instructor_id'];
        $section = $_POST['section_assigned'];
        $time = $_POST['schedule_time'];
        $day = $_POST['schedule_day'];
        $room = $_POST['room'];

        $sql = "UPDATE subjects SET subject_code = ?, subject_description = ?, units = ?, instructor_id = ?, section_assigned = ?, schedule_time = ?, schedule_day = ?, room = ?
                WHERE subject_id = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssisssssi", $code, $desc, $units, $instructor, $section, $time, $day, $room, $id);

        if ($stmt->execute()) {
            $this->log_activity($_SESSION['user_id'], "Subject Management", "Updated subject details: $code");
            header("Location: ../pages/staff/subject-management.php?success=Subject updated successfully");
        } else {
            header("Location: ../pages/staff/subject-management.php?error=Failed to update subject");
        }

        exit();
    }

    public function delete_subject() {
        $id = $_POST['subject_id'];
        $code = $_POST['subject_code'];

        $sql = "DELETE FROM subjects WHERE subject_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $this->log_activity($_SESSION['user_id'], "Subject Management", "Deleted subject: $code");
            header("Location: ../pages/staff/subject-management.php?success=Subject deleted successfully");
        } else {
            header("Location: ../pages/staff/subject-management.php?error=Failed to delete subject");
        }

        exit();
    }

    public function get_all_subjects() {
        $sql = "SELECT s.*, p.first_name, p.last_name, p.profile_picture, p.academic_rank
                FROM subjects s
                LEFT JOIN staff_profiles p ON s.instructor_id = p.user_id
                ORDER BY s.created_at DESC";

        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_student_subjects($user_id) {
        $sql = "SELECT section FROM student_profiles WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        
        $section = $student['section'] ?? 'N/A';
        
        $sql2 = "SELECT s.*, p.first_name, p.last_name, p.profile_picture
                 FROM subjects s
                 LEFT JOIN staff_profiles p ON s.instructor_id = p.user_id
                 WHERE s.section_assigned = ? AND s.status = 'Active'
            
                 UNION
            
                 SELECT s.*, p.first_name, p.last_name, p.profile_picture
                 FROM subjects s
                 LEFT JOIN staff_profiles p ON s.instructor_id = p.user_id
                 INNER JOIN grading_periods gp ON s.subject_id = gp.subject_id 
                 WHERE gp.student_id = ? AND s.status = 'Active'";

        $stmt2 = $this->connection->prepare($sql2);
        
        $stmt2->bind_param("si", $section, $user_id); 
        
        if ($stmt2->execute()) {
            return $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    private function get_subjects_by_section_only($section) {
        $sql = "SELECT s.*, p.first_name, p.last_name, p.profile_picture
                FROM subjects s
                LEFT JOIN staff_profiles p ON s.instructor_id = p.user_id
                WHERE s.section_assigned = ? AND s.status = 'Active'";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $section);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc(MYSQLI_ASSOC);
    }

    public function upload_resource() {
        if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === 0) {
            $title = $_POST['title'];
            $subject = $_POST['subject_code'];
            $category = $_POST['category'];
            $status = $_POST['status'];
            $uploader = $_SESSION['user_id'];

            $fileTmpPath = $_FILES['resource_file']['tmp_name'];
            $fileName = $_FILES['resource_file']['name'];
            $fileSize = $_FILES['resource_file']['size'];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'jpg', 'png', 'mp4'];
            if (!in_array($fileType, $allowedExtensions)) {
                header("Location: ../pages/staff/content-library.php?error=Invalid file type. Allowed: PDF, Docs, Images, MP4");
                exit();
            }

            $newFileName = time() . '_' . $fileName;
            $uploadFileDir = '../assets/uploads/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $sql = "INSERT INTO resources (title, subject_code, category, file_name, file_path, file_type, uploaded_by, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->connection->prepare($sql);
                $db_path = '../../assets/uploads/' . $newFileName;
                $stmt->bind_param("ssssssis", $title, $subject, $category, $fileName, $db_path, $fileType, $uploader, $status);

                if ($stmt->execute()) {
                    $this->log_activity($_SESSION['user_id'], "Content Library", "Uploaded file: $fileName");
                    header("Location: ../pages/staff/content-library.php?success=Resource uploaded successfully");
                } else {
                    header("Location: ../pages/staff/content-library.php?error=Database error");
                }
            } else {
                header("Location: ../pages/staff/content-library.php?error=No file uploaded or file too large");
            }
            
            exit();
        } else {
            $error_code = $_FILES['resource_file']['error'] ?? 'No file';
            header("Location: ../pages/staff/content-library.php?error=Upload Failed. Error Code: " . $error_code);
            exit();
        }
    }

    public function get_all_resources() {
        $sql = "SELECT * FROM resources ORDER BY created_at DESC";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function toggle_resource_status() {
        $id = $_POST['resource_id'];
        $current_status = $_POST['current_status'];
        
        $sql_info = "SELECT title FROM resources WHERE resource_id = ?";
        $stmt_info = $this->connection->prepare($sql_info);
        $stmt_info->bind_param('i', $id);
        $stmt_info->execute();
        $res_info = $stmt_info->get_result()->fetch_assoc();
        $title = $res_info['title'] ?? 'Unknown Resource';
        
        $new_status = ($current_status === 'Published') ? 'Draft' : 'Published';

        $sql = "UPDATE resources SET status = ? WHERE resource_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $new_status, $id);

        if ($stmt->execute()) {
            $action_verb = ($new_status === 'Published') ? "Published" : "Unpublished";
            $this->log_activity($_SESSION['user_id'], "Content Library", "$action_verb resource: $title");
            header("Location: ../pages/staff/content-library.php?success=Status updated to $new_status");
        } else {
            header("Location: ../pages/staff/content-library.php?error=Failed to update status");
        }

        exit();
    }

    public function delete_resource() {
        $id = $_POST['resource_id'];

        $sql_get = "SELECT file_path FROM resources WHERE resource_id = ?";
        $stmt_get = $this->connection->prepare($sql_get);
        $stmt_get->bind_param("i", $id);
        $stmt_get->execute();  
        $result = $stmt_get->get_result()->fetch_assoc();

        $file_name_log = $result['file_name'] ?? 'Unknown File';

        if ($result) {
            $file_to_delete = __DIR__ . '/../' . str_replace('../../', '', $result['file_path']);

            if (file_exists($file_to_delete)) {
                unlink($file_to_delete);
            }

            $sql = "DELETE FROM resources WHERE resource_id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            $this->log_activity($_SESSION['user_id'], "Content Library", "Deleted file: $file_name_log");
            header("Location: ../pages/staff/content-library.php?success=Resource deleted");
        } else {
            header("Location: ../pages/staff/content-library.php?error=Resource not found");
        }

        exit();
    }

    public function check_or_404($data) {
        if (empty($data)) {
            http_response_code(404);
            include (__DIR__ . '/../404.php');
            exit();
        }
    }

    public function log_activity($user_id, $action_type, $description) {
        $sql = "INSERT INTO activity_logs (user_id, action_type, description) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("iss", $user_id, $action_type, $description);
        $stmt->execute();
    }

    public function create_announcement() {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];
        
        $sql = "INSERT INTO announcements (title, content, created_by) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $user_id);
        
        if ($stmt->execute()) {
            $this->log_activity($user_id, "Announcement", "Published announcement: $title");
            header("Location: ../pages/staff/staff-dashboard.php?success=Announcement posted");
        } else {
            header("Location: ../pages/staff/staff-dashboard.php?error=Failed to post");
        }
        exit();
    }

    public function get_announcements() {
        $sql = "SELECT * FROM announcements WHERE status = 'Active' ORDER BY created_at DESC LIMIT 5";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update_announcement() {
        $id = $_POST['announcement_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $status = $_POST['status'];

        $sql = "UPDATE announcements SET title = ?, content = ?, status = ? WHERE announcement_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("sssi", $title, $content, $status, $id);
        
        if ($stmt->execute()) {
            $this->log_activity($_SESSION['user_id'], "Announcement", "Updated announcement: $title");
            header("Location: ../pages/staff/staff-dashboard.php?success=Announcement updated");
        } else {
            header("Location: ../pages/staff/staff-dashboard.php?error=Update failed");
        }
        exit();
    }

    public function delete_announcement() {
        $id = $_POST['announcement_id'];
        
        $sql_get = "SELECT title FROM announcements WHERE announcement_id = ?";
        $stmt_get = $this->connection->prepare($sql_get);
        $stmt_get->bind_param("i", $id);
        $stmt_get->execute();
        $res = $stmt_get->get_result()->fetch_assoc();
        $title = $res['title'] ?? 'Unknown';

        $sql = "DELETE FROM announcements WHERE announcement_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $this->log_activity($_SESSION['user_id'], "Announcement", "Deleted announcement: $title");
            header("Location: ../pages/staff/staff-dashboard.php?success=Announcement deleted");
        } else {
            header("Location: ../pages/staff/staff-dashboard.php?error=Delete failed");
        }
        exit();
    }

    public function get_recent_logs() {
        $sql = "SELECT l.*, s.first_name, s.last_name, s.profile_picture, s.academic_rank
                FROM activity_logs l 
                LEFT JOIN staff_profiles s ON l.user_id = s.user_id 
                ORDER BY l.created_at DESC LIMIT 10";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_more_logs() {
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $limit = 10;

        $sql = "SELECT l.*, 
                       COALESCE(s.first_name, st.first_name) as first_name, 
                       COALESCE(s.last_name, st.last_name) as last_name, 
                       COALESCE(s.profile_picture, st.profile_picture) as profile_picture
                FROM activity_logs l 
                LEFT JOIN staff_profiles s ON l.user_id = s.user_id 
                LEFT JOIN student_profiles st ON l.user_id = st.user_id
                ORDER BY l.created_at DESC LIMIT ? OFFSET ?";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        echo json_encode($result);
        exit();
    }

    public function export_logs() {
        $filename = "activity_logs_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['Log ID', 'Action Type', 'User', 'Description', 'Date & Time']);
        
        $sql = "SELECT l.log_id, l.action_type, 
                       CONCAT(COALESCE(s.first_name, st.first_name), ' ', COALESCE(s.last_name, st.last_name)) as user_name,
                       l.description, l.created_at
                FROM activity_logs l 
                LEFT JOIN staff_profiles s ON l.user_id = s.user_id 
                LEFT JOIN student_profiles st ON l.user_id = st.user_id
                ORDER BY l.created_at DESC";
                
        $result = $this->connection->query($sql);
        
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit();
    }

    public function export_users() {
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'teacher') {
            header("Location: ../auth/login.php");
            exit();
        }

        $filename = "user_masterlist_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'User ID', 
            'Role', 
            'Last Name', 
            'First Name', 
            'Email Address', 
            'Status', 
            'Course / Dept',
            'Year / Rank',
            'Section',
            'Date Registered'
        ]);
        
        $sql = "SELECT 
                    u.unique_id, 
                    u.role, 
                    COALESCE(s.last_name, st.last_name) as last_name,
                    COALESCE(s.first_name, st.first_name) as first_name,
                    u.email, 
                    u.status,
                    COALESCE(st.course, s.department) as info_1,
                    COALESCE(st.year_level, s.academic_rank) as info_2,
                    st.section,
                    u.created_at
                FROM users u 
                LEFT JOIN staff_profiles s ON u.user_id = s.user_id 
                LEFT JOIN student_profiles st ON u.user_id = st.user_id
                ORDER BY u.role ASC, last_name ASC";
                
        $result = $this->connection->query($sql);
        
        while ($row = $result->fetch_assoc()) {
            $section = ($row['role'] === 'student') ? $row['section'] : 'N/A';
            $date = date("M d, Y", strtotime($row['created_at']));
            
            fputcsv($output, [
                $row['unique_id'],
                ucfirst($row['role']),
                $row['last_name'],
                $row['first_name'],
                $row['email'],
                ucfirst($row['status']),
                $row['info_1'],
                $row['info_2'],
                $section,
                $date
            ]);
        }
        
        fclose($output);
        exit();
    }

    public function get_more_subjects() {
        $user_id = $_SESSION['user_id'];
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $limit = 3;

        $all_subjects = $this->get_student_subjects($user_id);
        $total_subjects = count($all_subjects);

        $chunk = array_slice($all_subjects, $offset, $limit);

        $loaded_count = $offset + count($chunk);
        $remaining = $total_subjects - $loaded_count;

        echo json_encode([
            'subjects' => $chunk,
            'remaining' => $remaining
        ]);
        exit();
    }

    public function get_rank_value($role, $rank) {
        if ($role === 'student') return 0;

        if ($rank === 'Head Administrator') return 100;
        if ($rank === 'System Admin') return 90;
        if ($role === 'admin') return 80;

        switch ($rank) {
            case 'Professor': return 50;
            case 'Assoc. Prof': return 40;
            case 'Asst. Prof': return 30;
            case 'Instructor II': return 20;
            case 'Instructor I': return 10;
            default: return 5;
        }
    }

    public function add_exam() {
        $subject_id = $_POST['subject_id'];
        $date = $_POST['exam_date'];
        $time = $_POST['start_time'];
        $room = $_POST['room'];
        $status = 'Upcoming';

        $sql = "INSERT INTO exams (subject_id, exam_date, start_time, room, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("issss", $subject_id, $date, $time, $room, $status);

        if ($stmt->execute()) {
            $this->log_activity($_SESSION['user_id'], "Exam Management", "Scheduled exam for Subject ID: $subject_id");
            header("Location: ../pages/staff/exam-management.php?success=Exam scheduled successfully");
        } else {
            header("Location: ../pages/staff/exam-management.php?error=Failed to schedule exam");
        }
        exit();
    }

    public function get_all_exams() {
        $sql = "SELECT e.*, s.subject_code, s.subject_description, s.section_assigned 
                FROM exams e 
                JOIN subjects s ON e.subject_id = s.subject_id 
                ORDER BY e.exam_date ASC";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function delete_exam() {
        $id = $_POST['exam_id'];
        
        $sql = "DELETE FROM exams WHERE exam_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $this->log_activity($_SESSION['user_id'], "Exam Management", "Deleted exam schedule ID: $id");
            header("Location: ../pages/staff/exam-management.php?success=Exam deleted");
        } else {
            header("Location: ../pages/staff/exam-management.php?error=Failed to delete");
        }
        exit();
    }

    public function complete_exam() {
        $id = $_POST['exam_id'];
        $sql = "UPDATE exams SET status = 'Completed' WHERE exam_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $this->log_activity($_SESSION['user_id'], "Exam Management", "Completed exam schedule ID: $id");
        header("Location: ../pages/staff/exam-management.php?success=Exam marked as completed");
        exit();
    }

    public function get_student_exams() {
        $sql_sec = "SELECT section FROM student_profiles WHERE user_id = ?";
        $stmt_sec = $this->connection->prepare($sql_sec);
        $stmt_sec->bind_param("i", $user_id);
        $stmt_sec->execute();
        $res_sec = $stmt_sec->get_result()->fetch_assoc();

        if (!$res_sec || empty($res_sec['section'])) {
            return [];
        }
        $section = $res_sec['section'];

        $sql = "SELECT e.*, s.subject_code, s.subject_description 
                FROM exams e
                JOIN subjects s ON e.subject_id = s.subject_id
                WHERE s.section_assigned = ? 
                AND e.status = 'Upcoming' 
                ORDER BY e.exam_date ASC";
                
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $section);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_student_assignments($user_id) {
        $sql_sec = "SELECT section FROM student_profiles WHERE user_id = ?";
        $stmt_sec = $this->connection->prepare($sql_sec);
        $stmt_sec->bind_param("i", $user_id);
        $stmt_sec->execute();
        $res_sec = $stmt_sec->get_result()->fetch_assoc();
        $section = $res_sec['section'] ?? '';

        if (!$section) return [];

        $sql = "SELECT r.*, s.subject_description 
                FROM resources r
                JOIN subjects s ON r.subject_code = s.subject_code
                WHERE s.section_assigned = ? 
                AND r.category = 'Assignment' 
                AND r.status = 'Published'
                ORDER BY r.created_at DESC LIMIT 5";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $section);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_student_resources($user_id) {
        $sql_sec = "SELECT section FROM student_profiles WHERE user_id = ?";
        $stmt_sec = $this->connection->prepare($sql_sec);
        $stmt_sec->bind_param("i", $user_id);
        $stmt_sec->execute();
        $res_sec = $stmt_sec->get_result()->fetch_assoc();
        $section = $res_sec['section'] ?? '';

        if (!$section) return [];

        $sql = "SELECT r.*, s.subject_description 
                FROM resources r
                JOIN subjects s ON r.subject_code = s.subject_code
                WHERE s.section_assigned = ? 
                AND r.status = 'Published'
                ORDER BY r.created_at DESC";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $section);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_student_fees($user_id) {
        $sql = "SELECT * FROM student_fees WHERE student_id = ? ORDER BY due_date ASC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_transaction_history($user_id) {
        $sql = "SELECT * FROM student_payments WHERE student_id = ? ORDER BY payment_date DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_payment_summary($user_id) {
        $sql = "SELECT SUM(amount) as balance FROM student_fees WHERE student_id = ? AND status != 'Paid'";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        
        $sql_paid = "SELECT SUM(amount) as total_paid FROM student_payments WHERE student_id = ? AND status = 'Verified'";
        $stmt_p = $this->connection->prepare($sql_paid);
        $stmt_p->bind_param("i", $user_id);
        $stmt_p->execute();
        $paid = $stmt_p->get_result()->fetch_assoc();

        $sql_pending = "SELECT SUM(amount) as pending FROM student_payments WHERE student_id = ? AND status = 'Pending'";
        $stmt_pen = $this->connection->prepare($sql_pending);
        $stmt_pen->bind_param("i", $user_id);
        $stmt_pen->execute();
        $pending = $stmt_pen->get_result()->fetch_assoc();

        return [
            'balance' => $res['balance'] ?? 0,
            'total_paid' => $paid['total_paid'] ?? 0,
            'pending' => $pending['pending'] ?? 0
        ];
    }

    public function submit_payment() {
        $user_id = $_SESSION['user_id'];
        $amount = $_POST['amount'];
        $method = $_POST['method'];
        $ref_no = $_POST['reference_no'];
        $date = date('Y-m-d');
        $status = 'Pending';

        $sql = "INSERT INTO student_payments (student_id, reference_no, amount, method, payment_date, status) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("isdsss", $user_id, $ref_no, $amount, $method, $date, $status);

        if ($stmt->execute()) {
            $this->log_activity($user_id, "Payment", "Submitted payment of $amount via $method Ref: $ref_no");
            header("Location: ../pages/student/payments.php?success=Payment submitted for verification");
        } else {
            header("Location: ../pages/student/payments.php?error=Transaction failed");
        }
        exit();
    }

    public function get_all_payments() {
        $sql = "SELECT p.*, s.first_name, s.last_name, s.course, s.year_level 
                FROM student_payments p
                JOIN student_profiles s ON p.student_id = s.user_id 
                ORDER BY 
                CASE WHEN p.status = 'Pending' THEN 1 ELSE 2 END, 
                p.payment_date DESC";
        $result = $this->connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function process_payment() {
        $payment_id = $_POST['payment_id'];
        $action = $_POST['action'];
        $new_status = ($action === 'verify') ? 'Verified' : 'Rejected';

        $sql = "UPDATE student_payments SET status = ? WHERE payment_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $new_status, $payment_id);
        
        if ($stmt->execute() && $new_status === 'Verified') {
            $q_pay = $this->connection->prepare("SELECT student_id, amount FROM student_payments WHERE payment_id = ?");
            $q_pay->bind_param("i", $payment_id);
            $q_pay->execute();
            $payment_data = $q_pay->get_result()->fetch_assoc();
            
            $student_id = $payment_data['student_id'];
            $money_on_hand = $payment_data['amount'];

            $q_fees = $this->connection->prepare("SELECT fee_id, amount FROM student_fees WHERE student_id = ? AND status != 'Paid' ORDER BY due_date ASC");
            $q_fees->bind_param("i", $student_id);
            $q_fees->execute();
            $fees = $q_fees->get_result()->fetch_all(MYSQLI_ASSOC);

            foreach ($fees as $fee) {
                if ($money_on_hand <= 0) break;

                $fee_id = $fee['fee_id'];
                $fee_amount = $fee['amount'];

                if ($money_on_hand >= $fee_amount) {
                    $this->connection->query("UPDATE student_fees SET status = 'Paid', amount = 0 WHERE fee_id = $fee_id");
                    $money_on_hand -= $fee_amount;
                } else {
                    $new_amount = $fee_amount - $money_on_hand;
                    $this->connection->query("UPDATE student_fees SET amount = $new_amount, status = 'Unpaid' WHERE fee_id = $fee_id");
                    $money_on_hand = 0;
                }
            }

            $this->log_activity($_SESSION['user_id'], "Payment Review", "Verified payment & updated fees for ID: $student_id");
            header("Location: ../pages/staff/payment-review.php?success=Payment verified and fees updated.");
        } else {
            header("Location: ../pages/staff/payment-review.php?error=Payment rejected.");
        }
        exit();
    }

    public function assign_fee() {
        $student_id = $_POST['student_id'];
        $title = $_POST['title'];
        $category = $_POST['category'];
        $amount = $_POST['amount'];
        $due_date = $_POST['due_date'];
        $status = 'Unpaid';

        $sql = "INSERT INTO student_fees (student_id, title, category, amount, due_date, status) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("issdss", $student_id, $title, $category, $amount, $due_date, $status);

        if ($stmt->execute()) {
            $this->log_activity($_SESSION['user_id'], "Billing", "Assigned fee '$title' (₱$amount) to Student ID: $student_id");
            header("Location: ../pages/staff/payment-review.php?success=Fee assigned successfully.");
        } else {
            header("Location: ../pages/staff/payment-review.php?error=Failed to assign fee.");
        }
        exit();
    }
}

$controller = new controller();
$controller->actionreader();

?>