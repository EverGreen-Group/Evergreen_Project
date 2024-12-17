 <!-- Route Creation Modal -->
 <div id="routeModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modalTitle">Route Details</h2>
      
      <form id="routeForm">
        <input type="hidden" id="routeId" name="routeId">

        <label for="routeName">Route Name:</label>
        <input type="text" id="routeName" name="routeName" required>

        <label for="daySelect">Day:</label>
        <select id="daySelect" name="day">
            <option value="" disabled selected>Select a day</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>

        <label for="supplierSelect">Select Supplier:</label>
        <select id="supplierSelect">
          <option value="" disabled selected>Select a supplier</option>
        </select>
        <button type="button" id="addSupplierButton">Add Supplier Stop</button>



        <h3>Route Stops:</h3>
        <ul id="stopList"></ul>

        <div id="map" style="width: 100%; height: 400px;"></div>

        <button type="submit" class="submit-btn">Save Route</button>
      </form>
    </div>
</div>


  <!-- Route Edit Modal -->
  <div id="editRouteModal" class="modal">
    <div class="modal-content">
        <span class="close-edit">&times;</span>
        <h2>Edit Route</h2>
        
        <form id="editRouteForm">
            <input type="hidden" id="editRouteId" name="editRouteId">

            <label for="editRouteName">Route Name:</label>
            <input type="text" id="editRouteName" name="editRouteName" required>

            <label for="editStatus">Status:</label>
            <select id="editStatus" name="editStatus" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>

            <label for="editSupplierSelect">Select Supplier:</label>
            <select id="editSupplierSelect">
                <option value="" disabled selected>Select a supplier</option>
            </select>
            <button type="button" id="editAddSupplierButton">Add Supplier Stop</button>

            <h3>Route Stops:</h3>
            <ul id="editStopList"></ul>

            <div id="editMap" style="width: 100%; height: 400px;"></div>

            <button type="submit" class="submit-btn">Update Route</button>
        </form>
    </div>
  </div>
