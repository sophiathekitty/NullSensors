<?php
/**
 * returns the room temperature for the room this device is in or the average for all rooms
 * if this device doesn't have a room_id SettingsVar
 * @return array ['temp', 'temp_max', 'temp_min', 'hum', 'hum_max', 'hum_min']
 */
function RoomTemperature(){
    $room_id = Settings::LoadSettingsVar('room_id');
    if(is_null($room_id) || (int)$room_id == 0) return AverageIndoorTemperature();
    return RoomCurrentTemperature($room_id);
}
/**
 * returns the room temperature
 * @depreciated use `RoomDHT11::RoomTemperature($room_id)`
 * @param int $room_id the room's id
 * @return array ['temp', 'temp_max', 'temp_min', 'hum', 'hum_max', 'hum_min']
 */
function RoomCurrentTemperature($room_id){
    $sensors = TemperatureSensors::LoadRoomSensors($room_id,true);
    Debug::Log("RoomCurrentTemp($room_id)",$sensors);
    if(is_null($sensors) || count($sensors) == 0) return null;//AverageIndoorTemperature();
    $indoorSensors = [];
    $gardenSensors = [];
    foreach($sensors as $sensor){
        if($sensor['garden_id'] == 0) $indoorSensors[] = $sensor;
        else $gardenSensors[] = $sensor;
    }
    if(count($indoorSensors) == 0) $indoorSensors = TemperatureSensors::LoadWorkingIndoorSensors();
    $indoorAverage = AverageTemperatureSensors($indoorSensors);
    if(count($gardenSensors) == 0) return $indoorAverage;
    $gardenAverage = AverageTemperatureSensors($gardenSensors);
    $average = MergeTemperatures($indoorAverage,$gardenAverage,Settings::LoadSettingsVar("IndoorGardenTempRatioRoom$room_id",0.75));
    $average['garden'] = $gardenAverage;
    return $average;
    
    /*    
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
    */
}
/**
 * handles the temperature (dht11) sensors for a room
 */
class RoomDHT11 {
    /**
     * checks if the room temperature is above a threshold temperature
     * @param float $temp the threshold temperature
     * @return bool returns true of room temperature is above threshold temperature
     */
    public static function RoomTemperatureAbove($room_id,$temp){
        $dht11 = RoomDHT11::RoomTemperature($room_id);
        return ((float)$dht11['temp'] > $temp);
    }
    /**
     * get the temperature and humidity data for a room
     * @param int $room_id the id of the room to load
     * @return array dht11 sensor data for all working dht11 sensors in room
     */
    public static function RoomTemperature($room_id){
        $sensors = TemperatureSensors::LoadRoomSensors($room_id);
    
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
            'hum' => (float)round($temp/count($sensors),2),
            'hum_max' => (float)$hum_max,
            'hum_min' => (float)$hum_min
        ];    
    }
}
?>