<?php
/**
 * temperature sensors
 */
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
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"room_id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"gpio",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"serial",
            'Type'=>"tinyint(1)",
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
    private static function GetInstance():TemperatureSensors{
        if(is_null(TemperatureSensors::$sensors)) TemperatureSensors::$sensors = new TemperatureSensors();
        return TemperatureSensors::$sensors;
    }
    /**
     * load sensor with $sensor_id
     * @param int $sensor_id the sensor's id
     * @return array|null the sensor's data array or null if it doesn't exist
     */
    public static function LoadSensorId($sensor_id){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadWhere(['id'=>$sensor_id]);
    }
    /**
     * load a local sensor by $gpio pin number
     * @param int $gpio the gpio pin number for the sensor
     * @return array|null the sensor's data array or null if it doesn't exist
     */
    public static function LoadLocalSensor($gpio){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadWhere(['gpio'=>$gpio,'mac_address'=>LocalMac()]);
    }
    /**
     * load all the temperature sensors
     * @return array list of temperature sensors
     */
    public static function LoadSensors(){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadAll();
    }
    /**
     * load only the working sensors ["error"=>"ok"]
     * @return array list of temperature sensors
     */
    public static function LoadWorkingSensors(){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadAllWhere(["error"=>"ok"]);
    }
    /**
     * load only the local sensors ["mac_address"=>LocalMac()]
     * @return array list of temperature sensors
     */
    public static function LoadLocalSensors(){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadAllWhere(["mac_address"=>LocalMac()]);
    }
    /**
     * load only the local sensors run on the pi `["serial"=>"0","mac_address"=>LocalMac()]`
     * @return array list of temperature sensors
     */
    public static function LoadLocalPiSensors(){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadAllWhere(["serial"=>"0","mac_address"=>LocalMac()]);
    }
    /**
     * load only the local sensors run on a connected arduino `["serial"=>"1","mac_address"=>LocalMac()]`
     * @return array list of temperature sensors
     */
    public static function LoadLocalArduinoSensors(){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadAllWhere(["serial"=>"1","mac_address"=>LocalMac()]);
    }
    /**
     * load only sensors in a specific room
     * @param int $room_id the room's id
     * @return array list of temperature sensors
     */
    public static function LoadRoomSensors($room_id){
        $sensors = TemperatureSensors::GetInstance();
        return $sensors->LoadAllWhere(['room_id'=>$room_id]);
    }
    /**
     * save local sensor data to the database uses id field to identify sensor
     * @notice use `TemperatureSensors::SaveRemoteSensor($data)` for remote sensors
     * @param array $data temperature sensor data array
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveSensor($data){
        $sensors = TemperatureSensors::GetInstance();
        $data = $sensors->CleanData($data);
        if(!isset($data['id']) || is_null($sensors->LoadWhere(['id'=>$data['id']]))){
            return $sensors->Save($data);
        }
        return $sensors->Save($data,['id'=>$data['id']]);
    }
    /**
     * save remote sensor data to the database uses remote_id and mac_address to identify sensor and ignores id field
     * @param array $data temperature sensor data array
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
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