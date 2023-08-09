<?php
require_once("../../../../../includes/main.php");
$data = [];
/* sample data
data = {
    'mac_address': mac_address
    'temperature': {
        'mac_address': mac_address
        'temp': 70,
        'temp_min': 70,
        'temp_max': 70,
        'hum': 15,
        'hum_min': 15,
        'hum_max': 15
    },
    'light': {
        'mac_address': mac_address
        'level': 70,
        'min': 70,
        'max': 70
    },
    'motion': {
        'mac_address': mac_address
        'level': 70
    }
}
# Send the data to the API
response = urequests.post(api_url, json=data)
*/
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $json_data = file_get_contents('php://input');
    
    // Decode the JSON data into a PHP associative array
    $pico = json_decode($json_data, true);
    // Ensure the JSON data is valid
    if (json_last_error() === JSON_ERROR_NONE) {
        $data['save'] = Pico::SavePico($pico);
    } else {
        JsonError("Invalid JSON data.",400);
    }
} else {
    JsonError("Method not allowed.",405);
}
OutputJson($data);
?>