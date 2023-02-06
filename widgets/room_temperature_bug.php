<?php
if(!isset($_GET['room_id'])) die();
require_once("../../../includes/main.php");
$temp = RoomDHT11::RoomTemperature($_GET['room_id']);
if(is_nan($temp['temp'])) die();
?>
<span type="temperature" var="temp" unit="fahrenheit" title="Temp: <?=round($temp['temp'],1);?>° | <?=round($temp['temp_max'],1);?>° / <?=round($temp['temp_min'],1);?>°
Hum: <?=round($temp['hum'],1);?>% | <?=round($temp['hum_max'],1)?>% / <?=round($temp['hum_min'],1);?>%" style="color:<?=interpolateColor(Colors::GetColor("temp_".floor($temp['temp']/10)),Colors::GetColor("temp_".ceil($temp['temp']/10)),($temp['temp']/10)-floor($temp['temp']/10));?>"><?=round($temp['temp'])?></span>
