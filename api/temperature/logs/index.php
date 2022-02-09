<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['temperature'] = TemperatureChart::Indoors();
$data['rooms'] = TemperatureChart::AllRooms();
OutputJson($data);
?>