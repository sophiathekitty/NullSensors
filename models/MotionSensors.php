<?php
/**
 * motion sensors
 */
class MotionSensors extends clsModel {
    public $table_name = "MotionSensors";
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
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"active",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"1",
            'Extra'=>""
        ]
    ];
    private static $sensors = null;
    private static function GetInstance(){
        if(is_null(MotionSensors::$sensors)) MotionSensors::$sensors = new MotionSensors();
        return MotionSensors::$sensors;
    }
    /**
     * load the sensor for a pico device
     * @param string $mac_address the pico's mac address
     * @return array the sensor data array
     */
    public static function LoadLocalPicoSensor($mac_address){
        $sensors = MotionSensors::GetInstance();
        return $sensors->LoadAllWhere(["mac_address"=>$mac_address]);
    }

    /**
     * load all motion sensors
     * @return array list of motion sensors
     */
    public static function LoadSensors(){
        $sensors = MotionSensors::GetInstance();
        return $sensors->LoadAll();
    }
    /**
     * load a sensor by it's id
     * @param int $id the sensor's id
     * @return array the sensor data array
     */
    public static function LoadSensor($id){
        $sensors = MotionSensors::GetInstance();
        return $sensors->LoadWhere(["id"=>$id]);
    }
    /**
     * save remote sensor data to the database uses remote_id and mac_address to identify sensor and ignores id field
     * @param array $data temperature sensor data array
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SavePicoSensor($data){
        $sensors = MotionSensors::GetInstance();
        $data = $sensors->CleanDataSkipId($data);
        $sensor = $sensors->LoadWhere(['mac_address'=>$data['mac_address'],'remote_id'=>$data['remote_id']]);
        if(is_null($sensor)){
            return $sensors->Save($data);
        }
        return $sensors->Save($data,['mac_address'=>$data['mac_address'],'remote_id'=>$data['remote_id']]);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new MotionSensors();
}
?>