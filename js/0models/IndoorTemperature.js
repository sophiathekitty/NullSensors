class IndoorTemperatureHourlyChart extends HourlyChart {
    static instance = new IndoorTemperatureHourlyChart();
    static debug_temperature = true;
    constructor(debug = IndoorTemperatureHourlyChart.debug_temperature){
        super("temperature_logs","temperature","temperature_chart","/plugins/NullSensors/api/temperature/logs",debug);
    }
    /**
     * gets the room chart data
     * @param {int} room_id 
     * @param {function(JSON)} callBack 
     */
    room(room_id,callBack){
        this.getData(json=>{
            json.rooms.forEach(room=>{
                if(Number(room.room_id) == room_id){
                    callBack(room);
                }
            });
        });
    }
}