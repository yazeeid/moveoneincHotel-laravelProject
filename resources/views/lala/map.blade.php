<!DOCTYPE html>
<html>
<head>
    <title>Map</title>
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.8.2/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.8.2/mapbox-gl.js'></script>
</head>
<body>
    <h3>You can view detailed information by clicking on the markers for each country</h3>
    <div id="map"></div>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoieWF6ZWVkaGFtYWRhOTUiLCJhIjoiY2x6Z3hxa2NnMDVycDJtc2JpeXVmZXFxNiJ9.VWY3boU3fmaUslv_xWvy8w';
        var map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [0, 0],
            zoom: 2
        });

        var locations = @json($locations);
        var messages = @json($messages);
        function geocodeLocation(location, callback) {
            var url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(location)}.json?access_token=${mapboxgl.accessToken}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.features.length > 0) {
                        var lngLat = data.features[0].center;
                        callback(lngLat);
                    } else {
                        callback(null);
                    }
                })
                .catch(() => callback(null));
        }
        var geocodePromises = locations.map((location, index) => {
            return new Promise((resolve) => {
                geocodeLocation(location, function(lngLat) {
                    if (lngLat) {
                        var popupHtml = '<strong>Job Titles:</strong><br>' + messages[index].join('<br>');
                        new mapboxgl.Marker()
                            .setLngLat(lngLat)
                            .setPopup(new mapboxgl.Popup({ offset: 25 })
                                .setHTML(popupHtml))
                            .addTo(map);
                    }
                    resolve();
                });
            });
        });
        Promise.all(geocodePromises).then(() => {
            var bounds = new mapboxgl.LngLatBounds();
            locations.forEach((location, index) => {
                geocodeLocation(location, function(lngLat) {
                    if (lngLat) {
                        bounds.extend(lngLat);
                    }
                });
            });
            map.fitBounds(bounds, { padding: 20 });
        });
    </script>
</body>
</html>
