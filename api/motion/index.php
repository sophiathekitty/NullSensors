<?php
require_once("../../../../includes/main.php");
$data = [];
$data['motion'] = MotionSensors::LoadSensors();
OutputJson($data);
?>