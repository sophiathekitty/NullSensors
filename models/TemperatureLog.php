<?php
/**
 * temperature sensors
 */
class TemperatureLog extends clsModel {
    public $table_name = "TemperatureLog";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"sensor_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"inside",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"garden",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ],[
            'Field'=>"temp",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"temp_max",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"temp_min",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"hum",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"hum_max",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"hum_min",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    private static $sensors = null;
    /**
     * @return TemperatureLog|clsModel
     */
    private static function GetInstance(){
        if(is_null(TemperatureLog::$sensors)) TemperatureLog::$sensors = new TemperatureLog();
        return TemperatureLog::$sensors;
    }
    /**
     * log a sensor's data
     * @param array $data the sensor's data array (make sure to)
     */
    public static function LogSensor($data){
        if(isset($data['error']) && $data['error'] != "ok") return ["no_log"=>$data['error']];
        $sensors = TemperatureLog::GetInstance();
        if(!isset($data['sensor_id']) && isset($data['log_delay'])){
            $data['sensor_id'] = $data['id'];
        }
        if(!isset($data['keep_time']) || !isset($data['log_delay'])){
            $sensor = TemperatureSensors::LoadSensorId($data['sensor_id']);
            $data['keep_time'] = $sensor['keep_time'];
            $data['log_delay'] = $sensor['log_delay'];
        }
        $log = TemperatureLog::LatestLog($data['sensor_id']);
        if(time() - strtotime($log['created']) < $data['log_delay']) return ["no_log"=>time() - strtotime($log['created'])];
        $sensors->PruneField('created',DaysToSeconds($data['keep_time']));
        if(isset($data['garden_id']) && (int)$data['garden_id'] != 0) $data['garden'] = 1;
        if(isset($data['outside']) && (int)$data['outside'] != 0) $data['inside'] = 0;
        $data = $sensors->CleanDataSkipFields($data,['id']);
        //print_r($data);
        Debug::Log("TemperatureLog::LogSensor",$data);
        //Services::Log("NullSensors::PullRemoteSensors","SyncTemperatureFromHub::sensor::".$data['sensor_id']." [".$data['inside']."] ".$data['garden']);
        return $sensors->Save($data);
    }
    /**
     * get the most recent log
     * @param int $sensor_id the sensor id
     * @return array the latest sensor log with $sensor_id
     */
    public static function LatestLog($sensor_id){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadWhere(["sensor_id"=>$sensor_id],['created'=>"DESC"]);
    }
    /**
     * get the logs for a sensor
     * @param int $senor_id the sensor id
     * @return array the list of logs for $sensor_id
     */
    public static function LoadLogs($sensor_id){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadAllWhere(['id'=>$sensor_id]);
    }
    /**
     * load temperature from hour of day
     * @param int $h the hour to load
     * @return array array of temperature data for the specified hour of the day
     */
    public static function LoadTemperatureHour($h){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadFieldHour('created',$h);
    }
    /**
     * load inside temperature from hour of day
     * @param int $h the hour to load
     * @return array array of temperature data for the specified hour of the day
     */
    public static function LoadInsideTemperatureHour($h){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadFieldHourWhere("`inside` = '1' AND `garden` = '0'",'created',$h);
    }
    /**
     * load temperature from hour of day
     * @param int $h the hour to load
     * @return array array of temperature data for the specified hour of the day
     */
    public static function LoadGardenTemperatureHour($h){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadFieldHourWhere("`garden` = '1'",'created',$h);
    }
    /**
     * load temperature from hour of day
     * @param int $h the hour to load
     * @return array array of temperature data for the specified hour of the day
     */
    public static function LoadTemperatureHourSensor($sensor_id,$h){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadFieldHourWhere("`sensor_id` = '$sensor_id'",'created',$h);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new TemperatureLog();
}
?>