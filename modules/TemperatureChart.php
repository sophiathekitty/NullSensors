<?php
/**
 * weather chart... could probably be updated to use the HourlyChart base class
 */
class TemperatureChart extends HourlyChart {

    private static $chart = null;
    /**
     * @return TemperatureChart
     */
    private static function GetInstance(){
        if(is_null(TemperatureChart::$chart)) TemperatureChart::$chart = new TemperatureChart();
        return TemperatureChart::$chart;
    }
    /**
     * generates a indoors hourly chart
     * @return array returns an array containing an hourly temperature chart
     */
    public static function Indoors(){
        $chart = TemperatureChart::GetInstance();
        $data = [];
        $dataGardens = [];
        for($h = 0; $h < 24; $h++){
            $data[$h] = $chart->HourlyAverages(TemperatureLog::LoadInsideTemperatureHour($h),$h,["temp","hum"]);
            $dataGardens[$h] = $chart->HourlyAverages(TemperatureLog::LoadGardenTemperatureHour($h),$h,["temp","hum"]);
        }
        return $chart->Merge($data,$dataGardens,["temp","hum"],0.75);
        return $data;
    }
    /**
     * generates a indoors hourly chart
     * @return array returns an array containing an hourly temperature chart
     */
    public static function AllRooms(){
        $data = [];
        $sensors = TemperatureSensors::LoadSensors();
        $rooms = [];
        foreach($sensors as $sensor){
            if(!isset($rooms[$sensor['room_id']])) $rooms[$sensor['room_id']] = (int)$sensor['room_id'];
        }
        foreach($rooms as $room_id){
            $chart = [];
            $chart['room_id'] = $room_id;
            $chart['temperature'] = TemperatureChart::Room($room_id);
            $data[] = $chart;
        }
        return $data;
    }
    /**
     * generates a indoors hourly chart
     * @return array returns an array containing an hourly temperature chart
     */
    public static function Room($room_id){
        $chart = TemperatureChart::GetInstance();
        $data = [];
        $sensors = TemperatureSensors::LoadRoomSensors($room_id);
        for($h = 0; $h < 24; $h++){
            $logs = [];
            foreach($sensors as $sensor){
                $log = TemperatureLog::LoadTemperatureHourSensor($sensor['id'],$h);
                $logs = array_merge($logs,$log);
            } 
            $data[$h] = $chart->HourlyAverages($logs,$h,["temp","hum"]);
        }
        return $data;
    }
}
?>