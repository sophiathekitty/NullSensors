<?php
class TemperatureLogger{
    public static function RecordTemperature($gpio,$temperature,$humidity){
        $sensor = TemperatureSensors::LoadLocalSensor($gpio);
        $new = false;
        if(is_null($sensor)){
            // is new
            $new = true;
            $sensor = ['gpio'=>$gpio,'mac_address'=>LocalMac(),'log_delay'=>60];
        }
        $sensor['error'] = "ok";
        $sensor['temp'] = $temperature;
        $sensor['hum'] = $humidity;
        if($new){
            $sensor['hum_max'] = $humidity;
            $sensor['hum_min'] = $humidity;
            $sensor['temp_max'] = $temperature;
            $sensor['temp_min'] = $temperature;
            //TemperatureLog::LogSensor($sensor);
        } else {
            if($sensor['hum_max'] < $humidity) $sensor['hum_max'] = $humidity;
            if($sensor['hum_min'] < $humidity) $sensor['hum_min'] = $humidity;
            if($sensor['temp_max'] < $temperature) $sensor['temp_max'] = $temperature;
            if($sensor['temp_min'] < $temperature) $sensor['temp_min'] = $temperature;
            $log = TemperatureLog::LatestLog($sensor['id']);
            if(strtotime($log['created']) + $sensor['log_delay'] < time()){
                // time for new log
                $sensor['sensor_id'] = $sensor['id'];
                TemperatureLog::LogSensor($sensor);
                // if we're logging the min and max should get reset
                $sensor['hum_max'] = $humidity;
                $sensor['hum_min'] = $humidity;
                $sensor['temp_max'] = $temperature;
                $sensor['temp_min'] = $temperature;
            }
        }
        TemperatureSensors::SaveSensor($sensor);
    }
    public static function RecordError($gpio,$error){
        $sensor = TemperatureSensors::LoadLocalSensor($gpio);
        if(is_null($sensor)) return;
        $sensor['error'] = $error;
        TemperatureSensors::SaveSensor($sensor);
    }
}
?>