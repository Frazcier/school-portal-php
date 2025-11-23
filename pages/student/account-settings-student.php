<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="stylesheet" href="../../assets/css/account-settings.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Account Settings</title>
</head>
<body>
    <div id="header-placeholder"></div>

    <div class="container">
        <div id="sidebar-placeholder"></div>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Account Settings</h1>
                    <h3>Manage your personal details and security preferences.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>

            <div class="settings-card profile-section">
                <div class="profile-header">
                    <div class="profile-img-container">
                        <img src="../../assets/img/profile-pictures/profile.svg" alt="Profile">
                        <button class="edit-icon" title="Change Picture">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <div class="profile-info-text">
                        <h2>Timothy Dionela</h2>
                        <p>Student &bullet; ID: STU-2025-0001</p>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="btn-secondary small">Remove Picture</button>
                </div>
            </div>

            <div class="settings-card">
                <div class="card-title-row">
                    <h3>Personal Information</h3>
                    <p>Update your student records and contact info.</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name</label>
                        <div class="input-box">
                            <i class="fas fa-user icon"></i>
                            <input type="text" value="Timothy">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Middle Name</label>
                        <div class="input-box">
                            <i class="fas fa-user icon"></i>
                            <input type="text" placeholder="Optional">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <div class="input-box">
                            <i class="fas fa-user icon"></i>
                            <input type="text" value="Dionela">
                        </div>
                    </div>
                </div>

                <div class="form-grid single-col">
                    <div class="form-group">
                        <label>Contact Email</label>
                        <div class="input-box">
                            <i class="fas fa-envelope icon"></i>
                            <input type="email" value="dionela.ikinamada@gmail.com">
                        </div>
                    </div>
                </div>
            </div>

            <div class="settings-card">
                <div class="card-title-row">
                    <h3>Security & Login</h3>
                    <p>Manage your password and account access.</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Current Password</label>
                        <div class="input-box">
                            <i class="fas fa-lock icon"></i>
                            <input type="password" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="input-box">
                            <i class="fas fa-key icon"></i>
                            <input type="password" placeholder="Enter new password">
                        </div>
                    </div>
                </div>

                <div class="security-footer">
                    <div class="danger-zone">
                        <h4>Danger Zone</h4>
                        <p>Deactivating your account will restrict access to portal services.</p>
                        <button class="btn-danger">Request Account Deactivation</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="../../assets/js/component-student.js"></script>
</body>
</html>