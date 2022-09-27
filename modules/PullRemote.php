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
        if(Servers::IsHub() || defined("practice_sync_from_device")){
            // sync using sensor's mac address to get server address
            Services::Log("NullSensors::PullRemoteSensors","Syncing as Hub not implimented");
            PullRemoteSensors::SyncFromDevice();
            Services::Complete("NullSensors::PullRemoteSensors");
            return null; // not implemented yet
        }
        // sync from the hub not the actual device
        $res = PullRemoteSensors::SyncFromHub();
        Services::Complete("NullSensors::PullRemoteSensors");
        return $res;
    }

    private static function SyncFromDevice(){
        Services::Log("NullSensors::PullRemoteSensors","SyncingFromDevice");
        $error = "";
        $sensors = TemperatureSensors::LoadSensors();
        $local_mac = LocalMac();
        foreach($sensors as $sensor){
            if($sensor['mac_address'] != $local_mac){
                // ok lets pull some sensor data
                $server = Servers::ServerMacAddress($sensor['mac_address']);
                $api = "/plugins/NullSensors/api/temperature/?id=".$sensor['remote_id'];
                if($server['type'] == "grow_manager") $api = "/api/garden/json/?garden_id=".$sensor['garden_id'];
                $data = ServerRequests::LoadRemoteJSON($sensor['mac_address'],$api);
                
                if($data && isset($data['garden'])){
                    Services::Log("NullSensors::PullRemoteSensors","SyncingFromDevice::Garden Temp ".$data['garden']['current']['temp']['temp']);
                    Debug::Log("NullSensors::PullRemoteSensors::SyncingFromDevice",$data);
                    
                    $sensor['temp'] = $data['garden']['current']['temp']['temp'];
                    $sensor['temp_max'] = $data['garden']['current']['temp']['max'];
                    $sensor['temp_min'] = $data['garden']['current']['temp']['min'];
                    $sensor['hum'] = $data['garden']['current']['hum']['hum'];
                    $sensor['hum_max'] = $data['garden']['current']['hum']['max'];
                    $sensor['hum_min'] = $data['garden']['current']['hum']['min'];
                    $sensor['error'] = $data['garden']['error'];
                    if($data['garden']['error'] == "ok" && ($data['garden']['current']['temp']['temp'] > 200 || $data['garden']['current']['hum']['hum'] > 100)){
                        $sensor['error'] = "invalid";
                    }
                    $res = TemperatureSensors::SaveRemoteSensor($sensor);
                    Debug::Log($res);
                    $error .= clsDB::$db_g->get_err();
                    $sensor['sensor_id'] = $sensor['id'];
                    TemperatureLog::LogSensor($sensor);
                } else if($data && isset($data['sensor'])){
                    Services::Log("NullSensors::PullRemoteSensors","SyncingFromDevice::Null Temp ".$data['sensor']['temp']);
                    Debug::Log("NullSensors::PullRemoteSensors::SyncingFromDevice",$data);
                    $sensor = ['id'=>$sensor['id']];
                    $sensor['temp'] = $data['sensor']['temp'];
                    $sensor['temp_max'] = $data['sensor']['temp_max'];
                    $sensor['temp_min'] = $data['sensor']['temp_min'];
                    $sensor['hum'] = $data['sensor']['hum'];
                    $sensor['hum_max'] = $data['sensor']['hum_max'];
                    $sensor['hum_min'] = $data['sensor']['hum_min'];
                    $sensor['error'] = $data['sensor']['error'];
                    if(($sensor['temp'] > 200 || $sensor['hum'] > 100)){
                        $sensor['error'] = "invalid";
                    }
                    $res = TemperatureSensors::SaveRemoteSensor($sensor);
                    Debug::Log($res);
                    $error .= clsDB::$db_g->get_err();
                    $sensor['sensor_id'] = $sensor['id'];
                    TemperatureLog::LogSensor($sensor);
                } else {
                    Services::Log("NullSensors::PullRemoteSensors","SyncingFromDevice::Offline");
                    $sensor = [];
                    $sensor['error'] = "Offline";
                    TemperatureSensors::SaveRemoteSensor($sensor);
                }
            }
        }
        return $error;
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
        if($hub['type'] == "old_hub"){
            //$url = "http://".$hub['url']."/api/temperature/?all=1";
            $url = "/api/temperature/?all=1";
        } else {
            //$url = "http://".$hub['url']."/plugins/NullSensors/api/temperature/";
            $url = "/plugins/NullSensors/api/temperature/";
        }
        $room_id = Settings::LoadSettingsVar('room_id');
        if($room_id) $url .= "?room_id=$room_id";
        // load data
        //$info = file_get_contents($url);
        //$data = json_decode($info,true);
        Services::Log("NullSensors::PullRemoteSensors","SyncTemperatureFromHub::api::".$url);
        $data = ServerRequests::LoadHubJSON($url);
        Debug::Log("PullRemoteSensors::SyncTemperatureFromHub",$data);
        $error = "";
        foreach($data['temperature'] as $temperature){
            // save temperature sensor
            if(isset($temperature['mac_address']) && $temperature['mac_address'] != LocalMac()){
                Services::Log("NullSensors::PullRemoteSensors","SyncTemperatureFromHub::sensor::".$temperature['mac_address']." [".$temperature['temp']."] ".$temperature['name']);
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