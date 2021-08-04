<?php
require_once("../../../../includes/main.php");
$data = [];
if(isset($_GET['working'])){
    $data['temperature'] = TemperatureSensors::LoadWorkingSensors();
} else if(isset($_GET['average'])){
    $data['temperature'] = AverageIndoorTemperature();
} else {
    $data['temperature'] = TemperatureSensors::LoadSensors();
}
OutputJson($data);
?>