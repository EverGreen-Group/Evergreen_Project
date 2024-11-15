
<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_supplier.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Tea Leaves Supplier</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="SupplyDashboard.html">Home </a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#"> Edit Fertilizer Requests</a>
                        </li>
                    </ul>
                </div>

                <div class="table-data">
                    <div class="head">
                        <h2>Edit Fertilizer Request</h2>
                        <form method="POST" class="complaint-form" action="<?php echo URLROOT . '/supplier/editFertilizerRequest/' . $request['order_id']; ?>">
                            <div class="form-group">
                                <label><b> Update total amount of fertilizer: </b></label>
                                <label for="total_amount">Total Amount:</label>
                                <select id="complaint-type" name="total_amount">
                                    <?php
                                    for ($i = 1; $i <= 50; $i++) {
                                        $selected = $data['order']->total_amount == $i ? 'selected' : '';
                                        echo "<option value='$i' $selected>{$i}kg</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="button" >Update Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/script.js" />
</body>
</html>
