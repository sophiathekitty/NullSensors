<?php
require_once("../../../../includes/main.php");
$data = [];
if(isset($_GET['mac_address'])){
    $data['pico'] = Pico::LoadPico($_GET['mac_address']);
} else {
    $data['picos'] = Pico::AllPicos();
}
OutputJson($data);
?>