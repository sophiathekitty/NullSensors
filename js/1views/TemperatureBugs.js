class TemperatureBug extends View {
    constructor(debug = TemperatureSensorCollection.debug_temperature){
        super(new TemperatureSensorCollection(),null, new Template("temp_bug","/plugins/NullSensors/templates/bugs/temperature.html"),60000, debug);
        this.pallet = ColorPallet.getPallet("weather");
        this.pixelChart = new TemperaturePixelChart();
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
            this.model.roomSensors(room_id,json=>{
                if(this.debug) console.log("TemperatureBug::Build",room_id,"sensors",json);
                if(json.temperature.length > 0){
                    $(html).appendTo("[room_id="+room_id+"] .sensors");
                    this.display(room_id);
                }
            });    
        });
        if(this.pixelChart) this.pixelChart.build(room_id);
    }
    display(room_id){
        if(this.debug) console.log("TemperatureBug::Display",room_id);
        this.model.roomSensors(room_id,json=>{
            if(this.debug) console.log("TemperatureBug::Display",room_id,"sensors",json);
            if(json.temperature.length > 0){
                var temp = 0;
                var count = 0;
                json.temperature.forEach(sensor=>{
                    temp += Number(sensor.temp);
                    count++;
                    if(this.debug) console.log("TemperatureBug::Display",room_id,"foreach",temp,sensor.temp);
                });
                if(this.debug) console.log("TempBug::Display",temp,count,Math.round(temp/count))
                temp = Math.round(temp/count);
                if(this.debug) console.log("TemperatureBug::Display",room_id,"temp",temp);
                $("[room_id="+room_id+"] .sensors [var=temp]").html(temp);
                $("[room_id="+room_id+"] .sensors [var=temp]").attr("unit","fahrenheit");
                this.pallet.getColorLerp("temp",temp,color=>{
                    $("[room_id="+room_id+"] .sensors [var=temp]").css("color",color);
                });

            } else {
                $("[room_id="+room_id+"] .sensors [var=temp]").attr("unit","e");
            }
        });
        if(this.pixelChart) this.pixelChart.display(room_id);
    }
    refresh(room_id){
        if(this.debug) console.log("TemperatureBug::Refresh",room_id);
        this.display(room_id);
    }
}