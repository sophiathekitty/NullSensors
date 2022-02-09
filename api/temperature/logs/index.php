<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['temperature'] = TemperatureChart::Indoors();
OutputJson($data);
?>