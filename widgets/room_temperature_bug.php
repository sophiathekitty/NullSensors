<?php
if(!isset($_GET['room_id'])) die();
require_once("../../../includes/main.php");
$temp = RoomDHT11::RoomTemperature($_GET['room_id']);
if(is_nan($temp['temp'])) die();
?>
<span type="temperature" var="temp" unit="fahrenheit" title="Temp: <?=round($temp['temp']);?>° | <?=round($temp['temp_max']);?>° / <?=round($temp['temp_min']);?>°\nHum: <?=round($temp['hum']);?>% | <?=round($temp['hum_max'])?>% / <?=round($temp['hum_min']);?>%" style="color:<?=interpolateColor(Colors::GetColor("temp_".floor($temp['temp']/10)),Colors::GetColor("temp_".ceil($temp['temp']/10)),($temp['temp']/10)-floor($temp['temp']/10));?>"><?=round($temp['temp'])?></span>
