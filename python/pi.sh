#!/bin/bash
echo "-----------------------------------"
echo "waiting to start (2 minutes)"
echo "-----------------------------------"
sleep 2m
echo "-----------------------------------"
echo "starting python"
echo "-----------------------------------"
while true; do
    sudo python3 /var/www/html/plugins/NullSensors/python/piSensors.py &
    wait $!
    sleep 10
done
exit
