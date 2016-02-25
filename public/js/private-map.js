$(function()
{

    /**
     * Leaflet
     */

    if(window.Mapbox.token && window.Mapbox.project) {

        // create map instance
        window.Mapbox.markers = [];
        window.Mapbox.map = L.map('map').setView(window.Mapbox.coord, 10);

        // create tile
        var tile = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: '',
            maxZoom: 18,
            id: window.Mapbox.project,
            accessToken: window.Mapbox.token
        });
        tile.addTo(window.Mapbox.map);

        // add markers
        $.each(window.Mapbox.locations, function (index, location)
        {
            if(location.place) {

                // create marker
                var marker = L.marker(location.place).addTo(window.Mapbox.map);
                window.Mapbox.markers.push(marker);

                // create popup
                marker.bindPopup(
                    '<a onclick="window.isLeaving = true" href="' + location.link + '">' +
                        '<img src="' + location.pic + '"/>' + location.name + ', ' + location.date +
                    '</a>'
                );
            }
        });

        // (un)zoom to see all markers
        var group = new L.featureGroup(window.Mapbox.markers);
        window.Mapbox.map.fitBounds(group.getBounds());
    }

});