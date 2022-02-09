<?php
require_once("../../../../../../includes/main.php");
$data = [];
if(isset($_GET['room_id'])){
    $data['temperature'] = TemperatureChart::Room($_GET['room_id']);
} else {
    $data['temperature'] = TemperatureChart::Indoors();
}
OutputJson($data);
?>