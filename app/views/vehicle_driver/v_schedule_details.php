<?php require APPROOT . '/views/inc/components/header.php'; ?>
<?php require APPROOT . '/views/inc/components/sidebar_vehicle_driver.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_driver/schedule_details/styles.css">
<script src="<?php echo URLROOT; ?>/css/components/script.js"></script>
<!-- <script src="<?php echo URLROOT; ?>/public/js/vehicle_driver/schedule_detials.js"></script> -->
<script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>


<?php
function isWithinShiftTime($startTime, $endTime) {
    $now = new DateTime();
    $startDateTime = new DateTime($startTime);
    $endDateTime = new DateTime($endTime);
    return $now >= $startDateTime && $now <= $endDateTime;
}

// Get today's day and current time
$today = date('l');
$currentDateTime = new DateTime();

// Initialize variables
$isCompleted = false;
// Check if today matches the schedule's day and if it's within shift time
$isTodayScheduled = strtolower($data['schedule']->day) === strtolower($today);
$isWithinShiftTime = isWithinShiftTime($data['schedule']->start_time, $data['schedule']->end_time);
$canAccessCollectionFeatures = $isTodayScheduled && $isWithinShiftTime;

// Check if collection exists and has a status
if (isset($data['collection']) && $data['collection']) {
    $isCompleted = (isset($data['collection']->status) && 
                    strtolower($data['collection']->status) === 'completed');
}

// Print conditions
// echo "<div class='conditions'>";
// echo "<p>Schedule day: " . htmlspecialchars($data['schedule']->day) . "</p>";
// echo "<p>Today: " . htmlspecialchars($today) . "</p>";
// echo "<p>Is Today Scheduled: " . ($isTodayScheduled ? 'Yes' : 'No') . "</p>";
// echo "<p>Is Within Shift Time: " . ($isWithinShiftTime ? 'Yes' : 'No') . "</p>";
// echo "<p>Can Access Collection Features: " . ($canAccessCollectionFeatures ? 'Yes' : 'No') . "</p>";
// echo "<p>Is Collection Completed: " . ($isCompleted ? 'Yes' : 'No') . "</p>";
// echo "</div>";
?>

<main class="schedule-details-main">
    <div class="content-header">
        <div class="header-text">
            <h1>Collection Details</h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo URLROOT; ?>/vehicledriver/" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i>
            </a>
        </div>
    </div>

    <div class="schedule-content">
        <section class="schedule-info">
            <h2>Schedule Information</h2>
            <p>Week: <?php echo htmlspecialchars($data['schedule']->week_number); ?></p>
            <p>Day: <?php echo htmlspecialchars($data['schedule']->day); ?></p>
            <p>Shift: <?php echo htmlspecialchars($data['schedule']->start_time . ' - ' . $data['schedule']->end_time); ?></p>
        </section>

        <section class="vehicle-info">
            <h2>Vehicle Information</h2>
            <p>Vehicle Type: <?php echo htmlspecialchars($data['schedule']->vehicle_type); ?></p>
            <p>License Plate: <?php echo htmlspecialchars($data['schedule']->license_plate); ?></p>
            <p>Capacity: <?php echo htmlspecialchars($data['schedule']->capacity); ?> kg</p>
        </section>

        <section class="driver-info">
            <h2>Driver Information</h2>
            <p>Name: <?php echo htmlspecialchars($data['schedule']->first_name . ' ' . $data['schedule']->last_name); ?></p>
            <p>Contact: <?php echo htmlspecialchars($data['schedule']->email); ?></p>
        </section>

        <section class="route-info">
            <h2>Route Information</h2>
            <div class="route-header">
                <p><strong>Route Name:</strong> <?php echo htmlspecialchars($data['route']->route_name); ?></p>
                <p><strong>Number of Suppliers:</strong> <?php echo count($data['routeSuppliers']); ?></p>
            </div>
            <?php if (!empty($data['routeSuppliers'])): ?>
                <div class="suppliers-list">
                    <h3>Suppliers in Route</h3>
                    <table class="suppliers-table">
                        <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Location</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['routeSuppliers'] as $supplier): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($supplier->first_name . ' ' . $supplier->last_name); ?></td>
                                <td><?php echo htmlspecialchars($supplier->coordinates); ?></td>
                                <td><?php echo htmlspecialchars($supplier->contact_number); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No suppliers assigned to this route.</p>
            <?php endif; ?>
        </section>


        <?php if ($canAccessCollectionFeatures): ?>
            
            <section class="collection-status">
                <h2>Collection Status</h2>
                
                <?php if (empty($data['collectionBags'])): ?>
                    <!-- Initial state - Driver assigns bags -->
                    <div class="collection-stage bag-assignment">
                        <h3>Assign Collection Bags</h3>
                        <div class="bag-assignment-container">
                            <form action="<?php echo URLROOT; ?>/vehicledriver/assignBags" method="POST" class="bag-assignment-form">
                                <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($data['schedule']->schedule_id); ?>">
                                
                                <div class="form-group">
                                    <label for="bag-id">Add Bags for Collection</label>
                                    <div class="bag-input-container">
                                        <input type="text" 
                                            id="bag-id"
                                            class="bag-id-input" 
                                            placeholder="Enter bag ID..."
                                            pattern="[0-9]+"
                                            autocomplete="off">
                                        <button type="button" class="btn-primary add-bag-btn">Add Bag</button>
                                    </div>
                                </div>

                                <div class="assigned-bags-section">
                                    <h4>Assigned Bags</h4>
                                    <div class="assigned-bags-list">
                                        <p class="no-bags-message">No bags assigned yet</p>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-primary">Confirm Bag Assignment</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- After bags are assigned - Show approval status -->
                    <div class="collection-stage approval-status">
                        <h3>Collection Approval Status</h3>
                        
                        <?php 
                        // Debug the collection data
                        error_log('Collection data type: ' . gettype($data['collection']));
                        error_log('Collection data: ' . print_r($data['collection'], true));

                        // print_r($data['collection']);
                        
                        // Safe check for both existence and property
                        if (!isset($data['collection']) || 
                            !is_object($data['collection']) || 
                            !property_exists($data['collection'], 'vehicle_manager_approved') || 
                            !$data['collection']->vehicle_manager_approved): 
                        ?>
                            <div class="status-message warning">
                                <i class='bx bx-time'></i>
                                <p>Awaiting Vehicle Manager's Approval</p>
                            </div>
                            
                            <!-- Show assigned bags list -->
                            <div class="assigned-bags">
                                <h4>Assigned Bags</h4>
                                <ul class="bags-list">
                                    <?php foreach ($data['collectionBags'] as $bag): ?>
                                        <li>
                                            <span class="bag-id">Bag #<?php echo $bag->bag_id; ?></span>
                                            <span class="bag-capacity"><?php echo $bag->capacity_kg; ?> kg</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <!-- Show start collection button only after approval -->
                            <div class="status-message success">
                                <i class='bx bx-check-circle'></i>
                                <p>Approved by Vehicle Manager - Ready to Start Collection</p>
                            </div>
                            
                            <div class="collection-actions">
                                <a href="<?php echo URLROOT; ?>/vehicledriver/collection/<?php echo $data['collection']->collection_id; ?>" 
                                   class="btn-primary">Start Collection</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>

    </div>
</main>

<script>

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".bag-assignment-form");
    const input = document.querySelector(".bag-id-input");  // Changed from bag-token-input
    const addButton = document.querySelector(".add-bag-btn");
    const bagsList = document.querySelector(".assigned-bags-list");
    const assignedBags = new Set();

  // Add bag when button is clicked
  addButton.addEventListener("click", async function () {
    await addBag();
  });

  // Add bag when Enter is pressed
  input.addEventListener("keypress", async function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      await addBag();
    }
  });

  async function addBag() {
    const bagId = input.value.trim();
    if (!bagId) return;

    if (assignedBags.has(bagId)) {
      alert("This bag is already assigned");
      return;
    }

    try {
      // Check if bag exists and is available
      const response = await fetch(
        `${URLROOT}/vehicledriver/checkBag/${bagId}`
      );
      const data = await response.json();

      if (!data.success) {
        alert(data.message);
        return;
      }

      // Add bag to the list
      assignedBags.add(bagId);

      // Create bag element with capacity and weight info
      const bagElement = document.createElement("div");
      bagElement.className = "assigned-bag";
      bagElement.dataset.bagId = bagId;
      bagElement.innerHTML = `
              <div class="bag-info">
                  <span class="bag-id">Bag #${bagId}</span>
                  <span class="bag-capacity">Capacity: ${
                    data.data.capacity_kg
                  } kg</span>
                  <span class="bag-weight">Weight: ${
                    data.data.bag_weight_kg || 0
                  } kg</span>
              </div>
              <button type="button" class="remove-bag-btn">
                  <i class='bx bx-trash'></i>
              </button>
              <input type="hidden" name="bags[]" value="${bagId}">
          `;

      // Add remove functionality
      const removeButton = bagElement.querySelector(".remove-bag-btn");
      removeButton.addEventListener("click", function () {
        assignedBags.delete(bagId);
        bagElement.remove();

        // Show "No bags" message if list is empty
        if (assignedBags.size === 0) {
          bagsList.innerHTML =
            '<p class="no-bags-message">No bags assigned yet</p>';
        }
      });

      // Remove "No bags" message if it exists
      const noBagsMessage = bagsList.querySelector(".no-bags-message");
      if (noBagsMessage) {
        noBagsMessage.remove();
      }

      // Add bag to the list
      bagsList.appendChild(bagElement);

      // Clear input
      input.value = "";
      input.focus();
    } catch (error) {
      console.error("Error checking bag:", error);
      alert("Error checking bag. Please try again.");
    }
  }

  // Form submission
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (assignedBags.size === 0) {
      alert("Please assign at least one bag before submitting.");
      return;
    }

    // Submit form
    this.submit();
  });
});


</script>

<?php require APPROOT . '/views/inc/components/footer.php'; ?>





<?php require APPROOT . '/views/inc/components/footer.php'; ?> 