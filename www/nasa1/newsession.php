<?php

include_once( dirname(__FILE__) . '/class/Database.class.php' );

if (isset($_GET['device_id']) && $_GET['device_id'] != "") {
    $device_id = $_GET['device_id'];
    $device_id;

    $pdo = Database::getInstance()->getPdoObject();

    $query = $pdo->prepare('SELECT *
            FROM geolocations geo1
            WHERE geo1.id = (
            SELECT MAX(geo2.id) FROM geolocations geo2
            WHERE geo2.device_id = :device_id
            );');

    $query->execute(array("device_id" => $device_id));
    $lastObj = $query->fetch(PDO::FETCH_OBJ);
    $sessionId = 1;
    if (isset($lastObj)) {
        $sessionId = $lastObj->session_id;
    }
    $sessionId++;

    $query = $pdo->prepare('INSERT INTO geolocations(`lon`,`lat`,`device_id`,`session_id`,`datetime`) VALUES( :lon, :lat, :device_id, :session_id , now())');
    $query->execute(array("lon" => $lastObj->lon, "lat" => $lastObj->lat, "device_id" => $device_id, "session_id" => $sessionId));
}
?>