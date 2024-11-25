class CollectionTracker {
  constructor(urlRoot) {
    this.URLROOT = urlRoot;
    this.map = null;
    this.directionsService = null;
    this.directionsRenderer = null;
    this.markers = [];
  }

  init() {
    this.initMap();
    this.initEventListeners();
  }

  initMap() {
    this.map = new google.maps.Map(document.getElementById("map-container"), {
      center: { lat: 6.2173037, lng: 80.2564385 },
      zoom: 11,
    });

    this.directionsService = new google.maps.DirectionsService();
    this.directionsRenderer = new google.maps.DirectionsRenderer({
      map: this.map,
      suppressMarkers: true,
    });
  }

  initEventListeners() {
    const selectElement = document.getElementById("ongoing-collection-select");
    if (selectElement) {
      selectElement.addEventListener("change", () =>
        this.updateCollectionDetails(selectElement.value)
      );
      if (selectElement.options.length > 1) {
        selectElement.selectedIndex = 1;
        this.updateCollectionDetails(selectElement.value);
      }
    }
  }

  async updateMap(collectionId) {
    if (!collectionId) return;

    try {
      const response = await fetch(
        `${this.URLROOT}/vehiclemanager/getCollectionRoute/${collectionId}`
      );
      const data = await response.json();

      this.directionsRenderer.setDirections({ routes: [] });
      this.clearMarkers();

      const waypoints = data.suppliers.slice(1, -1).map((stop) => ({
        location: {
          lat: parseFloat(stop.latitude),
          lng: parseFloat(stop.longitude),
        },
        stopover: true,
      }));

      const request = {
        origin: {
          lat: parseFloat(data.start_location.latitude),
          lng: parseFloat(data.start_location.longitude),
        },
        destination: {
          lat: parseFloat(data.end_location.latitude),
          lng: parseFloat(data.end_location.longitude),
        },
        waypoints: waypoints,
        travelMode: "DRIVING",
      };

      this.directionsService.route(request, (result, status) => {
        if (status === "OK") {
          this.directionsRenderer.setDirections(result);
        }
      });
    } catch (error) {
      console.error("Error updating map:", error);
    }
  }

  async updateCollectionDetails(collectionId) {
    if (!collectionId) return;

    this.updateMap(collectionId);

    try {
      const response = await fetch(
        `${this.URLROOT}/vehiclemanager/getCollectionDetails/${collectionId}`
      );
      const data = await response.json();

      document.getElementById("team-name").textContent = data.team_name;
      document.getElementById("route-name").textContent = data.route_name;
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while fetching collection details");
    }
  }

  clearMarkers() {
    this.markers.forEach((marker) => marker.setMap(null));
    this.markers = [];
  }
}

class CollectionManager {
  constructor(urlRoot) {
    this.URLROOT = urlRoot;
  }

  async approveCollection(collectionId) {
    if (!confirm("Are you sure you want to approve this collection?")) return;

    try {
      const response = await fetch(
        `${this.URLROOT}/vehiclemanager/approveCollection/${collectionId}`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
        }
      );
      const data = await response.json();

      if (data.success) {
        alert("Collection approved successfully");
        location.reload();
      } else {
        alert(data.message || "Failed to approve collection");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while approving the collection");
    }
  }

  async rejectCollection(collectionId) {
    const reason = prompt("Please enter the reason for rejection:");
    if (!reason) return;

    try {
      const response = await fetch(
        `${this.URLROOT}/vehiclemanager/rejectCollection/${collectionId}`,
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ reason }),
        }
      );
      const data = await response.json();

      if (data.success) {
        alert("Collection rejected successfully");
        location.reload();
      } else {
        alert(data.message || "Failed to reject collection");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while rejecting the collection");
    }
  }
}

// Initialize on page load
window.addEventListener("DOMContentLoaded", () => {
  const tracker = new CollectionTracker(URLROOT);
  window.collectionManager = new CollectionManager(URLROOT);
  window.initMap = () => tracker.init();
});
