$(function()
{

    /**
     * Leaflet
     */

    if(window.Mapbox.token) {

        // create map instance
        window.Mapbox.markers = [];
        window.Mapbox.map = L.map('map').setView(window.Mapbox.coord, 10);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: '',
            maxZoom: 18,
            id: window.Mapbox.project,
            accessToken: window.Mapbox.token
        }).addTo(window.Mapbox.map);

        // add markers
        $.each(window.Mapbox.locations, function (index, location) {
            var query = encodeURIComponent(location.place);
            var url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' + query + '.json?access_token=';
            $.get(url + window.Mapbox.token).success(function(json) {
                if(json.features) {
                    var place = json.features[0];
                    var marker = L.marker(place.center.reverse());
                    marker.bindPopup('<a href="' + location.link + '">' + location.name + ', <em>' + location.date + '</em></a>');
                    marker.addTo(window.Mapbox.map);
                    window.Mapbox.markers.push(marker);
                }
            });
        });

    }

});