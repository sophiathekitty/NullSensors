<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['room_id'])){
    $data['room'] = RoomDHT11::RoomTemperature($_GET['room_id']);
} else {
    $data['indoors'] = AverageIndoorTemperature();
}
OutputJson($data);
?>