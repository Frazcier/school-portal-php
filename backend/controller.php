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
            } else if ($action === 'change_password') {
                $this->change_password();
            } else if ($action === 'change_avatar') {
                $this->change_avatar();
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
        $prefix = "ADM";
        
        $default_student_pic = '../../assets/img/profile-pictures/profile.svg';
        $default_staff_pic = '../../assets/img/profile-pictures/profile-staff.svg';

        if ($role === 'student') {
            $course = $_POST['course'];
            $year_level = $_POST['year_level'];
            $prefix = "STU";
        } else {
            $department = $_POST['department'] ?? 'Administration';
            $academic_rank = $_POST['academic_rank'] ?? 'System Admin';
            $prefix = "TCH";
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
            } else if ($role === 'teacher'){
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

                    $u_id = $user['user_id'];
                    $role = $user['role'];

                    if ($role === 'student') {
                        $sql_p = "SELECT * FROM student_profiles WHERE user_id = ?";
                    } else {
                        $sql_p = "SELECT * FROM staff_profiles WHERE user_id = ?";
                    }

                    $stmt_p = $this->connection->prepare($sql_p);
                    $stmt_p->bind_param("i", $u_id);
                    $stmt_p->execute();
                    $res_p = $stmt_p->get_result();
                    $profile = $res_p->fetch_assoc();

                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['unique_id'] = $user['unique_id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['middle_name'];
                    $_SESSION['middle_name'] = $user['middle_name'];
                    $_SESSION['email'] = $user['email'];
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

    public function change_password() {
        if (!isset($_SESSION['userID'])) {
            header("Location: ../pages/auth/login.php");
            exit();
        }

        $user_id = $_SESSION['userID'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        $sql = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {

                if (password_verify($current_password, $row['password'])) {
                    $new_hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);

                    $update_sql = "UPDATE users SET password = ? WHERE user_id = ?";
                    $update_stmt = $this->connection->prepare($update_sql);
                    $update_stmt->bind_param("si", $new_hashed_pass, $user_id);

                    if ($update_stmt->execute()) {
                        if ($_SESSION['role'] === 'student') {
                            $location = '../../pages/student/account-settings-student.php';
                        } else {
                            $location = '../../pages/staff/account-settings-staff.php';
                        }

                        header("Location: $location?page=settings&success=Password Updated Successfully");
                    } else {
                        header("Location: ../../pages/staff/staff-dashboard.php?page=settings&error=Database Error");
                    }
                } else {
                    header("Location: ../../pages/staff/staff-dashboard.php?page=settings&error=Incorrect Current Password");
                }
            }
        } else {
            header("Location: ../../pages/staff/staff-dashboard.php?page=settings&error=Connection Error");
        }
    }

    public function change_avatar() {
        echo "Hello World";
    }
}

$controller = new controller();
$controller->actionreader();

?>