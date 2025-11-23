<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/payments.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Payments & Transactions</title>
</head>
<body>
    <div id="header-placeholder"></div>

    <div class="container">
        <div id="sidebar-placeholder"></div>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Payments</h1>
                    <h3>View your statement of account and manage transactions.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-history"></i> Transaction History
                    </button>
                    <button class="btn-primary">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </button>
                </div>
            </div>

            <div class="summary-row">
                <div class="summary-card balance">
                    <div class="icon-wrapper"><i class="fas fa-wallet"></i></div>
                    <div class="summary-text">
                        <p>Total Balance Due</p>
                        <h2>₱15,000.00</h2>
                    </div>
                </div>
                <div class="summary-card paid">
                    <div class="icon-wrapper"><i class="fas fa-check-circle"></i></div>
                    <div class="summary-text">
                        <p>Total Paid (Sem)</p>
                        <h2>₱20,000.00</h2>
                    </div>
                </div>
                <div class="summary-card pending">
                    <div class="icon-wrapper"><i class="fas fa-clock"></i></div>
                    <div class="summary-text">
                        <p>Pending Validation</p>
                        <h2>₱5,000.00</h2>
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
                                    <tr>
                                        <td>
                                            <div class="fee-info">
                                                <p class="title">Tuition Fee - Prelims</p>
                                                <p class="sub">School Fees</p>
                                            </div>
                                        </td>
                                        <td>Mar 05, 2025</td>
                                        <td class="amount">₱10,000.00</td>
                                        <td><span class="status-pill pending">Unpaid</span></td>
                                        <td><button class="btn-sm">Pay</button></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="fee-info">
                                                <p class="title">Athletic Fee</p>
                                                <p class="sub">Misc Fees</p>
                                            </div>
                                        </td>
                                        <td>Mar 05, 2025</td>
                                        <td class="amount">₱2,000.00</td>
                                        <td><span class="status-pill pending">Unpaid</span></td>
                                        <td><button class="btn-sm">Pay</button></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="fee-info">
                                                <p class="title">Library Membership</p>
                                                <p class="sub">Misc Fees</p>
                                            </div>
                                        </td>
                                        <td>Feb 25, 2025</td>
                                        <td class="amount">₱500.00</td>
                                        <td><span class="status-pill overdue">Overdue</span></td>
                                        <td><button class="btn-sm outline">Pay</button></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="fee-info">
                                                <p class="title">Laboratory Fee</p>
                                                <p class="sub">Lab Fees</p>
                                            </div>
                                        </td>
                                        <td>Jan 15, 2025</td>
                                        <td class="amount">₱2,500.00</td>
                                        <td><span class="status-pill paid">Paid</span></td>
                                        <td><button class="icon-btn"><i class="fas fa-download"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="side-column">
                    
                    <div class="widget-card">
                        <h3>Payment Channels</h3>
                        <div class="payment-methods-list">
                            <div class="method-item">
                                <div class="method-icon"><i class="fas fa-university"></i></div>
                                <span>Bank Transfer</span>
                                <i class="fas fa-chevron-right arrow"></i>
                            </div>
                            <div class="method-item">
                                <div class="method-icon"><i class="fas fa-mobile-alt"></i></div>
                                <span>GCash / Maya</span>
                                <i class="fas fa-chevron-right arrow"></i>
                            </div>
                            <div class="method-item">
                                <div class="method-icon"><i class="fas fa-credit-card"></i></div>
                                <span>Credit/Debit Card</span>
                                <i class="fas fa-chevron-right arrow"></i>
                            </div>
                        </div>
                    </div>

                    <div class="widget-card">
                        <h3>Recent Receipts</h3>
                        <div class="receipts-list">
                            <div class="receipt-item">
                                <div class="receipt-details">
                                    <p class="ref">OR #12350</p>
                                    <p class="date">Feb 22, 2025</p>
                                </div>
                                <span class="amount-small">-₱2,000</span>
                            </div>
                            <div class="receipt-item">
                                <div class="receipt-details">
                                    <p class="ref">OR #12349</p>
                                    <p class="date">Feb 11, 2025</p>
                                </div>
                                <span class="amount-small">-₱5,000</span>
                            </div>
                        </div>
                        <button class="btn-text">View All History</button>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="../../assets/js/component-student.js"></script>
</body>
</html>