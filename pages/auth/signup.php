<?php 
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <link rel="stylesheet" href="../../assets/css/auth.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <script src="../../assets/js/password-toggle.js" defer></script>
    <script src="../../assets/js/field-toggle.js" defer></script>
    <script src="../../assets/js/main.js" defer></script>
    <title>Create Account - School Portal</title>
</head>
<body>

    <div class="auth-container fadeIn">
        
        <div class="brand-side">
            <img src="../../assets/img/logo/for-guthib.png" alt="School Logo" class="logo">
            <h1>Join Us!</h1>
            <p>Enter your personal details and start your journey with us.</p>
            <a href="login.php" class="switch-btn">Login Instead</a>
        </div>

        <div class="form-side">
            <div class="form-header">
                <h2>Create Account</h2>
                <p>Fill in the information below.</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <img src="../../assets/img/icons/notif-3-icon.svg" alt="Error">
                    <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?> 

            <form action="../../backend/controller.php?method_finder=register" method="POST" onsubmit="return validatePassword()">
                
                <div class="input-group">
                    <div class="input-wrapper">
                        <select name="role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                            <option value="admin">Administrator</option>
                        </select>
                        <img class="icon" src="../../assets/img/icons/profile-icon.svg" alt="Role">
                    </div>
                </div>

                <div id="dynamic-fields">
    
                    <div id="student-fields" class="dynamic-section">
                        <div class="input-group">
                            <div class="input-wrapper">
                                <select name="course">
                                    <option value="" disabled selected>Select Degree Program</option>
                                    <option value="BSIT">BS Information Technology</option>
                                    <option value="BSDC">BS Development Communication</option>
                                    <option value="BLIS">BL Information Science</option>
                                </select>
                                <img class="icon" src="../../assets/img/icons/degree-icon.svg" alt="Course">
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem;">
                            <div class="input-group" style="flex: 1;">
                                <div class="input-wrapper">
                                    <select name="year_level">
                                        <option value="" disabled selected>Year Level</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                        <option value="Irregular">Irregular</option>
                                    </select>
                                    <img class="icon" src="../../assets/img/icons/calendar-icon.svg" alt="Level">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="teacher-fields" class="dynamic-section">
                        
                        <div class="input-group">
                            <div class="input-wrapper">
                                <select name="department">
                                    <option value="" disabled selected>Select Department</option>
                                    <option value="DIT">Dept. of Information Technology</option>
                                    <option value="DDC">Dept. of Dev. Communication</option>
                                    <option value="DLS">Dept. of Library Science</option>
                                </select>
                                <img class="icon" src="../../assets/img/icons/department-icon.svg" alt="Dept">
                            </div>
                        </div>

                        <div class="input-group">
                            <div class="input-wrapper">
                                <select name="academic_rank">
                                    <option value="" disabled selected>Position</option>
                                    <option value="Instructor I">Instructor I</option>
                                    <option value="Instructor II">Instructor II</option>
                                    <option value="Asst. Prof">Assistant Professor</option>
                                    <option value="Assoc. Prof">Associate Professor</option>
                                    <option value="Professor">Professor</option>
                                </select>
                                <img class="icon" src="../../assets/img/icons/academic-rank-icon.svg" alt="Rank">
                            </div>
                        </div>

                    </div>

                </div>

                <div style="display: flex; gap: 1rem;">
                    <div class="input-group" style="flex:1;">
                        <div class="input-wrapper">
                            <input type="text" name="first_name" placeholder="First Name" required>
                            <img class="icon" src="../../assets/img/icons/username-icon.svg" alt="User">
                        </div>
                    </div>
                    <div class="input-group" style="flex:1;">
                        <div class="input-wrapper">
                            <input type="text" name="last_name" placeholder="Last Name" required>
                            <img class="icon" src="../../assets/img/icons/username-icon.svg" alt="User">
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-wrapper">
                        <input type="text" name="middle_name" placeholder="Middle Name (Optional)">
                        <img class="icon" src="../../assets/img/icons/username-icon.svg" alt="User">
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-wrapper">
                        <input type="email" name="email" placeholder="Email Address" required>
                        <img class="icon" src="../../assets/img/icons/email-icon.svg" alt="Email">
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="password-field" placeholder="Create Password" required>
                        <img class="icon" src="../../assets/img/icons/password-icon.svg" alt="Lock">
                        <img src="../../assets/img/icons/eye-off-icon.svg" class="toggle-password" alt="Show Password">
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-wrapper">
                        <input type="password" name="confirm_password" id="confirm_password" class="password-field" placeholder="Confirm Password" required>
                        <img class="icon" src="../../assets/img/icons/password-icon.svg" alt="Lock">
                        <img src="../../assets/img/icons/eye-off-icon.svg" class="toggle-password" alt="Show Password">
                    </div>
                </div>

                <p id="password-error" style="color: #DC2626; font-size: 0.85rem; display: none; margin-bottom: 1rem;">
                    Passwords do not match.
                </p>

                <button type="submit" class="btn-submit">Register</button>

                <div class="extra-links">
                    <span class="mobile-only">Already have an account? <a href="login.php">Login</a></span>
                </div>
            </form>
        </div>
    </div>
</body>
</html>