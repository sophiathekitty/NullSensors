<?php
require_once("../../../../includes/main.php");
$data = [];
$data['temperature'] = TemperatureSensors::LoadSensors();
OutputJson($data);
?>