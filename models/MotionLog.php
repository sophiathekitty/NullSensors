<?php 
/**
 * a model for representing a motion sensor
 */
class MotionLog extends clsModel {
    public $table_name = "MotionLog";
    public $fields = [
        [
            'Field'=>"guid",
            'Type'=>"varchar(40)",
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
            'Extra'=>""
        ],[
            'Field'=>"level",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * get the instance of this model
     * @return MotionLog
     */
    private static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MotionLog();
        }
        return self::$instance;
    }
    /**
     * get the last motion log for a sensor
     * @param int $sensor_id
     * @return array
     */
    public static function getLastLog($sensor_id) {
        $instance = self::getInstance();
        return $instance->LoadWhere(['sensor_id'=>$sensor_id],['created'=>'DESC']);
    }
    /**
     * save a new log from a motion sensor data array
     * @param array $data
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function saveLog($data) {
        $instance = self::getInstance();
        if(!isset($data['sensor_id']) && isset($data['log_delay'])){
            $data['sensor_id'] = $data['id'];
        }
        if(!isset($data['keep_time']) || !isset($data['log_delay'])){
            $sensor = MotionSensors::LoadSensor($data['sensor_id']);
            $data['keep_time'] = $sensor['keep_time'];
            $data['log_delay'] = $sensor['log_delay'];
        }
        $log = MotionLog::getLastLog($data['sensor_id']);
        if(time() - strtotime($log['created']) < $data['log_delay']) return ["no_log"=>time() - strtotime($log['created'])];
        $data = $instance->CleanDataSkipFields($data,['id','created']);
        $data['guid'] = $instance->MakeGUID($data,"sensor_id",'created');
        $data['created'] = date('Y-m-d H:i:s');
        $instance->PruneField('created',DaysToSeconds($data['keep_time']));
        return $instance->Save($data);
    }
    /**
     * get the logs for a sensor
     * @param int $sensor_id
     * @return array
     */
    public static function getLogs($sensor_id) {
        $instance = self::getInstance();
        return $instance->LoadAllWhere(['sensor_id'=>$sensor_id]);
    }
    /**
     * get the motion logs for an hour of the day
     * @param int $hour
     * @return array
     */
    public static function getLogsByHour($hour) {
        $instance = self::getInstance();
        return $instance->LoadFieldHour('created',$hour);
    }
    /**
     * load motion from hour of day
     * @param int $h the hour to load
     * @return array array of motion data for the specified hour of the day
     */
    public static function LoadHourSensor($sensor_id,$h){
        $instance = self::GetInstance();
        return $instance->LoadFieldHourWhere("`sensor_id` = '$sensor_id'",'created',$h);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new MotionLog();
}
?>