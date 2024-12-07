<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <select id="delivery_id" required="true" class="form-control selectized" name="delivery_id">
          <option value="">Select Delivery</option>
          <?php foreach ($deliverylist as $delivery) { ?>
            <option value="<?= $delivery['id'] ?>">
              Order #<?= $delivery['order_id'] ?> - <?= $delivery['customer_name'] ?>
            </option>
          <?php } ?>
        </select>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <div id="map_canvas" style="width: 100%; height: 650px"></div>
    </div>
  </div>
</div>

<script id="group" data-name="0" src="<?= base_url(); ?>assets/js/delivery-live.js"></script>
<script src="<?= base_url(); ?>assets/fontawesome-markers.min.js"></script> 