<?php
include_once 'header.php';
include 'locations_model.php';
//get_unconfirmed_locations();exit;
?>
<?php
$con=mysqli_connect ("localhost", 'root', '','monitoring_db');
    if (!$con) {
        die('Not connected : ' . mysqli_connect_error());
    }

$query1 = "SELECT * from equipments where category = 'heavy'";

$result1 = mysqli_query($con,$query1);
?>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyA-AB-9XZd-iQby-bNLYPFyb0pR2Qw3orw">
    </script>

    <div id="map"></div>
    <script>
        /**
         * Create new map
         */
        var infowindow;
        var map;
        var red_icon =  'http://maps.google.com/mapfiles/ms/icons/red-dot.png' ;
        var purple_icon =  'http://maps.google.com/mapfiles/ms/icons/purple-dot.png' ;
        var locations = <?php get_confirmed_locations() ?>;
        //var equipments = <?php //getEquipment() ?>;
        var myOptions = {
            zoom: 9,
            center: new google.maps.LatLng(10.2926,123.0247),
            mapTypeId: 'roadmap'
        };
        map = new google.maps.Map(document.getElementById('map'), myOptions);

        /**
         * Global marker object that holds all markers.
         * @type {Object.<string, google.maps.LatLng>}
         */
        var markers = {};

        /**
         * Concatenates given lat and lng with an underscore and returns it.
         * This id will be used as a key of marker to cache the marker in markers object.
         * @param {!number} lat Latitude.
         * @param {!number} lng Longitude.
         * @return {string} Concatenated marker id.
         */
        var getMarkerUniqueId= function(lat, lng) {
            return lat + '_' + lng;
        };

        /**
         * Creates an instance of google.maps.LatLng by given lat and lng values and returns it.
         * This function can be useful for getting new coordinates quickly.
         * @param {!number} lat Latitude.
         * @param {!number} lng Longitude.
         * @return {google.maps.LatLng} An instance of google.maps.LatLng object
         */
        var getLatLng = function(lat, lng) {
            return new google.maps.LatLng(lat, lng);
        };

        /**
         * Binds click event to given map and invokes a callback that appends a new marker to clicked location.
         */
        var addMarker = google.maps.event.addListener(map, 'click', function(e) {
            var lat = e.latLng.lat(); // lat of clicked point
            var lng = e.latLng.lng(); // lng of clicked point
            var markerId = getMarkerUniqueId(lat, lng); // an that will be used to cache this marker in markers object.
            var i ; var confirmed = 0;
            var marker = new google.maps.Marker({
                position: getLatLng(lat, lng),
                map: map,
                animation: google.maps.Animation.DROP,
                id: 'marker_' + markerId,
                html: "    <div id='info_"+markerId+"'>\n" +
                "        <table class=\"map1\">\n" +
                "            <tr>\n" +
                "                <td><a>Equipment:</a></td>\n" +
                "                \n" +
                "                <td><select id = 'desc'>"+ <?php
                       include('db.php');
                        $query2=mysqli_query($connection,"select * from equipments where category = 'heavy'")or die(mysqli_error());
                          while($row2=mysqli_fetch_array($query2)){
                      ?>"<option id='manual_description' value='<?php echo $row2[0];?>'><?php echo $row2[1];?></option>"+ <?php }?> + "</select></td></tr>\n" +
                "      <tr>\n" +
                    "  <td><a>Employee:</a></td>\n" +
                "                \n" +
                "                <td><select id = 'eid'>"+ <?php
                       include('db.php');
                        $query3=mysqli_query($connection,"select * from employees")or die(mysqli_error());
                          while($row3=mysqli_fetch_array($query3)){
                      ?>"<option id='emp' value='<?php echo $row3[0];?>'><?php echo $row3[1];?></option>"+ <?php }?> + "</select></td></tr>\n" +
                "            <tr><td></td><td><input type='button' value='Save' onclick='saveData("+lat+","+lng+")'/></td></tr>\n" +
                "        </table>\n" +
                "    </div>"
            });
            markers[markerId] = marker; // cache marker in markers object
            bindMarkerEvents(marker); // bind right click event to marker
            bindMarkerinfo(marker); // bind infowindow with click event to marker
        });


        /**
         * Binds  click event to given marker and invokes a callback function that will remove the marker from map.
         * @param {!google.maps.Marker} marker A google.maps.Marker instance that the handler will binded.
         */
        var bindMarkerinfo = function(marker) {
            google.maps.event.addListener(marker, "click", function (point) {
                var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
                var marker = markers[markerId]; // find marker
                infowindow = new google.maps.InfoWindow();
                infowindow.setContent(marker.html);
                infowindow.open(map, marker);
                // removeMarker(marker, markerId); // remove it
            });
        };

        /**
         * Binds right click event to given marker and invokes a callback function that will remove the marker from map.
         * @param {!google.maps.Marker} marker A google.maps.Marker instance that the handler will binded.
         */
        var bindMarkerEvents = function(marker) {
            google.maps.event.addListener(marker, "rightclick", function (point) {
                var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
                var marker = markers[markerId]; // find marker
                removeMarker(marker, markerId); // remove it
            });
        };

        /**
         * Removes given marker from map.
         * @param {!google.maps.Marker} marker A google.maps.Marker instance that will be removed.
         * @param {!string} markerId Id of marker.
         */
        var removeMarker = function(marker, markerId) {
            marker.setMap(null); // set markers setMap to null to remove it from map
            delete markers[markerId]; // delete marker instance from markers object
        };


        /**
         * loop through (Mysql) dynamic locations to add markers to map.
         */
        var i ; var confirmed = 0;
        for (i = 0; i < locations.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][3], locations[i][4]),
                map: map,
                icon :   locations[i][7] === '1' ?  red_icon  : red_icon,
                html: 
                "<form method='post' action='#'>"+
                "<div>\n" +
                "<table class=\"map1\">\n" +
                "<td><input type='hidden' name='id' value='"+locations[i][0]+"' placeholder='Description'></td>\n"+
                "<tr>\n" +
                "<td><a>Equipment:</a></td>\n" +
                " \n" +
                "<td><input type='read only' name='equip' value='"+locations[i][2]+"' placeholder='Description'></td>\n" +
                " <tr>\n" +
                " \n" +
                "<td><a>Date Deploy:</a></td>\n" +
                " \n" +
                "<td><input type='read only' value='"+locations[i][5]+"'></td>\n"+
                " <tr>\n" +
                " \n" +
                "<td><a>Employee Assigned:</a></td>\n" +
                " \n" +
                "<td><input type='read only' value='"+locations[i][8]+"'></td>\n"+
                "<td><button type='submit' name='submit' style='background-color: green'>Return</button></td>"+
                "</tr>"+
                "</table>\n" +
                "</div>" +
                "</form>"
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow = new google.maps.InfoWindow();
                    confirmed =  locations[i][4] === '1' ?  'checked'  :  0;
                    $("#confirmed").prop(confirmed,locations[i][7]);
                    $("#id").val(locations[i][0]);
                    $("#description").val(locations[i][2]);
                    $("#form").show();
                    infowindow.setContent(marker.html);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }

        /**
         * SAVE save marker from map.
         * @param lat  A latitude of marker.
         * @param lng A longitude of marker.
         */
        function saveData(lat,lng) {
            var desc = $("#desc").val();
            var eid = $("#eid").val();
            var description = document.getElementById('manual_description').value;
            var emp = document.getElementById('emp').value;
            var url = 'locations_model.php?add_location&description=' + desc + '&emp='+ eid +'&lat=' + lat + '&lng=' + lng;
            downloadUrl(url, function(data, responseCode) {
                if (responseCode === 200  && data.length > 1) {
                    var markerId = getMarkerUniqueId(lat,lng); // get marker id by using clicked point's coordinate
                    var manual_marker = markers[markerId]; // find marker
                    manual_marker.setIcon(purple_icon);
                    infowindow.close();
                    alert("successfully added!");
                    window.location = 'borrowed_equip.php';
                    infowindow.open(map, manual_marker);
                    infowindow.close();

                }else{
                    console.log(responseCode);
                    console.log(data);
                    infowindow.setContent("<div style='color: red; font-size: 25px;'>Inserting Errors</div>");
                }
            });
        }

        function downloadUrl(url, callback) {
            var request = window.ActiveXObject ?
                new ActiveXObject('Microsoft.XMLHTTP') :
                new XMLHttpRequest;

            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    callback(request.responseText, request.status);
                }
            };

            request.open('GET', url, true);
            request.send(null);
        }


    </script>

<?php
$con=mysqli_connect ("localhost", 'root', '','monitoring_db');
if(isset($_POST['submit'])){
    $equip = $_POST['equip'];
    $id = $_POST['id'];
    // Inserts new row with place data.
    $result=mysqli_query($con,"UPDATE equip_mapping SET isVisible = 'NO' WHERE map_id ='$id'")
    or die(mysqli_error());
    echo "Returned Successfully";
    ?><script type="text/javascript">
        alert("Returned Successfully");
        window.location = "borrowed_equip.php";
    </script>
    <?php
    if (!$result) {
        die('Invalid query: ' . mysqli_error($con));
    }
}
?>



<?php
include_once 'footer.php';

?>