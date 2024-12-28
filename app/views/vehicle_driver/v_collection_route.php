<?php require APPROOT . '/views/inc/components/header.php'; ?>
<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_driver/collection_route/collection_route.css">
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
    const collections = <?php echo json_encode($data['collections']); ?>;
    const collectionId = <?php echo $data['collection']->collection_id; ?>;
    const vehicleLocation = <?php echo json_encode($data['vehicleLocation']); ?>;
</script>
<script src="<?php echo URLROOT; ?>/public/js/vehicle_driver/collection_route_maps.js"></script>

<!-- <?php print_r($data['vehicleLocation']) ?> -->

<div class="map-container" id="map"></div>

<div class="bottom-nav">
    <button class="btn-arrive" onclick="markArrived()">
        <i class='bx bx-map-pin'></i> Mark Arrived
    </button>
    <button class="btn-view" onclick="viewCollection()">
        <i class='bx bx-collection'></i> View Collection
    </button>
</div>


<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdt_khahhXrKdrA8cLgKeQB2CZtde-_Vc&callback=initMap"></script>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>