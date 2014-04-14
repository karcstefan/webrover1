<?php

for ($i = 1; $i < 10; $i++) {
    sleep(2);
//    $url = "http://api.worldweatheronline.com/free/v1/weather.ashx?key=naycw7c84t84zrgvpfp44hxg&q=" . $lat . "," . $lon . "&num_of_days=3&format=json";
    $y = 2 * $i;
    echo $i." ".$y.'<br/>';
    $url = 'http://localhost/nasa1/setgps.php?device_id=1&lat=' . $i . '&lon=' . $y;

    $ch = curl_init(); // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set the url
    curl_setopt($ch, CURLOPT_URL, $url); // Execute		

    curl_exec($ch);
}
?>