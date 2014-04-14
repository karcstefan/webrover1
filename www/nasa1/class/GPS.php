<?php

include_once( dirname(__FILE__) . '/Database.class.php' );

class GPS {

    public $pdo;
    public $lon;
    public $lat;
    public $device_id;
    public $session_id;

    public function __construct($lon, $lat, $device_id) {
        $this->pdo = Database::getInstance()->getPdoObject();
        $this->lon = $lon;
        $this->lat = $lat;
        $this->device_id = $device_id;
    }

    public function save() {
        $query = $this->pdo->prepare('SELECT *
            FROM geolocations geo1
            WHERE geo1.id = (
            SELECT MAX(geo2.id) FROM geolocations geo2
            WHERE geo2.device_id = :device_id
            );');

        $query->execute(array("device_id" => $this->device_id));
        $lastObj = $query->fetch(PDO::FETCH_OBJ);
        $sessionId = 1;
        if ($lastObj == false) {
            $sessionId = $lastObj->session_id;
        }
        $query = $this->pdo->prepare('INSERT INTO geolocations(`lon`,`lat`,`device_id`,`session_id`,`datetime`) VALUES( :lon, :lat, :device_id, :session_id , now())');
        $query->execute(array("lon" => $this->lon, "lat" => $this->lat, "device_id" => $this->device_id, "session_id" => $sessionId));
    }

    public static function getLastPosition($device_id) {

        $pdo = Database::getInstance()->getPdoObject();

        $query = $pdo->prepare('SELECT *
            FROM geolocations geo1
            WHERE geo1.id = (
            SELECT MAX(geo2.id) FROM geolocations geo2
            WHERE geo2.device_id = :device_id
            );');

        $query->execute(array("device_id" => $device_id));
        $lastObj = $query->fetch(PDO::FETCH_OBJ);

        if ($lastObj == false) {
            $query = $pdo->prepare('SELECT * FROM geolocations LIMIT 1');
            $query->execute();
            $lastObj = $query->fetch(PDO::FETCH_OBJ);
            $lastObj->lon = 0;
            $lastObj->lat = 0;
            $lastObj->device_id = $device_id;
            $lastObj->session_id = 0;
            $lastObj->datetime = NULL;
            $lastObj->id = NULL;
        }

        return $lastObj;
    }

}

?>