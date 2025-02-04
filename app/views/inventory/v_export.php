<?php require APPROOT . '/views/inc/components/header.php'; ?>

<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>
<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection_bags/styles.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo URLROOT; ?>/public/js/collection_bags.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<script>
  const URLROOT = '<?php echo URLROOT; ?>';
  const UPLOADROOT = '<?php echo UPLOADROOT; ?>';
</script>

<!-- MAIN -->
<main>
  <!-- Vehicle Management Section -->
  <div class="head-title">
    <div class="left">
      <h1>Tea Leaf Exports</h1>
      <ul class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
      </ul>
    </div>
  </div>

  <div class="action-buttons">
    <button class="btn btn-primary" onclick="showaddExportRecord()">
      <i class='bx bx-plus'></i>
      Add Export Record
    </button>
  </div>

  <ul class="box-info">
    <li>
      <i class='bx bxs-shopping-bag'></i>
      <span class="text">
        <p>Total Exports</p>
        <h3><?php echo isset($data['totalVehicles']) ? $data['totalVehicles'] : '0'; ?></h3>
      </span>
    </li>
    <li>
      <i class='bx bxs-user'></i>
      <span class="text">
        <p>Total Exports For Last Month</p>
        <h3><?php echo isset($data['a']) ? $data['a'] : '0'; ?></h3>
      </span>
    </li>
    <li>
      <i class='bx bxs-user'></i>
      <span class="text">
        <p>Ready to Export</p>
        <h3><?php echo isset($data['b']) ? $data['b'] : '0'; ?></h3>
      </span>
    </li>
  </ul>


  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Stock Exports(Last Month)</h3>
      </div>
      <table id="stockExportsTable">
        <thead>
          <tr>
            <th>Tea Type</th>
            <th>Quantity Exported (kg)</th>
            <th>Revenue Earned ($)</th>
            <th>Last Updated</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Black Tea</td>
            <td>2000 kg</td>
            <td>Rs.8000</td>
            <td>2023-10-10</td>
            <td>
              <button class="btn btn-secondary" onclick="viewExportModal()">Details</button>
            </td>
          </tr>
          <tr>
            <td>Green Tea</td>
            <td>1500 kg</td>
            <td>Rs.6000</td>
            <td>2023-10-12</td>
            <td>
              <button class="btn btn-secondary" onclick="viewExportModal()">Details</button>
            </td>
          </tr>
          <tr>
            <td>Herbal Tea</td>
            <td>1000 kg</td>
            <td>Rs.4000</td>
            <td>2023-10-15</td>
            <td>
              <button class="btn btn-secondary" onclick="viewExportModal('')">Details</button>
            </td>
          </tr>
          <tr>
            <td>Oolong Tea</td>
            <td>500 kg</td>
            <td>Rs.2000</td>
            <td>2023-10-18</td>
            <td>
              <button class="btn btn-secondary" onclick="viewExportModal('')">Details</button>
            </td>
          </tr>
        </tbody>

        <tfoot>
          <tr>
            <td><strong>Total</strong></td>
            <td><strong>4000 kg</strong></td>
            <td><strong>$20000</strong></td>
            <td></td>
            <td></td>
          </tr>
        </tfoot>
      </table>

    </div>

    <!-- Driver Status Chart -->
    <div class="order">
      <div class="head">
        <h3>Tea Leaf Exports</h3>
        <i class='bx bx-shopping-bag'></i>
      </div>
      <div>
        <canvas id="reportTypesChart"></canvas>
      </div>
    </div>
  </div>

  <style>
    .chart-container-wrapper {
      position: relative;
      width: 100%;
      height: 500px;
      /* Fixed height */
      padding: 20px;
      background: white;
      border-radius: 8px;
      margin: 20px 0;
    }

    #reportTypesChart {
      width: 100% !important;
      height: 100% !important;
    }

    /* Ensure the parent container has proper dimensions */
    .order {
      min-height: 400px;
    }
  </style>


  <div id="viewExportModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('viewExportModal')">&times;</span>
      <h2>Export Details for Black Tea</h2>
      <div class="stock-modal-content">
        <div class="stock-modal-details">
          <form id="blackTeaExportForm">
            <!-- Total Exports and Revenue -->
            <div class="detail-group">
              <h3>Export Summary</h3>
              <table>
                <thead>
                  <tr>
                    <th>Grade</th>
                    <th>Quantity Exported (kg)</th>
                    <th>Revenue Earned ($)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>BOPF</td>
                    <td>3000 kg</td>
                    <td>Rs.12000</td> <!-- Example revenue -->
                  </tr>
                  <tr>
                    <td>FBOP</td>
                    <td>1500 kg</td>
                    <td>Rs.6000</td> <!-- Example revenue -->
                  </tr>
                  <tr>
                    <td>Dust</td>
                    <td>500 kg</td>
                    <td>Rs.2000</td> <!-- Example revenue -->
                  </tr>
                  <tr>
                    <td>OP (Orange Pekoe)</td>
                    <td>800 kg</td>
                    <td>Rs.3200</td> <!-- Example revenue -->
                  </tr>
                  <tr>
                    <td>FOP (Flowery Orange Pekoe)</td>
                    <td>700 kg</td>
                    <td>Rs.2800</td> <!-- Example revenue -->
                  </tr>
                  <tr>
                    <td>GFOP (Golden Flowery Orange Pekoe)</td>
                    <td>600 kg</td>
                    <td>Rs.2400</td> <!-- Example revenue -->
                  </tr>
                  <tr>
                    <td>BOP (Broken Orange Pekoe)</td>
                    <td>1200 kg</td>
                    <td>Rs.4800</td> <!-- Example revenue -->
                  </tr>
                  <tr>
                    <td>PD (Pekoe Dust)</td>
                    <td>400 kg</td>
                    <td>Rs.1600</td> <!-- Example revenue -->
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>7000 kg</strong></td> <!-- Total quantity exported -->
                    <td><strong>$28800</strong></td> <!-- Total revenue -->
                  </tr>
                </tfoot>
              </table>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="table-data">
    <div class="order">
      <div class="head">
        <h3>Export Logs</h3>
      </div>
      <table id="exportLogsTable">
        <thead>
          <tr>
            <th>Inventory Manager</th>
            <th>Stock Type</th>
            <th>Reg-No</th>
            <th>Quantity</th>
            <th>Price per kg ($)</th>
            <th>Company</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($data['exports'] as $export): ?>
            <tr>
              <td><?php echo $export->manager_name; ?></td>
              <td><?php echo $export->stock_name; ?></td>
              <td><?php echo $export->reg_no; ?></td>
              <td><?php echo $export->export_quantity; ?> kg</td>
              <td>Rs.<?php echo $export->export_price; ?></td>
              <td><?php echo $export->export_company; ?></td>
              <td><?php echo $export->export_date; ?></td>
            </tr>
          <?php endforeach; ?>
          <!-- <tr>
                    <td>John Doe</td>
                    <td>Black Tea</td>
                    <td>BOPF</td>
                    <td>1000 kg</td>
                    <td>Rs.120.00</td>
                    <td>Tea Co.</td>
                    <td>2023-10-05</td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>Green Tea</td>
                    <td>FBOP</td>
                    <td>500 kg</td>
                    <td>Rs.120.00</td>
                    <td>Green Leaf Ltd.</td>
                    <td>2023-10-06</td>
                </tr>
                <tr>
                    <td>John Doe</td>
                    <td>Herbal Tea</td>
                    <td>Chamomile</td>
                    <td>300 kg</td>
                    <td>Rs.130.00</td>
                    <td>Herbal Solutions</td>
                    <td>2023-10-07</td>
                </tr>
                <tr>
                    <td>Jane Smith</td>
                    <td>Oolong Tea</td>
                    <td>Tieguanyin</td>
                    <td>200 kg</td>
                    <td>Rs.100.00</td>
                    <td>Oolong Traders</td>
                    <td>2023-10-08</td>
                </tr> -->
        </tbody>
      </table>
    </div>
  </div>


  <div id="addExportModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('addExportModal')">&times;</span>
      <h2>Add Export Record</h2>
      <div class="stock-modal-content">
        <div class="vehicle-modal-content">
          <div class="vehicle-modal-details">
            <div class="detail-group">
              <h3>Export Information</h3>
              <div class="detail-row">
                <span class="label">Reg-No:</span>
                <span class="value">
                  <input type="text" id="exportRegNo" name="reg-no" required
                    style="width: 100%; padding: 8px; box-sizing: border-box;">
                </span>
              </div>
              <div class="detail-row">
                <span class="label">Manager Name:</span>
                <span class="value">
                  <input type="text" id="exportmanager" name="manager" required
                    style="width: 100%; padding: 8px; box-sizing: border-box;">
                </span>
              </div>
              <div class="detail-row">
                <span class="label">Tea Type:</span>
                <span class="value">
                  <input type="text" id="Stockname" name="stock-name" required
                    style="width: 100%; padding: 8px; box-sizing: border-box;">
                </span>
              </div>

              <div class="detail-row">
                <span class="label">Quantity (kg):</span>
                <span class="value">
                  <input type="number" id="exportQuantity" name="quantity" required
                    style="width: 100%; padding: 8px; box-sizing: border-box;">
                </span>
              </div>

              <div class="detail-row">
                <span class="label">Price per kg ($):</span>
                <span class="value">
                  <input type="number" id="exportPrice" name="price" required
                    style="width: 100%; padding: 8px; box-sizing: border-box;" step="0.01">
                </span>
              </div>
              <div class="detail-row">
                <span class="label">Company:</span>
                <span class="value">
                  <input type="text" id="exportCompany" name="company-name" required
                    style="width: 100%; padding: 8px; box-sizing: border-box;">
                </span>
              </div>

              <div class="detail-row">
                <span class="label">Notes:</span>
                <span class="value">
                  <textarea id="exportNotes" name="notes" rows="3"
                    style="width: 100%; padding: 8px; box-sizing: border-box;"></textarea>
                </span>
              </div>
            </div>
          </div>
          <div style="text-align: center; margin-top: 20px;">
            <button type="submit" class="btn btn-primary full-width" onclick="addExportRecord(event)">ADD EXPORT
              RECORD</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>

    function addExportRecord(event) {
      const StockType = document.getElementById("Stockname").value;
      const Quantity = document.getElementById("exportQuantity").value;
      const price = document.getElementById("exportPrice").value;
      const company = document.getElementById("exportCompany").value;
      const notes = document.getElementById("exportNotes").value;
      const RegNo = document.getElementById("exportRegNo").value;
      const Manager = document.getElementById("exportmanager").value;

      const data = {
        StockType,
        Quantity,
        price,
        company,
        notes,
        RegNo,
        Manager
      };

      document.getElementById("addExportModal").style.display = "none";

      const url = `${URLROOT}/export/release`; 
      console.log(data);

      // Send the data to the server
      fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      })
    }

    function updateGradingOptions() {
      const teaType = document.getElementById('teaType').value;
      const gradingSelect = document.getElementById('grading');

      // Clear existing options
      gradingSelect.innerHTML = '<option value="">Select a Grading</option>';

      // Define grading options based on tea type
      let gradingOptions = [];
      if (teaType === 'Black Tea') {
        gradingOptions = ['BOPF', 'FBOP', 'Dust', 'OP (Orange Pekoe)', 'FOP (Flowery Orange Pekoe)'];
      } else if (teaType === 'Green Tea') {
        gradingOptions = ['Sencha', 'Matcha', 'Gyokuro'];
      } else if (teaType === 'Herbal Tea') {
        gradingOptions = ['Chamomile', 'Peppermint', 'Rooibos'];
      } else if (teaType === 'Oolong Tea') {
        gradingOptions = ['Tieguanyin', 'Da Hong Pao'];
      }

      // Populate grading select options
      gradingOptions.forEach(function (grading) {
        const option = document.createElement('option');
        option.value = grading;
        option.textContent = grading;
        gradingSelect.appendChild(option);
      });
    }

    function showExportDetails(teaType) {
      // Placeholder for modal implementation
      alert('Showing details for: ' + teaType);
      // Here you would implement the logic to open a modal and display the relevant information
    }

    function showaddExportRecord() {
      // Show the modal
      document.getElementById("addExportModal").style.display = "block";
    }

    document.addEventListener("DOMContentLoaded", function () {
  const reportCtx = document.getElementById("reportTypesChart");

  if (reportCtx) {
    // Clear any previous chart instances
    Chart.helpers.each(Chart.instances, function (instance) {
      instance.destroy();
    });

    new Chart(reportCtx, {
      type: "line",
      data: {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ],
        datasets: [
          {
            label: "Black Tea",
            data: [
              5000, 480, 500, 5300, 5500, 600, 6200, 6100, 6300, 6400, 6500,
              6700,
            ],
            backgroundColor: "rgba(54, 162, 235, 0.2)",
            borderColor: "#36A2EB",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
          {
            label: "Green Tea",
            data: [
              3000, 3200, 3100, 3300, 3400, 3500, 3600, 3700, 3800, 3900, 4000,
              4100,
            ],
            backgroundColor: "rgba(75, 192, 192, 0.2)",
            borderColor: "#4BC0C0",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
          {
            label: "Herbal Tea",
            data: [
              2000, 2100, 2200, 2500, 2400, 2300, 2600, 2700, 2800, 2900, 3000,
              3100,
            ],
            backgroundColor: "rgba(255, 206, 86, 0.2)",
            borderColor: "#FFCE56",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
          {
            label: "Oolong Tea",
            data: [
              1500, 1600, 1700, 1800, 1900, 2000, 2100, 2200, 2300, 2400, 2500,
              2600,
            ],
            backgroundColor: "rgba(153, 102, 255, 0.2)",
            borderColor: "#9966FF",
            borderWidth: 2,
            fill: false,
            tension: 0.4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "top",
            labels: {
              padding: 20,
              font: {
                size: 12,
              },
            },
          },
          title: {
            display: true,
            text: "Monthly Tea Leaf Stock",
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: "rgba(0, 0, 0, 0.1)",
            },
            ticks: {
              callback: function (value) {
                return value + " kg";
              },
            },
            title: {
              display: true,
              text: "Stock (kg)",
            },
          },
          x: {
            grid: {
              display: false,
            },
            title: {
              display: true,
              text: "Months",
            },
          },
        },
      },
    });
  }
});
  </script>

</main>


<?php require APPROOT . '/views/inc/components/footer.php'; ?>