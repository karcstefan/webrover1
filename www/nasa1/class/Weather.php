<?php

class Weather {

    private static $instance = null;
    private $pdoObject;
    public $url;
    public $result;
    public $temp_C;
    public $winddirDegree;
    public $windspeedKmph;
    public $humidity;
    public $pressure;

    public function __construct($lat, $lon) {
        $url = "http://api.worldweatheronline.com/free/v1/weather.ashx?key=naycw7c84t84zrgvpfp44hxg&q=" . $lat . "," . $lon . "&num_of_days=3&format=json";

        $ch = curl_init(); // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set the url
        curl_setopt($ch, CURLOPT_URL, $url); // Execute		
        
        $this->result = curl_exec($ch);
        $this->result = json_decode($this->result, true);

        $this->temp_C = $this->result['data']['current_condition'][0]['temp_C'];
        $this->winddirDegree = $this->result['data']['current_condition'][0]['winddirDegree'];
        $this->windspeedKmph = $this->result['data']['current_condition'][0]['windspeedKmph'];
        $this->humidity = $this->result['data']['current_condition'][0]['humidity'];
        $this->pressure = $this->result['data']['current_condition'][0]['pressure'];
    }

}

?>