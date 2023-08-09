<?php
require_once("../../../../../includes/main.php");
$data = [];
if(isset($_GET['mac_address'])){
    // register the pico and it's sensors
    $data['pico'] = Pico::Register($_GET['mac_address']);
} else {
    $requestParts = explode('/', $_SERVER['REQUEST_URI']);
    if($requestParts[6] != ""){
        $data['pico'] = Pico::Register($requestParts[6]);
    }    
}
$data['hub'] = Pico::MainHub();
$data['servers'] = Servers::OnlineServers();
OutputJson($data);
?>