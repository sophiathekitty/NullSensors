class TemperatureSensorCollection extends Collection {
    static instance = new TemperatureSensorCollection();
    static debug_temperature = false;
    constructor(debug = TemperatureSensorCollection.debug_temperature){
        super("temperature","temperature","/plugins/NullSensors/api/temperature?working=1","/plugins/NullSensors/api/temperature");
    }
    /**
     * gets the room sensors
     * @param {int} room_id 
     * @param {function(JSON)} callBack 
     */
    roomSensors(room_id,callBack){
        this.getData(json=>{
            var sensors = {'temperature':[]};
            json.temperature.forEach(sensor=>{
                if(Number(sensor.room_id) == room_id){
                    sensors.temperature.push(sensor);
                }
            });
            callBack(sensors);
        });
    }
}