# NullSensors

a plugin i'm making for my null hub stuff to handle sensors running on an arduino. i'm going to focus on getting this to handle syncing sensor data from the current hub. eventually i'll see about adding the arduino sketches and see if i can figure out how to push updates to the arduino from the raspberry pi

## setup pi stuff for sensors on pi gpio

i'm still working on this... i think these are all the commands i used to install all the stuff i needed. i don't know if these include extras i don't really nead. as of writing this i only have tested with a dht11 sensor. i used this [tutorial](https://peppe8o.com/using-raspberry-pi-with-dht11-temperature-and-humidity-sensor-and-python/#:~:text=Step-by-Step%20Procedure%201%20Wiring%20Diagram.%20Note%20that%20DHT11,Install%20Python%20Libraries.%20...%205%20Setup%20CircuitPython-DHT.%20). and this might help with the [pinout](https://pinout.xyz/pinout/pin11_gpio17). i have it working wired to gpio 17 (sixth pin from top on left)

### Individual commands

```bash
sudo apt install -y python-smbus
```

```bash
sudo apt install -y i2c-tools
```

```bash
pip3 install RPI.GPIO
```

```bash
pip3 install adafruit-blinka
```

```bash
pip3 install adafruit-circuitpython-dht
```

```bash
sudo apt install libgpiod2
```

## Raspbarry Pi Config

```bash
sudo raspbi-config
```

Under **Interfacing Options** enable ***I2C*** and ***SPI***

## clone repo

```bash
cd /var/www/html/plugins
```

```bash
git clone https://github.com/sophiathekitty/NullSensors.git
```

### setup cron job

```bash
sudo crontab -e
```

```Apache config
3 * * * * sh /var/www/html/plugins/NullSensors/gitpull.sh
```
