<pre></pre><?php
require_once("../../../includes/main.php");
$sensor = TemperatureSensors::LoadLocalSensor(17);
TemperatureLogger::DoLog($sensor);
?></pre>