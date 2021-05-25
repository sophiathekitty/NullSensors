# NullSensors
 a plugin i'm making for my null hub stuff to handle sensors running on an arduino. i'm going to focus on getting this to handle syncing sensor data from the current hub. eventually i'll see about adding the arduino sketches and see if i can figure out how to push updates to the arduino from the raspberry pi

## clone repo

```bash
cd \var\www\html\plugins
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
