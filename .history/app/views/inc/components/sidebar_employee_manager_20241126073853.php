<section id="sidebar">
    <a href="<?php echo URLROOT; ?>" class="brand">
        <img src="../public/img/logo.svg" alt="Logo" />
        <span class="text">EVERGREEN</span>
    </a>
    <ul class="side-menu top">
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'index') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/index">
                <i class="bx bxs-dashboard"></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'leaves') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/leaves">
                <i class="bx bxs-calendar-check"></i>
                <span class="text">Leave Management</span>
            </a>
        </li>
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'attendance') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/attendance">
                <i class="bx bxs-time-five"></i>
                <span class="text">Attendance</span>
            </a>
        </li>
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'tasks') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/tasks">
                <i class="bx bxs-task"></i>
                <span class="text">Task Management</span>
            </a>
        </li>
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'evaluations') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/evaluations">
                <i class="bx bxs-badge-check"></i>
                <span class="text">Evaluations</span>
            </a>
        </li>
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'salary') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/salary">
                <i class="bx bxs-wallet"></i>
                <span class="text">Salary Management</span>
            </a>
        </li>
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'staff') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/staff">
                <i class="bx bxs-group"></i>
                <span class="text">Staff</span>
            </a>
        </li>
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'register') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/register">
                <i class="bx bxs-user-plus"></i>
                <span class="text">Register Employee</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li class="<?php echo (basename($_SERVER['REQUEST_URI']) == 'settings') ? 'active' : ''; ?>">
            <a href="<?php echo URLROOT; ?>/employeemanager/settings">
                <i class="bx bxs-cog"></i>
                <span class="text">Settings</span>
            </a>
        </li>
        <li>
            <a href="<?php echo URLROOT; ?>/employeemanager/personal-details" class="logout">
                <i class="bx bxs-user-detail"></i>
                <span class="text">Personal Details</span>
            </a>
        </li>
        <li>
            <a href="<?php echo URLROOT; ?>/auth/logout" class="logout">
                <i class="bx bxs-log-out-circle"></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section> 