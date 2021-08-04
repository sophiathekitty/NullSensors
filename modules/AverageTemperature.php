<?php
function AverageIndoorTemperature(){
    $sensors = TemperatureSensors::LoadWorkingSensors();
        
    $temp = 0;
    $temp_max = 0;
    $temp_min = 100000;

    $hum = 0;
    $hum_max = 0;
    $hum_min = 100000;

    foreach($sensors as $sensor){
        $temp += $sensor['temp'];
        if($temp_max < $sensor['temp_max']) $temp_max = $sensor['temp_max'];
        if($temp_min > $sensor['temp_min']) $temp_min = $sensor['temp_min'];
        
        $hum += $sensor['hum'];
        if($hum_max < $sensor['hum_max']) $hum_max = $sensor['hum_max'];
        if($hum_min > $sensor['hum_min']) $hum_min = $sensor['hum_min'];
    }
    return [
        'temp' => (float)round($temp/count($sensors),2),
        'temp_max' => (float)$temp_max,
        'temp_min' => (float)$temp_min,
        'hum' => (float)round($hum/count($sensors),2),
        'hum_max' => (float)$hum_max,
        'hum_min' => (float)$hum_min
    ];
}
?>