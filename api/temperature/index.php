<?php
require_once("../../../../includes/main.php");
$data = [];
if(isset($_GET['gpio'],$_GET['error'])){
    // report error state
    TemperatureLogger::RecordError($_GET['gpio'],$_GET['error']);
} else if(isset($_GET['gpio'],$_GET['temperature'],$_GET['humidity'])){
    // report local temperature sensor data
    TemperatureLogger::RecordTemperature($_GET['gpio'],$_GET['temperature'],$_GET['humidity']);
} else if(isset($_GET['id'])){
    $data['sensor'] = TemperatureSensors::LoadSensorId($_GET['id']);
} else if(isset($_GET['working'])){
    $data['temperature'] = TemperatureSensors::LoadWorkingSensors();
} else if(isset($_GET['average'])){
    $data['temperature'] = AverageIndoorTemperature();
} else {
    $data['temperature'] = TemperatureSensors::LoadSensors();
}
OutputJson($data);
?>