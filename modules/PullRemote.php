<?php
class PullRemoteSensors {
    /**
     * pull remote sensors from the main hub or the actual devices if this is the main hub
     * @not_implemented pulling from source devices as main hub not implemented
     * @return null|string returns a string of any database errors that occurred
     */
    public static function Sync(){
        Settings::SaveSettingsVar("service::PullRemoteSensors",date("H:i:s"));
        if(Servers::IsHub()){
            // sync using sensor's mac address to get server address
            return null; // not implemented yet
        }
        // sync from the hub not the actual device
        return PullRemoteSensors::SyncFromHub();
    }

    private static function SyncFromHub(){
        $hub = Servers::GetHub();
        $error = "";
        $error .= PullRemoteSensors::SyncTemperatureFromHub($hub);
        return $error;
    }
    private static function SyncTemperatureFromHub($hub){
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
        print_r($data);
        $error = "";
        foreach($data['temperature'] as $temperature){
            // save temperature sensor
            if(isset($temperature['mac_address']) && $temperature['mac_address'] != LocalMac()){
                TemperatureSensors::SaveRemoteSensor($temperature);
                $error .= clsDB::$db_g->get_err();
            }
        }
        return $error;
    }
}
?>