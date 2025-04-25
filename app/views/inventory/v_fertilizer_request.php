<?php require APPROOT . '/views/inc/components/header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/vehicle/vehicle.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/vehicle_manager/collection/collection.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/components/topnavbar_style.css" />
</head>

<!-- Top nav bar -->
<?php require APPROOT . '/views/inc/components/topnavbar.php' ?>
<!-- Side bar -->
<?php require APPROOT . '/views/inc/components/sidebar_inventory.php' ?>

<main>
    <div class="head-title">
        <div class="left">
            <h1>Factory Fertilizer Requests</h1>
            <ul class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Fertilizer</a></li>
            </ul>
        </div>
    </div>


    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Accepted Fertilizer Requests</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Fertilizer Name</th>
                        <th>Company Name</th>
                        <th>Quantity</th>
                        <th>Inventory Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['fertilizerRequest'])): ?>
                        <?php foreach ($data['fertilizerRequest'] as $fertilizer): ?>
                            <?php if($fertilizer->payment_status == 'Pending' || $fertilizer->payment_status == 'Approved'): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fertilizer->order_id); ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/manager/manageSupplier/<?php echo htmlspecialchars($fertilizer->supplier_id); ?>" class="manager-link">
                                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($fertilizer->image_path); ?>" alt="Supplier Photo" class="manager-photo">
                                        <?php echo htmlspecialchars($fertilizer->full_name); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($fertilizer->fertilizer_name); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->company_name); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->order_quantity); ?> kg</td>
                                <td><?php echo htmlspecialchars($fertilizer->order_date); ?></td>
                                <td>
                                    <span class="status-badge <?php 
                                        echo $fertilizer->payment_status == 'Pending' ? 'oranged' : 
                                            ($fertilizer->payment_status == 'Paid' ? 'added' : 'removed'); 
                                    ?>">
                                        <?php echo htmlspecialchars($fertilizer->payment_status); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($fertilizer->payment_status == 'Pending'): ?>
                                        <div style="display: flex; gap: 5px;">
                                            <!-- Approve button -->
                                            <a href="<?php echo URLROOT; ?>/Inventory/markRequestAsPaid/<?php echo $fertilizer->order_id; ?>" 
                                               class="btn btn-tertiary" 
                                               style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                               title="Approve">
                                                <i class='bx bx-check-circle' style="font-size: 24px; color:green;"></i>
                                            </a>
                                            
                                            <!-- Mark as Paid button -->
                                            <a href="<?php echo URLROOT; ?>/Inventory/markRequestAsFailed/<?php echo $fertilizer->order_id; ?>" 
                                               class="btn btn-tertiary" 
                                               style="display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border: none; background: none;"
                                               title="Mark as Paid">
                                                <i class='bx bx-x-circle' style="font-size: 24px; color:red;"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No fertilizers available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>




    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Completed Fertilizer Requests</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier</th>
                        <th>Fertilizer Name</th>
                        <th>Company Name</th>
                        <th>Quantity</th>
                        <th>Inventory Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['fertilizerRequest'])): ?>
                        <?php foreach ($data['fertilizerRequest'] as $fertilizer): ?>
                            <?php if($fertilizer->payment_status == 'Pending' || $fertilizer->payment_status == 'Approved'): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fertilizer->order_id); ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/manager/manageSupplier/<?php echo htmlspecialchars($fertilizer->supplier_id); ?>" class="manager-link">
                                        <img src="<?php echo URLROOT . '/' . htmlspecialchars($fertilizer->image_path); ?>" alt="Supplier Photo" class="manager-photo">
                                        <?php echo htmlspecialchars($fertilizer->full_name); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($fertilizer->fertilizer_name); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->company_name); ?></td>
                                <td><?php echo htmlspecialchars($fertilizer->order_quantity); ?> kg</td>
                                <td><?php echo htmlspecialchars($fertilizer->order_date); ?></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No fertilizers available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<style>

</style>

<script>
// Add filter inputs to the Completed Fertilizer Requests table - with debugging
document.addEventListener('DOMContentLoaded', function() {
  console.log("DOM fully loaded, running filter script");
  
  // Find all table headers to verify we're looking at the right one
  const allTableHeadings = document.querySelectorAll('.table-data .order .head h3');
  console.log("Found table headings:", allTableHeadings.length);
  allTableHeadings.forEach((heading, i) => {
    console.log(`Heading ${i}:`, heading.textContent);
  });
  
  // Find the Completed Fertilizer Requests table - using more specific selector
  const completedTableSection = document.querySelectorAll('.table-data')[1]; // Get the second table-data div
  console.log("Found second table section:", completedTableSection);
  
  if (!completedTableSection) {
    console.error("Could not find the second table section");
    return;
  }
  
  const completedTableHead = completedTableSection.querySelector('.head');
  console.log("Found table head:", completedTableHead);
  
  if (!completedTableHead) {
    console.error("Could not find the table head");
    return;
  }
  
  // Create filter container
  const filterContainer = document.createElement('div');
  filterContainer.className = 'filter-container';
  filterContainer.style.display = 'flex';
  filterContainer.style.gap = '15px';
  filterContainer.style.marginBottom = '15px';
  filterContainer.style.marginTop = '10px';
  
  // Month filter
  const monthFilterContainer = document.createElement('div');
  monthFilterContainer.className = 'filter-group';
  
  const monthLabel = document.createElement('label');
  monthLabel.textContent = 'Month: ';
  monthLabel.htmlFor = 'completed-month-filter';
  
  const monthSelect = document.createElement('select');
  monthSelect.id = 'completed-month-filter';
  monthSelect.className = 'filter-select';
  monthSelect.style.width = '150px'; // Force width
  
  const monthOptions = [
    { value: '', text: 'All Months' },
    { value: '01', text: 'January' },
    { value: '02', text: 'February' },
    { value: '03', text: 'March' },
    { value: '04', text: 'April' },
    { value: '05', text: 'May' },
    { value: '06', text: 'June' },
    { value: '07', text: 'July' },
    { value: '08', text: 'August' },
    { value: '09', text: 'September' },
    { value: '10', text: 'October' },
    { value: '11', text: 'November' },
    { value: '12', text: 'December' }
  ];
  
  monthOptions.forEach(option => {
    const optionElement = document.createElement('option');
    optionElement.value = option.value;
    optionElement.textContent = option.text;
    monthSelect.appendChild(optionElement);
  });
  
  monthFilterContainer.appendChild(monthLabel);
  monthFilterContainer.appendChild(monthSelect);
  
  // Fertilizer name filter
  const fertilizerFilterContainer = document.createElement('div');
  fertilizerFilterContainer.className = 'filter-group';
  
  const fertilizerLabel = document.createElement('label');
  fertilizerLabel.textContent = 'Fertilizer: ';
  fertilizerLabel.htmlFor = 'completed-fertilizer-filter';
  
  const fertilizerInput = document.createElement('input');
  fertilizerInput.type = 'text';
  fertilizerInput.id = 'completed-fertilizer-filter';
  fertilizerInput.className = 'filter-input';
  fertilizerInput.placeholder = 'Enter fertilizer name...';
  fertilizerInput.style.width = '200px'; // Force width
  
  fertilizerFilterContainer.appendChild(fertilizerLabel);
  fertilizerFilterContainer.appendChild(fertilizerInput);
  
  // Clear filters button
  const clearButton = document.createElement('button');
  clearButton.textContent = 'Clear Filters';
  clearButton.className = 'clear-filter-btn';
  clearButton.style.marginLeft = 'auto';
  clearButton.style.padding = '5px 10px';
  clearButton.style.backgroundColor = '#f0f0f0';
  clearButton.style.border = '1px solid #ddd';
  clearButton.style.borderRadius = '4px';
  clearButton.style.cursor = 'pointer';
  
  // Add all filter elements to the container
  filterContainer.appendChild(monthFilterContainer);
  filterContainer.appendChild(fertilizerFilterContainer);
  filterContainer.appendChild(clearButton);
  
  // Add the filter container to the table header with explicit visibility
  filterContainer.style.display = 'flex'; // Ensure it's visible
  filterContainer.style.backgroundColor = '#f9f9f9'; // Add background to see if it's there
  filterContainer.style.padding = '10px'; // Add padding to make it more visible
  filterContainer.style.border = '1px solid #eee'; // Add border to see boundaries
  
  console.log("Inserting filter container into table head");
  completedTableHead.appendChild(filterContainer);
  
  // Get the table body for the Completed Fertilizer Requests table
  const tableBody = completedTableSection.querySelector('table tbody');
  console.log("Found table body:", tableBody);
  
  if (!tableBody) {
    console.error("Could not find the table body");
    return;
  }
  
  // Filter function
  function applyFilters() {
    console.log("Applying filters");
    const monthValue = monthSelect.value;
    const fertilizerValue = fertilizerInput.value.toLowerCase();
    
    console.log("Filter values:", { month: monthValue, fertilizer: fertilizerValue });
    
    // Get all rows in the table body
    const tableRows = tableBody.querySelectorAll('tr');
    console.log("Found rows to filter:", tableRows.length);
    
    let visibleRows = 0;
    
    tableRows.forEach(row => {
      // Skip rows that are just "No fertilizers available" messages
      if (row.cells.length === 1) {
        console.log("Skipping message row");
        return;
      }
      
      try {
        // Get the date value from the row (6th column)
        const dateCell = row.querySelector('td:nth-child(6)').textContent;
        // Get the fertilizer name from the row (3rd column)
        const fertilizerCell = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        console.log("Row data:", { date: dateCell, fertilizer: fertilizerCell });
        
        // Extract month from date (assuming format YYYY-MM-DD or similar)
        const dateMonth = dateCell.split('-')[1] || '';
        console.log("Extracted month:", dateMonth);
        
        // Check if row matches both filters
        const matchesMonth = monthValue === '' || dateMonth === monthValue;
        const matchesFertilizer = fertilizerValue === '' || fertilizerCell.includes(fertilizerValue);
        
        console.log("Filter matches:", { matchesMonth, matchesFertilizer });
        
        // Show or hide row based on filter match
        if (matchesMonth && matchesFertilizer) {
          row.style.display = '';
          visibleRows++;
        } else {
          row.style.display = 'none';
        }
      } catch (err) {
        console.error("Error filtering row:", err);
      }
    });
    
    console.log("Visible rows after filtering:", visibleRows);
    
    // If no rows are visible, show a "no results" message
    let noResultsRow = tableBody.querySelector('.no-results-row');
    
    if (visibleRows === 0) {
      if (!noResultsRow) {
        noResultsRow = document.createElement('tr');
        noResultsRow.className = 'no-results-row';
        const noResultsCell = document.createElement('td');
        noResultsCell.colSpan = 6; // Span all columns
        noResultsCell.textContent = 'No matching fertilizer requests found.';
        noResultsCell.style.textAlign = 'center';
        noResultsRow.appendChild(noResultsCell);
        tableBody.appendChild(noResultsRow);
        console.log("Added no results row");
      } else {
        noResultsRow.style.display = '';
      }
    } else if (noResultsRow) {
      noResultsRow.style.display = 'none';
    }
  }
  
  // Add event listeners
  console.log("Adding event listeners to filters");
  monthSelect.addEventListener('change', applyFilters);
  fertilizerInput.addEventListener('input', applyFilters);
  
  // Clear filters functionality
  clearButton.addEventListener('click', function() {
    console.log("Clearing filters");
    monthSelect.value = '';
    fertilizerInput.value = '';
    applyFilters();
  });
  
  // Add some basic styling with !important to override any conflicts
  const style = document.createElement('style');
  style.textContent = `
    .filter-container {
      display: flex !important;
      gap: 15px !important;
      margin-bottom: 15px !important;
      margin-top: 10px !important;
      background-color: #f9f9f9 !important;
      padding: 10px !important;
      border-radius: 4px !important;
      z-index: 1000 !important;
    }
    .filter-group {
      display: flex !important;
      align-items: center !important;
      gap: 5px !important;
    }
    .filter-select, .filter-input {
      padding: 5px !important;
      border: 1px solid #ddd !important;
      border-radius: 4px !important;
      min-width: 150px !important;
      display: inline-block !important;
      visibility: visible !important;
      opacity: 1 !important;
    }
    .filter-input {
      min-width: 200px !important;
    }
    .clear-filter-btn {
      padding: 5px 10px !important;
      background-color: #f0f0f0 !important;
      border: 1px solid #ddd !important;
      border-radius: 4px !important;
      cursor: pointer !important;
      display: inline-block !important;
      visibility: visible !important;
      opacity: 1 !important;
    }
    .clear-filter-btn:hover {
      background-color: #e0e0e0 !important;
    }
  `;
  document.head.appendChild(style);
  
  console.log("Filter setup complete");
});
</script>

<?php require APPROOT . '/views/inc/components/footer.php' ?>