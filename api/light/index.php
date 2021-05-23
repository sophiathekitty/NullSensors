<?php
require_once("../../../../includes/main.php");
$data = [];
$data['light'] = LightSensors::LoadSensors();
OutputJson($data);
?>