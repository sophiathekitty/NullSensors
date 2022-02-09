<?php

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
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>"on update current_timestamp()"
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
        $data = $sensors->CleanDataSkipFields($data,['id']);
        //print_r($data);
        return $sensors->Save($data);
    }
    public static function LatestLog($sensor_id){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadWhere(["sensor_id"=>$sensor_id],['created'=>"DESC"]);
    }
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