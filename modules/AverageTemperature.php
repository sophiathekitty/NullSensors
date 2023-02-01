<?php
/**
 * calculates the average indoor temperature
 * @return array ['temp', 'temp_max', 'temp_min', 'hum', 'hum_max', 'hum_min']
 */
function AverageIndoorTemperature(){
    $sensors = TemperatureSensors::LoadWorkingSensors();
    Debug::Log("AverageIndoorTemperature",$sensors);
    if(count($sensors) == 0)return [
        'temp' => 0,
        'temp_max' => 0,
        'temp_min' => 0,
        'hum' => 0,
        'hum_max' => 0,
        'hum_min' => 0,
        'error' => 'no working sensors'
    ];
    $temp = 0;
    $temp_max = 0;
    $temp_min = 100000;

    $hum = 0;
    $hum_max = 0;
    $hum_min = 100000;
    $count = 0;
    foreach($sensors as $sensor){
        if($sensor['garden_id'] == 0){
            for($i = 0; $i < Settings::LoadSettingsVar("main_sensors_padding",4); $i++){
                $temp += $sensor['temp'];
                $hum += $sensor['hum'];
                $count++;    
            }
        } 
        $temp += $sensor['temp'];
        if($temp_max < $sensor['temp_max']) $temp_max = $sensor['temp_max'];
        if($temp_min > $sensor['temp_min']) $temp_min = $sensor['temp_min'];
        
        $hum += $sensor['hum'];
        if($hum_max < $sensor['hum_max']) $hum_max = $sensor['hum_max'];
        if($hum_min > $sensor['hum_min']) $hum_min = $sensor['hum_min'];
        $count++;
    }
    return [
        'temp' => (float)round($temp/$count,2),
        'temp_max' => (float)$temp_max,
        'temp_min' => (float)$temp_min,
        'hum' => (float)round($hum/$count,2),
        'hum_max' => (float)$hum_max,
        'hum_min' => (float)$hum_min
    ];
}
?>