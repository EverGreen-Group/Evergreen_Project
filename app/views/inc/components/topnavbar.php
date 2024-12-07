<!-- NAVBAR -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <!-- <i class='bx bx-menu'></i> -->
        <a href="#" class="nav-link" style="visibility: hidden;">Categories</a>
        <form action="#" style="visibility: hidden;">
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="notification">
            <i class='bx bxs-bell'></i>
            <span class="num">8</span>
        </a>
        <a href="#" class="profile">
            <img src="<?php echo URLROOT; ?>/uploads/supplier_photos/default-supplier.png">
        </a>
    </nav>
    <!-- NAVBAR -->

    <script>
    // Sidebar Collapse
    const menuBar = document.querySelector('#content nav .bx.bx-menu');
    const sidebar = document.getElementById('sidebar');

    menuBar.addEventListener('click', function () {
        sidebar.classList.toggle('hide');
    });

    // Close sidebar on button click
    menuBar.addEventListener('click', function () {
        if (!sidebar.classList.contains('hide')) {
            sidebar.classList.add('hide');
        }
    });

    // Automatically hide sidebar on smaller screens
    if(window.innerWidth <= 768) {
        sidebar.classList.add('hide');
    }
    </script>

