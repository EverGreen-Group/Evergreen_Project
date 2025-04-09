<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2><?php echo $data['title']; ?></h2>

                <?php if ($data['justSubmitted']): ?>
                    <div class="box-info success-box">
                        <div class="info-icon">
                            <i class='bx bx-check-circle'></i>
                        </div>
                        <div class="info-content">
                            <h3>Application Submitted Successfully!</h3>
                            <p>We will review your application and get back to you soon.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($data['application']): ?>
                    <!-- Application Status Card -->
                    <div class="status-card">
                        <div class="status-header">
                            <h3>Application Details</h3>
                            <span class="status-badge <?php echo strtolower($data['application']->status); ?>">
                                <?php echo ucfirst($data['application']->status); ?>
                            </span>
                        </div>
                        
                        <div class="table-data">
                            <div class="order">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><strong>Application ID</strong></td>
                                            <td><?php echo $data['application']->application_id; ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Submitted On</strong></td>
                                            <td><?php echo date('F j, Y', strtotime($data['application']->created_at)); ?></td>
                                        </tr>
                                        <?php if ($data['application']->updated_at !== $data['application']->created_at): ?>
                                            <tr>
                                                <td><strong>Last Updated</strong></td>
                                                <td><?php echo date('F j, Y', strtotime($data['application']->updated_at)); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Status Messages -->
                    <?php if ($data['application']->status === 'pending'): ?>
                        <div class="box-info pending-box">
                            <div class="info-icon">
                                <i class='bx bx-time-five'></i>
                            </div>
                            <div class="info-content">
                                <h3>Under Review</h3>
                                <p>Your application is under review. This process can take 3-5 business days</p>
                            </div>
                        </div>
                    <?php elseif ($data['application']->status === 'approved'): ?>
                        <div class="box-info success-box">
                            <div class="info-icon">
                                <i class='bx bx-check-circle'></i>
                            </div>
                            <div class="info-content">
                                <h3>Approved!</h3>
                                <p>Your supplier application has been approved. You can login and use as a supplier!</p>
                                <div class="cta-buttons">
                                    <a href="<?php echo URLROOT; ?>/supplier/" class="btn primary-btn">Go to Supplier Dashboard</a>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($data['application']->status === 'rejected'): ?>
                        <div class="box-info error-box">
                            <div class="info-icon">
                                <i class='bx bx-x-circle'></i>
                            </div>
                            <div class="info-content">
                                <h3>Application Rejected</h3>
                                <p>Unfortunately, your application has been rejected.</p>
                                <div class="additional-info">
                                    <p>Please contact the factory if you think this is a mistake</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="box-info info-box">
                        <div class="info-icon">
                            <i class='bx bx-error-circle'></i>
                        </div>
                        <div class="info-content">
                            <h3>No Application Found</h3>
                            <p>You haven't submitted a supplier application yet.</p>
                            <div class="cta-buttons">
                                <a href="<?php echo URLROOT; ?>/auth/supplier_register" class="btn primary-btn">Apply Now</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<style>
    .auth-form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
    }

    .status-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .status-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .status-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #343a40;
    }

    .status-badge {
        padding: 0.3rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-badge.approved {
        background-color: #d4edda;
        color: #155724;
    }

    .status-badge.rejected {
        background-color: #f8d7da;
        color: #721c24;
    }

    .box-info {
        display: flex;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        align-items: flex-start;
    }

    .info-icon {
        font-size: 2.5rem;
        margin-right: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-content {
        flex: 1;
    }

    .info-content h3 {
        margin-top: 0;
        margin-bottom: 0.5rem;
        font-size: 1.3rem;
    }

    .success-box {
        background-color: #d4edda;
        border-left: 5px solid #28a745;
    }

    .success-box .info-icon {
        color: #28a745;
    }

    .pending-box {
        background-color: #fff3cd;
        border-left: 5px solid #ffc107;
    }

    .pending-box .info-icon {
        color: #ffc107;
    }

    .error-box {
        background-color: #f8d7da;
        border-left: 5px solid #dc3545;
    }

    .error-box .info-icon {
        color: #dc3545;
    }

    .info-box {
        background-color: #e2f0fb;
        border-left: 5px solid #17a2b8;
    }

    .info-box .info-icon {
        color: #17a2b8;
    }

    .table-data {
        padding: 1rem 1.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    table tr:last-child td {
        border-bottom: none;
    }

    .cta-buttons {
        margin-top: 1.5rem;
        display: flex;
        gap: 1rem;
    }

    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .primary-btn {
        background-color: #4361ee;
        color: white;
    }

    .primary-btn:hover {
        background-color: #3a56d4;
    }

    .secondary-btn {
        background-color: #6c757d;
        color: white;
    }

    .secondary-btn:hover {
        background-color: #5a6268;
    }

    .additional-info {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(0,0,0,0.1);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .box-info {
            flex-direction: column;
        }
        
        .info-icon {
            margin-right: 0;
            margin-bottom: 1rem;
        }
        
        .cta-buttons {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            text-align: center;
            margin-bottom: 0.5rem;
        }
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>