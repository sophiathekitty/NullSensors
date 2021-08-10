import time
import board
import adafruit_dht

import urllib.request
import json

#Initial the dht device, with data pin connected to:
dhtDevice = adafruit_dht.DHT11(board.D17)
error_count = 0
while True:
    try:
        # Print the values to the serial port
        temperature_c = dhtDevice.temperature
        temperature_f = temperature_c * (9 / 5) + 32
        humidity = dhtDevice.humidity
        print("Temp: {:.1f} F / {:.1f} C    Humidity: {}% "
            .format(temperature_f, temperature_c, humidity))
        error_count = 0
        with urllib.request.urlopen("http://localhost/plugins/NullSensors/api/temperature?gpio=17&temperature={}&humidity={}".format(temperature_f,humidity)) as json_url:
            buf = json_url.read()
            #data = json.loads(buf.decode('utf-8'))
            #print(buf.decode('utf-8'))
    except RuntimeError as error:     # Errors happen fairly often, DHT's are hard to read, just keep going
        print(error.args[0])
        error_count = error_count + 1
        if(error_count > 100):
            with urllib.request.urlopen("http://localhost/plugins/NullSensors/api/temperature?gpio=17&error={}".format(error.args[0])) as json_url:
                buf = json_url.read()
                data = json.loads(buf.decode('utf-8'))
            
    time.sleep(2.0)