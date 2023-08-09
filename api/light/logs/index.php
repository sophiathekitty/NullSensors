<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['room_id'])){
    $data['room'] = LightSensorChart::Room($_GET['room_id']);
}else{
    $data['daytime_average'] = LightSensorChart::DaytimeAverage();
    $data['nighttime_average'] = LightSensorChart::NighttimeAverage();
    $data['rooms'] = LightSensorChart::AllRooms();
}
OutputJson($data);
?>