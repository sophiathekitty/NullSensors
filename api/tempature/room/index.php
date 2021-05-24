<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['room'] = RoomTemperature();
OutputJson($data);
?>