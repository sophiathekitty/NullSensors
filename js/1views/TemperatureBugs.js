class TemperatureBug extends View {
    constructor(debug = RoomTemperatureModel.debug_temperature){
        super(new RoomTemperatureModel(),null, new Template("temp_bug","/plugins/NullSensors/templates/bugs/temperature.html"),60000, debug);
        this.pallet = ColorPallet.getPallet("weather");
        this.pixelChart = new TemperaturePixelChart();
        this.barChart = new TemperatureBarChart();
    }
    build(){
        if(this.debug) console.warn("TemperatureBug::Build","missing room id");
    }
    display(){
        if(this.debug) console.warn("TemperatureBug::Display","missing room id");
    }
    refresh(){
        if(this.debug) console.warn("TemperatureBug::Refresh","missing room id");
    }
    build(room_id){
        if(this.debug) console.log("TemperatureBug::Build",room_id);
        this.item_template.getData(html=>{
            if(this.debug) console.log("TemperatureBug::Build",room_id,"template",html);
            this.model.roomTemperature(room_id,json=>{
                if(this.debug) console.log("TemperatureBug::Build",room_id,"sensors",json);
                if(json.room != null){
                    $(html).appendTo("[room_id="+room_id+"] .sensors");
                    this.display(room_id);
                }
            });    
        });
        if(this.pixelChart) this.pixelChart.build(room_id);
        if(this.barChart) this.barChart.build(room_id);
    }
    display(room_id){
        if(this.debug) console.info("TemperatureBug::Display",room_id);
        this.model.roomTemperature(room_id,json=>{
            if(this.debug) console.log("TemperatureBug::Display",room_id,"sensors",json);
            if(json.room != null){
                /*
                var temp = 0;
                var count = 0;
                var hum = 0;
                var max_hum = 0;
                var temp_max = 0;
                var hum_min = 99999;
                var temp_min = 99999;
                json.temperature.forEach(sensor=>{
                    temp += Number(sensor.temp);
                    hum += Number(sensor.hum);
                    if(temp_max < Number(sensor.temp_max)) temp_max = Number(sensor.temp_max);
                    if(max_hum < Number(sensor.hum_max)) max_hum = Number(sensor.hum_max);
                    if(temp_min > Number(sensor.temp_min)) temp_min = Number(sensor.temp_min);
                    if(hum_min > Number(sensor.hum_min)) hum_min = Number(sensor.hum_min);
                    count++;
                    if(this.debug) console.log("TemperatureBug::Display",room_id,"foreach",temp,sensor.temp);
                });
                if(this.debug) console.log("TempBug::Display",temp,count,Math.round(temp/count))
                temp = Math.round(temp/count);
                hum = Math.round(hum/count);
                */
                if(this.debug) console.log("TemperatureBug::Display",room_id,json.room);
                $("[room_id="+room_id+"] .sensors [var=temp]").html(Math.round(json.room.temp));
                $("[room_id="+room_id+"] .sensors [var=temp]").attr("unit","fahrenheit");
                this.pallet.getColorLerp("temp",json.room.temp,color=>{
                    $("[room_id="+room_id+"] .sensors [var=temp]").css("color",color);
                });
                var tool_tip = "Temp: "+(Math.round(json.room.temp*10)/10)+"° | "+(Math.round(json.room.temp_max*10)/10)+"° / "+(Math.round(json.room.temp_min*10)/10)+"°\nHum: "+(Math.round(json.room.hum*10)/10)+"% | "+(Math.round(json.room.hum_max*10)/10)+"% / "+(Math.round(json.room.hum_min*10)/10)+"%";
                if("garden" in json.room){
                    tool_tip += "\n\nGarden\nTemp: "+(Math.round(json.room.garden.temp*10)/10)+"° | "+(Math.round(json.room.garden.temp_max*10)/10)+"° / "+(Math.round(json.room.garden.temp_min*10)/10)+"°\nHum: "+(Math.round(json.room.garden.hum*10)/10)+"% | "+(Math.round(json.room.garden.hum_max*10)/10)+"% / "+(Math.round(json.room.garden.hum_min*10)/10)+"%";
                }
                $("[room_id="+room_id+"] .sensors [var=temp]").attr("title",tool_tip);

            } else {
                $("[room_id="+room_id+"] .sensors [var=temp]").attr("unit","e");
            }
        });
        if(this.pixelChart) this.pixelChart.display(room_id);
        if(this.barChart) this.barChart.display(room_id);
    }
    refresh(room_id){
        if(this.debug) console.info("TemperatureBug::Refresh",room_id);
        this.display(room_id);
    }
}