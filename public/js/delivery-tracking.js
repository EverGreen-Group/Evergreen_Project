var map = infoWindow = latitude = null;
var markersArray = [];

$(document).ready(function() {
    let delivery_status = $('#delivery_status').val();
    
    if(delivery_status != 'ongoing') {
        if(delivery_status == 'pending') {
            $('#msg').html('Delivery is yet to start, tracking not available at this moment..');
        } else if(delivery_status == 'completed') {
            $('#msg').html('Delivery completed, live tracking not available..');
        } else {
            $('#msg').html('Tracking not available at this moment..');
        }
        $('#myModal').modal('show');
    } else {
        initMap();
        livetracking($('#tracking_code').val());
        window.setInterval(function() {
            livetracking($('#tracking_code').val());
        }, 15000);
    }
});

function initMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.cookie = "latitude=" + position.coords.latitude;
            document.cookie = "longitude=" + position.coords.longitude;
        });
    }
    
    map = new google.maps.Map(document.getElementById("map_canvas"), {
        center: new google.maps.LatLng(52.696361078274485, -111.4453125),
        zoom: 3,
        mapTypeId: 'roadmap',
        gestureHandling: 'greedy'
    });
    
    infoWindow = new google.maps.InfoWindow;
}

function livetracking(tracking_code) {
    if(!tracking_code) return;
    
    var path = $('#base').val() + "/api/currentposition/" + tracking_code;
    
    $.ajax({
        type: "GET",
        url: path,
        dataType: 'json',
        cache: false,
        success: function(result) {
            if(result.status == 1) {
                var markers = result.data;
                resetMarkers(markersArray);
                
                for (i = 0; i < markers.length; i++) {
                    var lastupdate = markers[i].time;
                    var v_type = getVehicleIcon(markers[i].v_type);
                    
                    var point = new google.maps.LatLng(
                        parseFloat(markers[i].latitude), 
                        parseFloat(markers[i].longitude)
                    );
                    
                    var html = "<div><b>Order ID: </b>" + markers[i].order_id + 
                              "<br><b>Status: </b>" + markers[i].status +
                              "<br><b>Updated: </b>" + lastupdate + "</div>";
                              
                    var marker = new google.maps.Marker({
                        map: map,
                        position: point,
                        icon: {
                            path: v_type,
                            scale: 0.4,
                            strokeWeight: 0.2,
                            strokeColor: 'black',
                            strokeOpacity: 2,
                            fillColor: markers[i].color,
                            fillOpacity: 1.5,
                        }
                    });
                    
                    markersArray.push(marker);
                    bindInfoWindow(marker, map, infoWindow, html);
                }
            }
        }
    });
}

function getVehicleIcon(type) {
    switch(type) {
        case 'MOTORCYCLE': return fontawesome.markers.MOTORCYCLE;
        case 'BICYCLE': return fontawesome.markers.BICYCLE;
        case 'CAR': return fontawesome.markers.CAR;
        case 'TRUCK': return fontawesome.markers.TRUCK;
        case 'BUS': return fontawesome.markers.BUS;
        case 'TAXI': return fontawesome.markers.TAXI;
        default: return fontawesome.markers.TRUCK;
    }
}

function resetMarkers(arr) {
    for (var i = 0; i < arr.length; i++) {
        arr[i].setMap(null);
    }
    arr = [];
}

function bindInfoWindow(marker, map, infoWindow, html) {
    google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
    });
}