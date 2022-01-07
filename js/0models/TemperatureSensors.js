class TemperatureSensorCollection extends Collection {
    static instance = new TemperatureSensorCollection();
    constructor(){
        super("temperature","temperature","/plugins/NullSensors/api/temperature","/plugins/NullSensors/api/temperature");
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
                if(Number(sensor.room_id) == room_id && sensor.error == "ok"){
                    sensors.temperature.push(sensor);
                }
            });
            callBack(sensors);
        });
    }
}