class RoomTemperatureModel extends Model {
    static instance = new RoomTemperatureModel();
    static debug_temperature = false;
    constructor(debug = RoomTemperatureModel.debug_temperature){
        super("temperature","/plugins/NullSensors/api/temperature/room/","/plugins/NullSensors/api/temperature");
        this.debug = debug;
    }
    /**
     * gets the room sensors
     * @param {int} room_id 
     * @param {function(JSON)} callBack 
     */
    roomTemperature(room_id,callBack){
        this.get_params = "?room_id="+room_id;
        if(this.debug) console.info("RoomTemperatureModel::roomTemperature - room_id:",room_id,this.get_url+this.get_params)
        this.pullData(json=>{
            if(this.debug) console.log("RoomTemperatureModel::roomTemperature - json:",json);
            callBack(json);
        });
    }
}