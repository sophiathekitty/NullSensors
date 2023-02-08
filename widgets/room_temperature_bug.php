<?php
if(!isset($_GET['room_id'])) die();
require_once("../../../includes/main.php");
//$temp = RoomDHT11::RoomTemperature($_GET['room_id']);
$temp = RoomCurrentTemperature($_GET['room_id']);
if(is_null($temp)) die();
?>
<span type="temperature" var="temp" unit="fahrenheit" title="Temp: <?=round($temp['temp'],1);?>° | <?=round($temp['temp_max'],1);?>° / <?=round($temp['temp_min'],1);?>°
Hum: <?=round($temp['hum'],1);?>% | <?=round($temp['hum_max'],1)?>% / <?=round($temp['hum_min'],1);?>%<?php if(isset($temp['garden'])) {?>

Garden
Temp: <?=round($temp['garden']['temp'],1);?>° | <?=round($temp['garden']['temp_max'],1);?>° / <?=round($temp['garden']['temp_min'],1);?>°
Hum: <?=round($temp['garden']['hum'],1);?>% | <?=round($temp['garden']['hum_max'],1)?>% / <?=round($temp['garden']['hum_min'],1);?>%<?php }?>" style="color:<?=interpolateColor(Colors::GetColor("temp_".floor($temp['temp']/10)),Colors::GetColor("temp_".ceil($temp['temp']/10)),($temp['temp']/10)-floor($temp['temp']/10));?>"><?=round($temp['temp'])?></span>
