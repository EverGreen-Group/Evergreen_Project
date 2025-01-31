<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_manager.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
<!-- <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script> -->
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>




<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Collection #23</h1>
            <div class="left">
                <div class="status-tag ongoing">Ongoing</div> <!-- Added status tag -->
            </div>
        </div>


    </div>

    <div class="action-buttons">
        <a href="#" class="btn btn-primary">
            <i class='bx bx-show'></i>
            View Collection History
        </a>
    </div>


    <!-- Box Info -->
    <ul class="box-info">
        <li>
            <i class='bx bxs-car'></i>
            <span class="text">
                <h3>0</h3>
                <p>Vehicles</p>
                <small> Available</small>
            </span>
        </li>
        <li>
            <i class='bx bxs-car'></i>
            <span class="text">
                <h3>0</h3>
                <p>Vehicles</p>
                <small> Available</small>
            </span>
        </li>
    </ul>




</main>


<style>
/* Add this to your CSS file or within a <style> tag */
.status-tag {
    display: inline-block; /* Make it an inline-block element */
    padding: 10px 20px; /* Add some padding */
    font-size: 18px; /* Adjust font size */
    color: white; /* Text color */
    background-color: #28a745; /* Green background for ongoing status */
    border-radius: 5px; /* Rounded corners */
    margin-top: 10px; /* Space above the tag */
    font-weight: bold; /* Make the text bold */
}
</style>



<?php require APPROOT . '/views/inc/components/footer.php'; ?>
