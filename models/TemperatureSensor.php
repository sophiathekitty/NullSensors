<?php

class TemperatureSensors extends clsModel {
    public $table_name = "TemperatureSensors";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"mac_address",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"remote_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>"0"
        ],[
            'Field'=>"room_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"name",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"log_delay",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"60",
            'Extra'=>""
        ],[
            'Field'=>"keep_time",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"5",
            'Extra'=>""
        ],[
            'Field'=>"log_data",
            'Type'=>"tinyint(1)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ],[
            'Field'=>"modified",
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
        ],[
            'Field'=>"error",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"Ok",
            'Extra'=>""
        ]
    ];
    private static $sensors = null;
    private static function GetInstance(){
        if(is_null(TemperatureSensors::$sensors)) TemperatureSensors::$sensors = new TemperatureSensors();
        return TemperatureSensors::$sensors;
    }
    public static function LoadSensors(){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadAll();
    }
    public static function LoadRoomSensors($room_id){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadAllWhere(['room_id'=>$room_id]);
    }
    public static function SaveSensor($data){
        $sensors = TemperatureSensors::GetInstance();
        if(is_null($sensors->LoadWhere(['id'=>$data['id']]))){
            return $sensors->Save($data);
        }
        return $sensors->Save($data,['id'=>$data['id']]);

    }
    public static function SaveRemoteSensor($data){
        $sensors = TemperatureSensors::GetInstance();
        $data = $sensors->CleanDataSkipId($data);
        $sensor = $sensors->LoadWhere(['remote_id'=>$data['remote_id'],'mac_address'=>$data['mac_address']]);
        if(is_null($sensor)){
            return $sensors->Save($data);
        }
        //print_r($data);
        return $sensors->Save($data,['remote_id'=>$data['remote_id'],'mac_address'=>$data['mac_address']]);
        //print_r($r);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new TemperatureSensors();
}
?>