<?php
/**
 * a module for representing a pico and it's associated sensors
 */
class Pico {
    /**
     * load a pico with its sensors
     * @param string $mac_address the mac address of the pico
     * @return array the data array of the pico with sensors
     */
    public static function LoadPico($mac_address){
        $pico = PicoDevices::MacAddress($mac_address);
        Pico::LoadSensors($pico);
        return $pico;
    }
    /**
     * load all pico with their sensors
     * @return array list of data arrays of the pico with sensors
     */
    public static function AllPicos(){
        $picos = PicoDevices::LoadPicos();
        foreach($picos as &$pico) Pico::LoadSensors($pico);
        return $picos;
    }
    /**
     * add the sensors to a pico
     * @param array $pico reference to the pico to add the sensors to
     */
    private static function LoadSensors(&$pico){
        $mac_address = $pico['mac_address'];
        $pico['temperature'] = TemperatureSensors::LoadLocalPicoSensor($mac_address);
        $pico['light'] = LightSensors::LoadLocalPicoSensor($mac_address);
        $pico['motion'] = MotionSensors::LoadLocalPicoSensor($mac_address);
    }
    /**
     * save a pico json object with sensor data
     * @param array $pico the data array for some pico json
     * @return array the data array of the pico with sensors
     */
    public static function SavePico($pico){
        $save = PicoDevices::SavePico($pico);
        if(isset($pico['temperature']) && is_array($pico['temperature']) && count($pico['temperature']) > 0) {
            $save['temperature'] = [];
            foreach($pico['temperature'] as &$sensor){
                $save['temperature'][] = TemperatureSensors::SavePicoSensor($sensor);
            }
        }
        if(isset($pico['light']) && is_array($pico['light']) && count($pico['light']) > 0){
            $save['light'] = [];
            foreach($pico['light'] as &$sensor){
                $save['light'][] = LightSensors::SavePicoSensor($sensor);
            }
        }
        if(isset($pico['motion']) && is_array($pico['motion']) && count($pico['motion']) > 0){
            $save['motion'] = [];
            foreach($pico['motion'] as &$sensor){
                $save['motion'][] = MotionSensors::SavePicoSensor($sensor);
            }
        }
        return $save;
    }
    /**
     * register a pico 
     */
    public static function Register($mac_address){
        $pico = PicoDevices::MacAddress($mac_address);
        if(!is_null($pico)) return $pico;
        $picos = PicoDevices::LoadPicos();
        $data = [
            'mac_address' => $mac_address,
            'url' => $_SERVER['REMOTE_ADDR'],
            'name' => "Pico ".(count($picos)+1)
        ];
        $save = PicoDevices::SavePico($data);
        Debug::Log("Pico::Register",$save);
        return PicoDevices::MacAddress($mac_address);
    }
    /**
     * get the pico hub (might not be main hub while doing development stuff)
     */
    public static function MainHub(){
        $main = Servers::GetMain();
        if($main['type'] == "old_hub"){
            $hubs = Servers::GetAllHubs();
            foreach($hubs as $hub){
                if($hub['name'] == "dev") return $hub;
            }
        }
        return $main;
    }
}
?>