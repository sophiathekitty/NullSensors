<?php
/**
 * temperature logger handler
 */
class TemperatureLogger{
    /**
     * record temperature for local sensor. will create new temperature sensor if one doesn't exist
     * if the sensor isn't new it will see if it's time to create a new log
     * @param int $gpio the gpio pin for the sensor
     * @param float $temperature the temperature
     * @param float $humidity the humidity
     */
    public static function RecordTemperature($gpio,$temperature,$humidity){
        $sensor = TemperatureSensors::LoadLocalSensor($gpio);
        //print_r($sensor);
        $new = false;
        if(is_null($sensor)){
            // is new
            $new = true;
            $sensor = ['gpio'=>$gpio,'mac_address'=>LocalMac(),'log_delay'=>60];
        }
        $temperature = round($temperature,1);
        $humidity = round($humidity,1);
        $sensor['error'] = "ok";
        $sensor['modified'] = date("Y-m-d H:i:s");
        $sensor['temp'] = $temperature;
        $sensor['hum'] = $humidity;
        if($new){
            //echo "new";
            $sensor['hum_max'] = $humidity;
            $sensor['hum_min'] = $humidity;
            $sensor['temp_max'] = $temperature;
            $sensor['temp_min'] = $temperature;
            TemperatureLog::LogSensor($sensor);
        } else {
            //echo "not new";
            $sensor['remote_id'] = $sensor['id'];
            if($sensor['hum_max'] < $humidity) $sensor['hum_max'] = $humidity;
            if($sensor['hum_min'] > $humidity) $sensor['hum_min'] = $humidity;
            if($sensor['temp_max'] < $temperature) $sensor['temp_max'] = $temperature;
            if($sensor['temp_min'] > $temperature) $sensor['temp_min'] = $temperature;
            $log = TemperatureLog::LatestLog($sensor['id']);
            if(is_null($log) || (strtotime($log['created']) + $sensor['log_delay']) < time()){
                // time for new log
                $sensor['sensor_id'] = $sensor['id'];

                TemperatureLog::LogSensor($sensor);
                // if we're logging the min and max should get reset (but only if log exists)
                if(!is_null($log)){
                    $sensor['hum_max'] = $humidity;
                    $sensor['hum_min'] = $humidity;
                    $sensor['temp_max'] = $temperature;
                    $sensor['temp_min'] = $temperature;    
                }
            }
        }
        //print_r($sensor);
        TemperatureSensors::SaveSensor($sensor);
    }
    /**
     * record an error for a local temperature sensor
     * @param int $gpio the gpio pin number
     * @param string $error the error message "ok" for no error
     */
    public static function RecordError($gpio,$error){
        $sensor = TemperatureSensors::LoadLocalSensor($gpio);
        if(is_null($sensor)) return;
        $sensor['error'] = $error;
        TemperatureSensors::SaveSensor($sensor);
    }
    /**
     * a debugging function for seeing if it's time to do a log? 
     * will break json output because it's a bunch of print_r and echo statements
     */
    public static function DoLog($sensor){
        print_r($sensor);
        $log = TemperatureLog::LatestLog($sensor['id']);
        print_r($log);
        if(is_null($log) || (strtotime($log['created']) + $sensor['log_delay']) < time()){
            echo "do log";
        } else {
            echo "never mind";
        }
    }
}
?>