// @author: PoofyPloop

// Variables
let map;
let geocoder;
let latLng = { lat: 45, lng: -80 };
let directionsService;
let directionsRenderer;
let infoWindow;
let markers = [];
let selectedFilter = "All";
let user;

// Initializing the map
function initMap() {
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    geocoder = new google.maps.Geocoder();
    infoWindow = new google.maps.InfoWindow();
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 10,
        center: latLng
    });

    google.maps.event.addListener(map, 'bounds_changed', function(event) {
        console.log('Bounds changed!');
    });

    setMarkers();
    setEvents();
    getUserLocation();
}

function setEvents() {
    $(document).on('click', '#infowindowButton', function(event) {
        //  Get the latitude and longiture from the data
        let location = { lat: $(event.target).data('lat'), lng: $(event.target).data('lng') };

        //  Get the directions to the selected location
        getDirections(location);

        //  Prevents form submitions 
        event.preventDefault();
        return false;
    });

    //  Clears & closes directions panel
    $(document).on('click', '#clearButton, #directionsCloseButton', function(event) {
        clearDirections();

        //  Prevents form submitions 
        event.preventDefault();
        return false;
    });

    //  User clicks the directions button
    $(document).on('click', '#directionsButton', function(event) {
        let locationAddress = $('#adressDirections').val();

        getDirections(locationAddress);

        geocoder.geocode({ address: locationAddress }, (results, status) => {
            if (status === "OK") {
                map.setCenter(results[0].geometry.location);
                console.log(results[0].geometry.location);
            } else {
                console.log("Geocode was not successful for the following reason: " + status);
            }
        });

        //  Prevents form submitions 
        event.preventDefault();
        return false;
    });

    //  Select a filter
    $('#mapFilters select').on('change', function(event) {
        selectedFilter = event.target.value;

        clearDirections();
        setMarkers();
    });
}

function getUserLocation() {
    if(navigator.geolocation) {
        //  Use the browser's Geolocation API to get the user's location 
        navigator.geolocation.getCurrentPosition(
            //  if getting the user's location was a success
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                //  Set the user's location on the map
                user = pos;
                infoWindow.setPosition(pos);
                infoWindow.setContent(`
                    <span class="font-weight-bold">You're here</span>
                    <br><br>Latitude: <span class="font-italic">${pos.lat}</span>
                    <br>Longitude: <span class="font-italic">${pos.lng}</span>
                `);
                infoWindow.open(map);

                //  Center map to user's location
                map.setCenter(pos);
            },
            //  If it fails to get user location
            () => {
                handleLocationError(true, infoWindow, map.getCenter());
            }
        );
    } else {
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function getDirections(destination) {
    //  Removes all markers
    clearMarkers();

    //  Set the directions renderer to use the map and panel
    directionsRenderer.setMap(map);
    directionsRenderer.setPanel(document.getElementById('mapContent'));

    //  Creates request for directions
    let req = {
        origin: user,
        destination: destination,
        travelMode: 'DRIVING'
    };

    directionsService.route(req, function(response, status) {
        if (status == 'OK') {
            directionsRenderer.setDirections(response);
            // Open the directions panel
            $('body').addClass('openDirectionsPanel');
        }
    });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    //  Handle errors with location
    let errorMessage = browserHasGeolocation ? "Error: Unable to obtain user location. Geolocation service failed." : "Error: Geolocation isn't supported by this browser. Please try something else.'.";
    infoWindow.setPosition(pos);
    infoWindow.setContent(errorMessage);
    infoWindow.open(map);

    $('#geoMessage .modal-body p').text(errorMessage);
    $('#geoMessage').modal('show');
}

function setMarkers() {
    let marker;
    let restingPlaces;

    clearMarkers();

    if(selectedFilter !== "All") {
        //  Filters by selected category if current isnt "All"
        restingPlaces = tourism.filter((val, idx, array) => {
            return val.CATEGORY == selectedFilter;
        });
    } else {
        //  Otherwise, show everything
        restingPlaces = tourism;
    }

    //  Loop through each institution and add the marker for it
    restingPlaces.forEach((val, idx, array) => {
        let latLng = { lat: val.LATITUDE, lng: val.LONGITUDE };
        let userLocation = `
            <h6><a href="${val.WEBSITE_URL}" target="_blank">${val.NAME}</a></h6>
            <p>${val.CATEGORY}<br>${val.ADDRESS}, ${val.COMMUNITY}</p>
            <button class="btn btn-sm" id="infowindowButton" data-lat="${val.LATITUDE}" data-lng="${val.LONGITUDE}">Directions</button>
        `;
        let noLocation = `
            <h6>${val.NAME}</h6>
            <p>...</p>
        `;

        marker = new google.maps.Marker({
            position: latLng,
            map: map,
            icon: `icons/${val.CATEGORY}.png`,
            //icon: `${val.CATEGORY}.png`,
            title: val.NAME,
        });

        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(user ? userLocation : noLocation);
            infoWindow.open(map, this);
        });

        markers.push(marker);
    });

    centerMap();
}

function centerMap() {
    let bounds = new google.maps.LatLngBounds();

    markers.forEach((marker) => {
        let markerLatLng = { lat: marker.getPosition().lat(), lng: marker.getPosition().lng() };
        bounds.extend(markerLatLng);
    });

    map.fitBounds(bounds);
    map.setCenter(bounds.getCenter());
}

function clearMarkers() {
    markers.forEach((marker, idx, array) => {
        marker.setMap(null);
    });
}

function clearDirections() {
    $('#mapContent').html('');
    $('#adressDirections').val('');

    //  Close panel
    $('body').removeClass('openDirectionsPanel');

    directionsRenderer.setMap(null);
    directionsRenderer.setPanel(null);

    setMarkers();
}