<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php'; ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Create Collection Bag</h1>
            <ul class="breadcrumb">
                <li><a href="<?= URLROOT ?>/inventory/collectionBags">Collection Bags</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Create Bag</a></li>
            </ul>
        </div>
    </div>

    <!-- Error Messages -->
    

    <form id="createBagForm" method="POST" action="<?php echo URLROOT; ?>/inventory/createBag">

        <!-- Bag Information -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Bag Information</h3>
                </div>
                <div class="section-content">
                    <div class="info-row">
                        <label class="label" for="capacity">Capacity (kg):</label>
                        <input type="number" step="0.01" id="capacity" name="capacity_kg" class="form-control" required
                            min="1" value="40.00">
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code Preview -->
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Next Bag Id</h3>
                </div>
                <div class="section-content">
                    <div class="qr-container">
                        <h1><?php echo $data['next_bag_id'] ?></h1>
                        <!-- <p class="qr-explanation">QR code will be generated upon bag creation</p> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Bag</button>
    </form>

</main>

<!-- Include QR code library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Create dummy QR code for preview
        const qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "SAMPLE-BAG-CODE",
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>

<style>
    /* Table Data Container */
    .table-data {
        margin-bottom: 24px;
    }

    .order {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    /* Section Headers */
    .head {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .head h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Content Sections */
    .section-content {
        padding: 8px 0;
    }

    /* Info Rows */
    .info-row {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        transition: background-color 0.2s;
    }

    .info-row:hover {
        background-color: #f8f9fa;
    }

    .info-row .label {
        flex: 0 0 200px;
        font-size: 14px;
        color: #6c757d;
    }

    /* QR Code container */
    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    #qrcode {
        margin-bottom: 15px;
    }

    .qr-explanation {
        font-size: 14px;
        color: #6c757d;
        text-align: center;
    }

    /* Form Control */
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    /* Submit button styling */
    .btn-primary {
        padding: 10px 20px;
        background-color: #10b981;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        margin: 0 0 20px 20px;
    }

    .btn-primary:hover {
        background-color: #059669;
    }
</style>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>