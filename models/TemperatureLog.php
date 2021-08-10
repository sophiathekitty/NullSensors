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
    private static function GetInstance(){
        if(is_null(TemperatureLog::$sensors)) TemperatureLog::$sensors = new TemperatureLog();
        return TemperatureLog::$sensors;
    }
    public static function LogSensor($data){
        $sensors = TemperatureLog::GetInstance();
        $sensor = TemperatureSensors::LoadSensorId($data['sensor_id']);
        $sensors->PruneField('created',DaysToSeconds($sensor['keep_time']));
        $data = $sensors->CleanDataSkipFields($data,['id']);
        return $sensors->Save($data);
    }
    public static function LatestLog($sensor_id){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadById($sensor_id);
    }
    public static function LoadLogs($sensor_id){
        $sensors = TemperatureLog::GetInstance();
        return $sensors->LoadAllWhere(['id'=>$sensor_id]);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new TemperatureLog();
}
?>