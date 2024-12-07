<?php require APPROOT . '/views/inc/components/header_public.php'; ?>

<main>
    <div class="auth-container">
        <div class="auth-form-section">
            <div class="auth-form-container">
                <h2><?php echo $data['title']; ?></h2>

                <?php if ($data['justSubmitted']): ?>
                    <div class="box-info">
                        <li class="completed">
                            <i class='bx bx-check-circle'></i>
                            <span class="text">
                                <h3>Application Submitted Successfully!</h3>
                                <p>We will review your application and get back to you soon.</p>
                            </span>
                        </li>
                    </div>
                <?php endif; ?>

                <?php if ($data['application']): ?>
                    <div class="table-data">
                        <div class="order">
                            <table>
                                <tbody>
                                    <tr>
                                        <td><strong>Application ID</strong></td>
                                        <td><?php echo $data['application']->application_id; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>
                                            <span class="status <?php echo strtolower($data['application']->status); ?>">
                                                <?php echo ucfirst($data['application']->status); ?>
                                            </span>
                                        </td>
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

                    <?php if ($data['application']->status === 'pending'): ?>
                        <div class="box-info">
                            <li class="not-completed">
                                <i class='bx bx-time-five'></i>
                                <span class="text">
                                    <h3>Under Review</h3>
                                    <p>Our team is currently reviewing your application. This process typically takes 3-5 business days.</p>
                                </span>
                            </li>
                        </div>
                    <?php elseif ($data['application']->status === 'approved'): ?>
                        <div class="box-info">
                            <li class="completed">
                                <i class='bx bx-check-circle'></i>
                                <span class="text">
                                    <h3>Approved!</h3>
                                    <p>Congratulations! Your supplier application has been approved. You can now start using our supplier features.</p>
                                </span>
                            </li>
                        </div>
                    <?php elseif ($data['application']->status === 'rejected'): ?>
                        <div class="box-info">
                            <li class="error">
                                <i class='bx bx-x-circle'></i>
                                <span class="text">
                                    <h3>Application Rejected</h3>
                                    <p>Unfortunately, your application has been rejected. Please contact support for more information.</p>
                                </span>
                            </li>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="box-info">
                        <li>
                            <i class='bx bx-error-circle'></i>
                            <span class="text">
                                <h2>No Application Found</h2>
                                <p>You haven't submitted a supplier application yet. 
                                   <a href="<?php echo URLROOT; ?>/auth/supplier_register">Apply now</a>
                                </p>
                            </span>
                        </li>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>


<?php require APPROOT . '/views/inc/components/footer.php'; ?> 