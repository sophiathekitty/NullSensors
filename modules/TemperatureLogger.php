<?php
class TemperatureLogger{
    public static function RecordTemperature($gpio,$temperature,$humidity){
        $sensor = TemperatureSensors::LoadLocalSensor($gpio);
        print_r($sensor);
        $new = false;
        if(is_null($sensor)){
            // is new
            $new = true;
            $sensor = ['gpio'=>$gpio,'mac_address'=>LocalMac(),'log_delay'=>60];
        }
        $temperature = round($temperature,1);
        $humidity = round($humidity,1);
        $sensor['error'] = "ok";
        $sensor['temp'] = $temperature;
        $sensor['hum'] = $humidity;
        if($new){
            echo "new";
            $sensor['hum_max'] = $humidity;
            $sensor['hum_min'] = $humidity;
            $sensor['temp_max'] = $temperature;
            $sensor['temp_min'] = $temperature;
            TemperatureLog::LogSensor($sensor);
        } else {
            echo "not new";
            $sensor['remote_id'] = $sensor['id'];
            if($sensor['hum_max'] < $humidity) $sensor['hum_max'] = $humidity;
            if($sensor['hum_min'] > $humidity) $sensor['hum_min'] = $humidity;
            if($sensor['temp_max'] < $temperature) $sensor['temp_max'] = $temperature;
            if($sensor['temp_min'] > $temperature) $sensor['temp_min'] = $temperature;
            $log = TemperatureLog::LatestLog($sensor['id']);
            if(is_null($log) || (strtotime($log['created']) + $sensor['log_delay']) < time()){
                // time for new log
                $sensor['sensor_id'] = $sensor['id'];

                //TemperatureLog::LogSensor($sensor);
                // if we're logging the min and max should get reset (but only if log exists)
                if(!is_null($log)){
                    $sensor['hum_max'] = $humidity;
                    $sensor['hum_min'] = $humidity;
                    $sensor['temp_max'] = $temperature;
                    $sensor['temp_min'] = $temperature;    
                }
            }
        }
        print_r($sensor);
        TemperatureSensors::SaveSensor($sensor);
    }
    public static function RecordError($gpio,$error){
        $sensor = TemperatureSensors::LoadLocalSensor($gpio);
        if(is_null($sensor)) return;
        $sensor['error'] = $error;
        TemperatureSensors::SaveSensor($sensor);
    }
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