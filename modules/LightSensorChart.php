<?php
/**
 * light sensors pixel chart stuff
 */
class LightSensorChart extends HourlyChart{
    private static $instance = null;
    /**
     * @return LightSensorChart
     */
    private static function GetInstance(){
        if(is_null(LightSensorChart::$instance)) LightSensorChart::$instance = new LightSensorChart();
        return LightSensorChart::$instance;
    }
    /**
     * generates a hourly charts for all rooms
     * @return array returns an array containing an hourly temperature chart
     */
    public static function AllRooms(){
        $data = [];
        $sensors = LightSensors::LoadSensors();
        $rooms = [];
        foreach($sensors as $sensor){
            if(!isset($rooms[$sensor['room_id']])) $rooms[$sensor['room_id']] = (int)$sensor['room_id'];
        }
        foreach($rooms as $room_id){
            $chart = [];
            $chart['room_id'] = $room_id;
            $chart['temperature'] = self::Room($room_id);
            $data[] = $chart;
        }
        return $data;
    }
    /**
     * generates a indoors hourly chart
     * @return array returns an array containing an hourly temperature chart
     */
    public static function Room($room_id){
        $chart = self::GetInstance();
        $data = [];
        $sensors = LightSensors::LoadRoomSensors($room_id);
        for($h = 0; $h < 24; $h++){
            $logs = [];
            foreach($sensors as $sensor){
                $log = LightSensorLogs::LoadHourSensor($sensor['id'],$h);
                $logs = array_merge($logs,$log);
            } 
            $data[$h] = $chart->HourlyAverages($logs,$h,["level"]);
        }
        return $data;
    }
    /**
     * calculate the average light level during daytime
     * @return int
     */
    public static function DaytimeAverage(){
        $logs = LightSensorLogs::getAll();
        $sunrise = Settings::LoadSettingsVar("sunrise","08:00");
        $sunset = Settings::LoadSettingsVar("sunset","20:00");
        $level = 0;
        $count = 0;
        foreach($logs as $log){
            $h = date("H",strtotime($log['created']));
            if($h >= $sunrise && $h <= $sunset){
                $level += $log['level'];
                $count++;
            }
        }
        if($count == 0) return 0;
        return $level/$count;
    }
    /**
     * calculate the average light level during nighttime
     * @return int
     */
    public static function NighttimeAverage(){
        $logs = LightSensorLogs::getAll();
        $sunrise = Settings::LoadSettingsVar("sunrise","08:00");
        $sunset = Settings::LoadSettingsVar("sunset","20:00");
        $level = 0;
        $count = 0;
        foreach($logs as $log){
            $h = date("H",strtotime($log['created']));
            if($h < $sunrise || $h > $sunset){
                $level += $log['level'];
                $count++;
            }
        }
        if($count == 0) return 0;
        return $level/$count;
    }
    /**
     * calculate the average light level during daytime for a specific room
     * @param int $room_id the room id
     * @return int
     */
    public static function RoomDaytimeAverage($room_id){
        $sensors = LightSensors::LoadRoomSensors($room_id);
        $logs = [];
        foreach($sensors as $sensor){
            $log = LightSensorLogs::getSensorLogs($sensor['id']);
            $logs = array_merge($logs,$log);
        }
        $sunrise = Settings::LoadSettingsVar("sunrise","08:00");
        $sunset = Settings::LoadSettingsVar("sunset","20:00");
        $level = 0;
        $count = 0;
        foreach($logs as $log){
            $h = date("H",strtotime($log['created']));
            if($h >= $sunrise && $h <= $sunset){
                $level += $log['level'];
                $count++;
            }
        }
        if($count == 0) return 0;
        return $level/$count;
    }
    /**
     * calculate the average light level during nighttime for a specific room
     * @param int $room_id the room id
     * @return int
     */
    public static function RoomNighttimeAverage($room_id){
        $sensors = LightSensors::LoadRoomSensors($room_id);
        $logs = [];
        foreach($sensors as $sensor){
            $log = LightSensorLogs::getSensorLogs($sensor['id']);
            $logs = array_merge($logs,$log);
        }
        $sunrise = Settings::LoadSettingsVar("sunrise","08:00");
        $sunset = Settings::LoadSettingsVar("sunset","20:00");
        $level = 0;
        $count = 0;
        foreach($logs as $log){
            $h = date("H",strtotime($log['created']));
            if($h < $sunrise || $h > $sunset){
                $level += $log['level'];
                $count++;
            }
        }
        if($count == 0) return 0;
        return $level/$count;
    }
}
?>