<!-- NAVBAR -->
<section id="content">
    <!-- NAVBAR -->
    <nav>
        <i class='bx bx-menu'></i>
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

    <div id="profileDropdown" class="profile-dropdown" style="display: none;">
        <ul>
            <li><a href="<?php echo URLROOT; ?>/profile">Profile</a></li>
            <li><a href="<?php echo URLROOT; ?>/logout">Logout</a></li>
        </ul>
    </div>
    <!-- NAVBAR -->

<script>
    document.getElementById('profileIcon').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default anchor behavior
        const dropdown = document.getElementById('profileDropdown');
        dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
    });

    // Close the dropdown if clicked outside
    window.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        if (!event.target.matches('#profileIcon') && !event.target.matches('.profile-dropdown *')) {
            dropdown.style.display = 'none';
        }
    });
</script>

<style>
.profile-dropdown {
    position: absolute; /* Position it below the profile icon */
    right: 10px; /* Adjust as needed */
    background-color: white; /* Background color */
    border: 1px solid #ccc; /* Border */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Shadow for depth */
    z-index: 1000; /* Ensure it appears above other elements */
}

.profile-dropdown ul {
    list-style: none; /* Remove bullet points */
    padding: 0; /* Remove padding */
    margin: 0; /* Remove margin */
}

.profile-dropdown li {
    padding: 10px; /* Padding for each item */
}

.profile-dropdown li a {
    text-decoration: none; /* Remove underline */
    color: black; /* Text color */
    display: block; /* Make the entire area clickable */
}

.profile-dropdown li:hover {
    background-color: #f0f0f0; /* Highlight on hover */
}

</style>