<?php 
    session_start();
    require_once '../../backend/controller.php';

    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'teacher')) {
        header("Location: ../auth/login.php");
        exit();
    }

    $controller = new controller();
    $payments = $controller->get_all_payments();
    $students = $controller->get_users_by_role('student', 'active');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/modal.js" defer></script>
    <title>Payment Review</title>
</head>
<body>
    <?php require_once '../../components/header.php'?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'?>

        <div class="content">
            <div class="section-header">
                <div class="header-details">
                    <h1>Payment Review</h1>
                    <h3>Manage student transactions and assign CIS-related fees.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-primary" onclick="showModal('assign-fee-modal')">
                        <i class="fas fa-plus-circle"></i> Assign Fee
                    </button>
                </div>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <span><?= htmlspecialchars($_GET['success']) ?></span>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <div class="data-card">
                <div class="table-responsive">
                    <table class="searchable-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Reference No.</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($payments)): ?>
                                <tr><td colspan="7" style="text-align:center; padding:2rem;">No transaction records found.</td></tr>
                            <?php else: ?>
                                <?php foreach($payments as $pay): 
                                    $statusClass = 'pending';
                                    if($pay['status'] === 'Verified') $statusClass = 'active'; 
                                    if($pay['status'] === 'Rejected') $statusClass = 'inactive'; 
                                ?>
                                <tr>
                                    <td>
                                        <div style="display:flex; flex-direction:column;">
                                            <span style="font-weight:600; color:#333;"><?= htmlspecialchars($pay['first_name'] . ' ' . $pay['last_name']) ?></span>
                                            <span style="font-size:0.8rem; color:#888;"><?= htmlspecialchars($pay['course'] . ' ' . $pay['year_level']) ?></span>
                                        </div>
                                    </td>
                                    <td style="font-family:monospace; font-weight:600; color:#555;"><?= htmlspecialchars($pay['reference_no']) ?></td>
                                    <td><?= htmlspecialchars($pay['method']) ?></td>
                                    <td style="font-weight:bold;">₱<?= number_format($pay['amount'], 2) ?></td>
                                    <td><?= date("M d, Y", strtotime($pay['payment_date'])) ?></td>
                                    <td><span class="status-pill <?= $statusClass ?>"><?= $pay['status'] ?></span></td>
                                    <td>
                                        <?php if($pay['status'] === 'Pending'): ?>
                                            <div class="action-buttons">
                                                <form action="../../backend/controller.php?method_finder=process_payment" method="POST">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <input type="hidden" name="payment_id" value="<?= $pay['payment_id'] ?>">
                                                    <input type="hidden" name="action" value="verify">
                                                    <button class="icon-btn" style="color: #166534; border-color: #166534;" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="../../backend/controller.php?method_finder=process_payment" method="POST">
                                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                    <input type="hidden" name="payment_id" value="<?= $pay['payment_id'] ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button class="icon-btn delete" title="Reject" onclick="return confirm('Reject this payment?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <span style="font-size:0.8rem; color:#aaa;">Processed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="assign-fee-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box" style="max-width: 550px;">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            
            <div class="modal-header" style="margin-bottom: 1rem;">
                <h3 style="margin: 0;">Assign Fee to Student</h3>
                <p style="color: #666; font-size: 0.9rem; margin-top: 0.25rem;">Create a new billing record for a student.</p>
            </div>
            
            <form action="../../backend/controller.php?method_finder=assign_fee" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="input-group" style="margin-bottom: 1.25rem;">
                    <label style="font-weight: 600; color: #333;">Select Student</label>
                    <select name="student_id" required style="width:100%; padding:0.8rem; border:1px solid #ccc; border-radius:0.5rem; background-color: #fff;">
                        <option value="" disabled selected>-- Search Name or ID --</option>
                        <?php foreach($students as $stu): ?>
                            <option value="<?= $stu['user_id'] ?>">
                                <?= htmlspecialchars($stu['last_name'] . ', ' . $stu['first_name']) ?> (<?= $stu['unique_id'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="background-color: #f8f9fa; border: 1px dashed #ced4da; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.25rem;">
                    <label style="font-size: 0.85rem; color: #166534; font-weight: 700; display:block; margin-bottom:0.5rem;">
                        <i class="fas fa-bolt"></i> Quick Auto-Fill (BSU CIS Standard Fees)
                    </label>
                    <select id="fee-preset" onchange="applyFeePreset()" style="width:100%; padding:0.6rem; border:1px solid #166534; color: #166534; border-radius:0.3rem; font-size: 0.9rem; font-weight: 500;">
                        <option value="" selected>-- Select a Preset to Auto-fill --</option>
                        <option value="BYTE">BYTE Membership (₱150.00)</option>
                        <option value="SSG">SSG Membership (₱60.00)</option>
                        <option value="Uniform_M">Male Uniform Set (₱850.00)</option>
                        <option value="Uniform_F">Female Uniform Set (₱850.00)</option>
                        <option value="DeptShirt">CIS Department Shirt (₱350.00)</option>
                        <option value="ID_Replace">ID Replacement (₱150.00)</option>
                    </select>
                </div>

                <div class="form-grid single-col" style="gap: 1rem;">
                    
                    <div class="input-group">
                        <label style="font-weight: 500;">Fee Description / Title</label>
                        <input type="text" name="title" id="fee-title" placeholder="e.g. Midterm Project Fee" required 
                               style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="input-group">
                            <label style="font-weight: 500;">Category</label>
                            <select name="category" id="fee-category" style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                                <option value="Org Fees">Org Fees</option>
                                <option value="Uniforms">Uniforms</option>
                                <option value="School Fees">School Fees</option>
                                <option value="Misc Fees">Misc Fees</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label style="font-weight: 500;">Amount (₱)</label>
                            <input type="number" name="amount" id="fee-amount" step="0.01" placeholder="0.00" required 
                                   style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                        </div>
                    </div>

                    <div class="input-group">
                        <label style="font-weight: 500;">Due Date</label>
                        <input type="date" name="due_date" required 
                               style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem; color: #555;">
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" style="width:100%; margin-top:1.5rem; padding: 0.9rem; font-size: 1rem;">
                    <i class="fas fa-paper-plane"></i> Assign Fee to Student
                </button>
            </form>
        </div>
    </div>
</body>
</html>