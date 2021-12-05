<?php
function RoomTemperature(){
    RoomCurrentTemperature(Settings::LoadSettingsVar('room_id'));
}
function RoomCurrentTemperature($room_id){
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
        'hum' => (float)round($hum/count($sensors),2),
        'hum_max' => (float)$hum_max,
        'hum_min' => (float)$hum_min
    ];
}
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