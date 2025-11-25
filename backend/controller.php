<?php
session_start();

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
}

$controller = new controller();
$controller->actionreader();

?>