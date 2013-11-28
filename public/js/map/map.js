function initMap(ele , latlng){
     if(latlng){
          var center = latlng;
     } else {
          var center = [52.48626, -1.89042];
     }
     var map = new L.Map(ele, {
          center: center,
          zoom: 3
      }).addLayer(new L.TileLayer(
          'http://{s}.tile.osm.org/{z}/{x}/{y}.png',
          {
               attribution: '&copy; <a href="http://osm.org/copyright" title="Map powered by OSM">OSM</a> | ' +
               '<a href="http://www.mapquest.com/" target="_blank" title="Nominatim Search Courtesy of Mapquest">MapQuest</a>'
          }
     ));
     if(latlng){
          L.marker(latlng).addTo(map);
     }

     map.on('click', function(e){
          clearMarker();
          L.marker(e.latlng).addTo(map);
          populateValues(ele , e.latlng);
     });
     return map;
}

function clearMarker(){
     dojo.query('.leaflet-marker-pane')[0].innerHTML = '';
     dojo.query('.leaflet-shadow-pane')[0].innerHTML = '';
}

function populateValues(ele , latLong){
     var id = dojo.attr(ele , 'id');
     dojo.attr(id + '-Coordinates-latitude', 'value' ,  latLong.lat);
     dojo.attr(id + '-Coordinates-longitude' , 'value' , latLong.lng);
     
     populateAdmin( ele , latLong);

}

function populateAdmin(ele , latLong){
     var id = dojo.attr(ele , 'id');
     dojo.xhrGet({
          url : "http://open.mapquestapi.com/nominatim/v1/reverse.php",
          handleAs : "json",
          content : {
               lat : latLong.lat ,
               lon : latLong.lng ,
               zoom : 12 ,
               format : 'json' ,
               lang : 'en'
          },
          load : function (data) {
               var adm1 = '';
               var adm2 = '';
               var levels = ['state' , 'county' , 'city'];
               
               for(var i=0 ; i<levels.length ; i++){
                    if(data.address[levels[i]]){
                         if(!adm1){
                              adm1 = data.address[levels[i]];
                         } else {
                              adm2 = data.address[levels[i]];
                         }
                         if(adm1 && adm2) break;
                    }
               }
               dojo.attr(id + '-Administrative-text' , 'value' , data.display_name);
               dojo.attr(id + '-Administrative-adm1' , 'value' , adm1);
               dojo.attr(id + '-Administrative-adm2' , 'value' , adm2);
               dojo.xhrGet({
                    url : APP_BASEPATH + "/ajax/get-country",
                    handleAs : "text",
                    content : {code : data.address.country_code},
                    load : function (countryId) {
                         setSelect2Data(id + '-Administrative-country' , countryId);
                    },
                    error : function (err) {
                        console.log(err);
                    }
               })
          },
          error : function (err) {
               clearMarker();
               alert('Sorry could not fetch location data. Please try again');
               console.log(err);
          }
      });
}