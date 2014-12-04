function initMap(ele, latlng) {
    if (latlng) {
        var center = latlng;
    } else {
        var center = [52.48626, -1.89042];
    }
    var map = new L.Map(ele, {
        center: center,
        zoom: 3
    }).addLayer(new L.TileLayer(
        'http://api.tiles.mapbox.com/v3/younginnovations.ijg2d43b/{z}/{x}/{y}.png', {
            attribution: "<a href='https://www.mapbox.com/about/maps/' target='_blank'>&copy; Mapbox &copy; OpenStreetMap</a> | " +
                "<a href='http://www.mapquest.com/' target='_blank' title='Nominatim Search Courtesy of Mapquest'>MapQuest</a>"
        }
    ));
    if (latlng) {
        L.marker(latlng).addTo(map);
    }

    map.on('click', function (e) {
        clearMarker();
        L.marker(e.latlng).addTo(map);
        populateValues(ele, e.latlng);
    });
    return map;
}

function clearMarker() {
    dojo.query('.leaflet-marker-pane')[0].innerHTML = '';
    dojo.query('.leaflet-shadow-pane')[0].innerHTML = '';
}

function populateValues(ele, latLong) {
    var id = dojo.attr(ele, 'id');
    dojo.attr(id + '-latitude', 'value', latLong.lat);
    dojo.attr(id + '-longitude', 'value', latLong.lng);
}