<?php
class PullRemoteSensors {
    public static function Sync(){
        if(Servers::IsHub()){
            // sync using sensor's mac address to get server address
            return null; // not implemented yet
        }
        // sync from the hub not the actual device
        return PullRemoteSensors::SyncFromHub();
    }

    private static function SyncFromHub(){
        $hub = Servers::GetHub();
        $url = "http://".$hub['url']."/api/temperature/";
        $room_id = Settings::LoadSettingsVar('room_id');
        if($room_id) $url .= "?room_id=$room_id";
        // load data
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        foreach($data['temperature'] as $temperature){
            // save temperature sensor
            TemperatureSensors::SaveRemoteSensor($temperature);
            echo clsDB::$db_g->get_err();
        }
    }
}
?>