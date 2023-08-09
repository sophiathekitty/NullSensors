<?php
define('SensorsPlugin',true);
/**
 * light sensors
 */
class LightSensors extends clsModel {
    public $table_name = "LightSensors";
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
            'Field'=>"level",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"max",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"min",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"day_threshold",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"100",
            'Extra'=>""
        ]
    ];
    private static $sensors = null;
    private static function GetInstance(){
        if(is_null(LightSensors::$sensors)) LightSensors::$sensors = new LightSensors();
        return LightSensors::$sensors;
    }
    /**
     * load the sensor for a pico device
     * @param string $mac_address the pico's mac address
     * @return array the sensor data array
     */
    public static function LoadLocalPicoSensor($mac_address){
        $sensors = LightSensors::GetInstance();
        return $sensors->LoadAllWhere(["mac_address"=>$mac_address]);
    }
    /**
     * load the sensors for a specific room
     * @param int $room_id the room id
     * @return array list of sensors
     */
    public static function LoadRoomSensors($room_id){
        $sensors = LightSensors::GetInstance();
        return $sensors->LoadAllWhere(["room_id"=>$room_id]);
    }
    /**
     * load all light sensors
     * @return array list of light sensors
     */
    public static function LoadSensors(){
        $sensors = LightSensors::GetInstance();
        return $sensors->LoadAll();
    }
    /**
     * save remote sensor data to the database uses remote_id and mac_address to identify sensor and ignores id field
     * @param array $data temperature sensor data array
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SavePicoSensor($data){
        $sensors = LightSensors::GetInstance();
        $data = $sensors->CleanDataSkipId($data);
        $sensor = $sensors->LoadWhere(['mac_address'=>$data['mac_address'],'remote_id'=>$data['remote_id']]);
        if(is_null($sensor)){
            return $sensors->Save($data);
        }
        return $sensors->Save($data,['mac_address'=>$data['mac_address'],'remote_id'=>$data['remote_id']]);
    }
    /**
     * load a sensor by id
     * @param int $id the sensor id
     * @return array the sensor data array
     */
    public static function LoadSensor($id){
        $sensors = LightSensors::GetInstance();
        return $sensors->LoadWhere(['id'=>$id]);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new LightSensors();
}
?>