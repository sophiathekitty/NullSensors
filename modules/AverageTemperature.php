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
    $indoorSensors = [];
    $gardenSensors = [];
    foreach($sensors as $sensor){
        if($sensor['garden_id'] == 0) $indoorSensors[] = $sensor;
        else $gardenSensors[] = $sensor;
    }
    $indoorAverage = AverageTemperatureSensors($indoorSensors);
    $gardenAverage = AverageTemperatureSensors($gardenSensors);
    return MergeTemperatures($indoorAverage,$gardenAverage,Settings::LoadSettingsVar("IndoorGardenTempRatio",0.75));
}
function AverageTemperatureSensors($sensors){
    Debug::Log("AverageTemperatureSensors",$sensors);
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
/**
 * Merge two temperatures together (temp, temp_min, temp_max, hum, hum_min, hum_max)
 * @param array $tempA the first temperature (temp, temp_min, temp_max, hum, hum_min, hum_max)
 * @param array $tempB the second temperature (temp, temp_min, temp_max, hum, hum_min, hum_max)
 * @param float $percent the percentage of $tempA to use ($a*$percent + $b*(1-$percent))
 * @return array the merged temperature 
 */
function MergeTemperatures($tempA, $tempB,$percent = 0.5){
    $temp_max = 0;
    $temp_min = 100000;
    if($tempA['temp_max'] > $tempB['temp_max']) $temp_max = $tempA['temp_max'];
    else $temp_max = $tempB['temp_max'];
    if($tempA['temp_min'] < $tempB['temp_min']) $temp_min = $tempA['temp_min'];
    else $temp_min = $tempB['temp_min'];
    
    $hum_max = 0;
    $hum_min = 100000;
    if($tempA['hum_max'] > $tempB['hum_max']) $hum_max = $tempA['hum_max'];
    else $hum_max = $tempB['hum_max'];
    if($tempA['hum_min'] < $tempB['hum_min']) $hum_min = $tempA['hum_min'];
    else $hum_min = $tempB['hum_min'];
        
    return [
        'temp' => (float)round(MergeFloats($tempA['temp'],$tempB['temp'],$percent),2),
        'temp_max' => (float)$temp_max,
        'temp_min' => (float)$temp_min,
        'hum' => (float)round(MergeFloats($tempA['hum'],$tempB['hum'],$percent),2),
        'hum_max' => (float)$hum_max,
        'hum_min' => (float)$hum_min
    ];
}
?>