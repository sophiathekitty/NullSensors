<?php
//if(!defined('main_already_included'))require_once("../../../includes/main.php");
//echo "pull remote sensors";
Services::Start("NullSensors::EveryMinute");

Services::Log("NullSensors::EveryMinute","PullRemoteSensors::Sync");
PullRemoteSensors::Sync();

Services::Complete("NullSensors::EveryMinute");
?>