<?php
require_once("../../../includes/main.php");
$chart = TemperatureChart::Indoors();
if(!isset($chart[0]['count'])) die();
function PixelHour($hour){
    ?>
    <div class="hour" hour="<?=$hour['hour'];?>" style="background-color:<?=interpolateColor(Colors::GetColor("temp_".floor($hour['temp']/10)),Colors::GetColor("temp_".ceil($hour['temp']/10)),($hour['temp']/10)-floor($hour['temp']/10));?>" title="Indoors -- <?=Times24ToTime12Short($hour['hour'])?>\nTemp: <?=round($hour['temp']);?>° | <?=round($hour['temp_max']);?>° / <?=round($hour['temp_min']);?>°\nHum: <?=round($hour['hum']);?>% | <?=round($hour['hum_max'])?>% / <?=round($hour['hum_min']);?>%"></div>
    <?php
}
?>
<div class="temp_chart indoors simple" collection="indoor_temperature" room_id="<?=$_GET['room_id'];?>">
    <div class="time_bar"></div>
    <?php foreach($chart as $hour) PixelHour($hour); ?>
</div>