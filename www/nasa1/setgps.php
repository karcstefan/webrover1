<?php
include_once( dirname(__FILE__) . '/class/GPS.php' );
?>
<!DOCTYPE html>
<html>
    <head>
        <title>set GPS coordinates</title>
        <script type="text/javascript" src="js/socket.io.min.js"></script>
    </head>
    <body onload="sendNotification();">
        <?php
        //The Latitude of Prilep is 41.351730000000000000. 
        //The Longitude of Prilep is 21.562140000000000000.
        if (isset($_GET['device_id']) && isset($_GET['lon']) && isset($_GET['lat']) 
                && $_GET['lon'] != "" && $_GET['lat'] != "" && $_GET['device_id']!="") {
            $lon = $_GET['lon'];
            $lat = $_GET['lat'];
            $device_id = $_GET['device_id'];
            $gps = new GPS($lon, $lat, $device_id);
            $gps->save();
            // insert in DB
        } else {
            $lat = $lon = 0;
        }
        ?>
        <div id="latitude" style="display: block;"><?php echo $lat; ?></div>
        <div id="longitude" style="display: block;"><?php echo $lon; ?></div>

        <script>
            function sendNotification() {
                var lat = document.getElementById("latitude");
                var lon = document.getElementById("longitude");
                //   alert(lat.innerHTML);
                lon = lon.innerHTML;
                lat = lat.innerHTML;

                var data = {
                    "lon": lon,
                    "lat": lat
                };
                
                if (lon != 0 && lat != 0) {
                    var socket = io.connect('http://localhost:3000');
                    socket.on('connect', function() {
                        var decData = JSON.stringify(data);
                        console.log('data sended');
                        socket.send(decData);
                    });
                }
            }
        </script>
    </body>
</html>
