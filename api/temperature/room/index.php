<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['room_id'])){
    $data['room'] = RoomDHT11::RoomTemperature($_GET['room_id']);
} else if(isset($_GET['average'])){
    $data['room'] = AverageIndoorTemperature();
} else {
    $data['room'] = RoomTemperature();
}
OutputJson($data);
?>