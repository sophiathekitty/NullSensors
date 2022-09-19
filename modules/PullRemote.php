<?php
class PullRemoteSensors {
    /**
     * pull remote sensors from the main hub or the actual devices if this is the main hub
     * @todo pulling from source devices as main hub not implemented
     * @return null|string returns a string of any database errors that occurred
     */
    public static function Sync(){
        Services::Start("NullSensors::PullRemoteSensors");
        //Settings::SaveSettingsVar("service::PullRemoteSensors",date("H:i:s"));
        if(Servers::IsHub()){
            // sync using sensor's mac address to get server address
            Services::Log("NullSensors::PullRemoteSensors","Syncing as Hub not implimented");
            Services::Complete("NullSensors::PullRemoteSensors");
            return null; // not implemented yet
        }
        // sync from the hub not the actual device
        $res = PullRemoteSensors::SyncFromHub();
        Services::Complete("NullSensors::PullRemoteSensors");
        return $res;
    }

    private static function SyncFromHub(){
        Services::Log("NullSensors::PullRemoteSensors","SyncingFromHub");
        $hub = Servers::GetHub();
        $error = "";
        $error .= PullRemoteSensors::SyncTemperatureFromHub($hub);
        Services::Log("NullSensors::PullRemoteSensors","SyncingFromHub done... $error");
        return $error;
    }
    private static function SyncTemperatureFromHub($hub){
        Services::Log("NullSensors::PullRemoteSensors","SyncTemperatureFromHub");
        if($hub['type'] == "old_hub")
            $url = "http://".$hub['url']."/api/temperature/?all=1";
        else {
            $url = "http://".$hub['url']."/plugins/NullWeather/api/temperature/";
        }
        $room_id = Settings::LoadSettingsVar('room_id');
        if($room_id) $url .= "?room_id=$room_id";
        // load data
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        Debug::Log("PullRemoteSensors::SyncTemperatureFromHub",$data);
        $error = "";
        foreach($data['temperature'] as $temperature){
            // save temperature sensor
            if(isset($temperature['mac_address']) && $temperature['mac_address'] != LocalMac()){
                TemperatureSensors::SaveRemoteSensor($temperature);
                $error .= clsDB::$db_g->get_err();
                $temperature['sensor_id'] = $temperature['id'];
                TemperatureLog::LogSensor($temperature);
            }
        }
        return $error;
    }
}
?>