<?php 
    session_start();
    require_once '../../backend/controller.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        header("Location: ../auth/login.php");
        exit();
    }

    $controller = new controller();
    $user_id = $_SESSION['user_id'];

    $fees = $controller->get_student_fees($user_id);
    $transactions = $controller->get_transaction_history($user_id);
    $summary = $controller->get_payment_summary($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="stylesheet" href="../../assets/css/payments.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/modal.js" defer></script>
    <title>Payments & Transactions</title>
</head>
<body>
    <?php require_once '../../components/header.php';?>

    <div class="container">
        <?php require_once '../../components/sidebar.php';?>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Payments</h1>
                    <h3>View your statement of account and manage transactions.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-primary" onclick="initPayment('', '')">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </button>
                </div>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <img src="../../assets/img/icons/success-icon.svg" alt="Success">
                    <span><?= htmlspecialchars($_GET['success']) ?></span>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <img src="../../assets/img/icons/error-icon.svg" alt="Error">
                    <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <div class="summary-row">
                <div class="summary-card balance">
                    <div class="icon-wrapper"><i class="fas fa-wallet"></i></div>
                    <div class="summary-text">
                        <p>Total Balance Due</p>
                        <h2>₱<?= number_format($summary['balance'], 2) ?></h2>
                    </div>
                </div>
                <div class="summary-card paid">
                    <div class="icon-wrapper"><i class="fas fa-check-circle"></i></div>
                    <div class="summary-text">
                        <p>Total Paid</p>
                        <h2>₱<?= number_format($summary['total_paid'], 2) ?></h2>
                    </div>
                </div>
                <div class="summary-card pending">
                    <div class="icon-wrapper"><i class="fas fa-clock"></i></div>
                    <div class="summary-text">
                        <p>Pending Validation</p>
                        <h2>₱<?= number_format($summary['pending'], 2) ?></h2>
                    </div>
                </div>
            </div>

            <div class="payments-grid">
                
                <div class="main-column">
                    <div class="data-card">
                        <div class="card-header">
                            <h3>Outstanding Fees</h3>
                            <div class="filter-toggle">
                                <select>
                                    <option>Current Sem</option>
                                    <option>Previous</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Fee Description</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($fees)): ?>
                                        <tr><td colspan="5" style="text-align:center; padding: 2rem; color: #888;">No outstanding fees found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($fees as $fee): 
                                            $statusClass = strtolower($fee['status']);
                                            $rawAmount = $fee['amount']; // Get raw amount for JS
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fee-info">
                                                    <p class="title"><?= htmlspecialchars($fee['title']) ?></p>
                                                    <p class="sub"><?= htmlspecialchars($fee['category']) ?></p>
                                                </div>
                                            </td>
                                            <td><?= date("M d, Y", strtotime($fee['due_date'])) ?></td>
                                            <td class="amount">₱<?= number_format($fee['amount'], 2) ?></td>
                                            <td><span class="status-pill <?= $statusClass ?>"><?= $fee['status'] ?></span></td>
                                            <td>
                                                <?php if($fee['status'] === 'Paid'): ?>
                                                    <button class="icon-btn" disabled style="opacity: 0.5; cursor: default;"><i class="fas fa-check"></i></button>
                                                <?php else: ?>
                                                    <button class="btn-sm" onclick="initPayment('', '<?= $rawAmount ?>')">Pay</button>
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

                <div class="side-column">
                    
                    <div class="widget-card">
                        <h3>Payment Channels</h3>
                        <div class="payment-methods-list">
                            <div class="method-item" onclick="initPayment('Bank Transfer', '')">
                                <div class="method-icon"><i class="fas fa-university"></i></div>
                                <span>Bank Transfer</span>
                                <i class="fas fa-chevron-right arrow"></i>
                            </div>
                            <div class="method-item" onclick="initPayment('GCash', '')">
                                <div class="method-icon"><i class="fas fa-mobile-alt"></i></div>
                                <span>GCash / Maya</span>
                                <i class="fas fa-chevron-right arrow"></i>
                            </div>
                            <div class="method-item" onclick="initPayment('Over-the-Counter', '')">
                                <div class="method-icon"><i class="fas fa-store"></i></div>
                                <span>Over-the-Counter</span>
                                <i class="fas fa-chevron-right arrow"></i>
                            </div>
                        </div>
                    </div>

                    <div class="widget-card">
                        <h3>Recent Receipts</h3>
                        <div class="receipts-list">
                            <?php if(empty($transactions)): ?>
                                <p style="color:#999; font-size:0.9rem; text-align:center; padding:1rem;">No history yet.</p>
                            <?php else: ?>
                                <?php foreach(array_slice($transactions, 0, 5) as $txn): ?>
                                <div class="receipt-item">
                                    <div class="receipt-details">
                                        <p class="ref">Ref: <?= htmlspecialchars($txn['reference_no']) ?></p>
                                        <p class="date"><?= htmlspecialchars($txn['method']) ?> &bull; <?= date("M d", strtotime($txn['payment_date'])) ?></p>
                                    </div>
                                    <div style="text-align: right;">
                                        <span class="amount-small">₱<?= number_format($txn['amount'], 2) ?></span><br>
                                        <small style="font-size: 0.7rem; color: #888;"><?= $txn['status'] ?></small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div id="payment-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box" style="max-width: 500px;">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Make a Payment</h3></div>
            
            <form action="../../backend/controller.php?method_finder=submit_payment" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-grid single-col">
                    <div class="input-group">
                        <label>Payment Method</label>
                        <select name="method" id="pay-method" required style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                            <option value="" disabled selected>Select Method</option>
                            <option value="GCash">GCash</option>
                            <option value="Maya">Maya</option>
                            <option value="Bank Transfer">Bank Transfer (BDO/BPI)</option>
                            <option value="Over-the-Counter">Over-the-Counter</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label>Amount (₱)</label>
                        <input type="number" name="amount" id="pay-amount" step="0.01" placeholder="0.00" required 
                               style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                    </div>

                    <div class="input-group">
                        <label>Reference Number</label>
                        <input type="text" name="reference_no" placeholder="OR Number or Ref ID" required 
                               style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                        <small style="color: #888; margin-top: 0.3rem;">Please enter the Ref/OR number from your receipt.</small>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" style="width:100%; margin-top:1rem;">Submit Payment</button>
            </form>
        </div>
    </div>

</body>
</html>