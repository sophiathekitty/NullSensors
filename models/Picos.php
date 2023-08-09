<?php

/**
 * light instance
 */
class PicoDevices extends clsModel {
    public $table_name = "PicoDevices";
    public $fields = [
        [
            'Field'=>"mac_address",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"name",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>"0"
        ],[
            'Field'=>"url",
            'Type'=>"varchar(20)",
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
            'Field'=>"modified",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>"on update current_timestamp()"
        ],[
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * get the instance
     * @return PicoDevices
     */
    private static function GetInstance(){
        if(is_null(PicoDevices::$instance)) PicoDevices::$instance = new PicoDevices();
        return PicoDevices::$instance;
    }
    /**
     * load pico by mac address
     * @param string $mac_address
     * @return array|null the pico data array or null if none
     */
    public static function MacAddress($mac_address){
        $instance = PicoDevices::GetInstance();
        return $instance->LoadWhere(['mac_address'=>$mac_address]);
    }
    /**
     * load all picos
     * @return array list of picos
     */
    public static function LoadPicos(){
        $instance = PicoDevices::GetInstance();
        return $instance->LoadAll();
    }
    /**
     * save a pico
     * @param array $data the pico data array
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SavePico($data){
        $instance = PicoDevices::GetInstance();
        $data = $instance->CleanData($data);
        $data['modified'] = date("Y-m-d H:i:s");
        $pico = PicoDevices::MacAddress($data['mac_address']);
        if(is_null($pico)){
            return $instance->Save($data);
        }
        return $instance->Save($data,['mac_address'=>$data['mac_address']]);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new PicoDevices();
}
?>