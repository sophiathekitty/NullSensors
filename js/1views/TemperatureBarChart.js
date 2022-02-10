class TemperatureBarChart extends View {
    constructor(debug = IndoorTemperatureHourlyChart.debug_temperature){
        super(IndoorTemperatureHourlyChart.instance,
            new Template("dht11_item","/plugins/NullSensors/templates/charts/bar.html"),
            null,
            90000,debug);
            this.pallet = ColorPallet.getPallet("weather");
    }
    build(){
        if(this.debug) console.warn("TemperatureBarChart::Build","missing room id");
    }
    refresh(){
        if(this.debug) console.warn("TemperatureBarChart::Refresh","missing room id");
    }
    display(){
        if(this.debug) console.warn("TemperatureBarChart::Display","missing room id");
    }
    build(room_id){
        if(this.template){
            this.template.getData(html=>{
                this.model.room(room_id,json=>{
                    $(html).appendTo("#floors [room_id="+room_id+"] .charts").attr("room_id",room_id);
                    this.display(room_id);
                });
            });
        }
    }
    refresh(room_id){
        this.display(room_id);
    }
    display(room_id){
        if(this.model){
            this.mapper = new ReMapper(-10,120);
            this.model.room(room_id,json=>{
                json.temperature.forEach(hour=>{
                    this.pallet.getColorLerp("temp",hour.temp,color=>{
                        var hours = Number(hour.hour);
                        var am = "am";
                        if(hours > 12){
                            am = "pm";
                            hours -= 12;
                        }
                        if(hours == 12){
                            am = "pm";
                        }
                        if(hours == 0){
                            hours = 12;
                        }
                        $("[room_id="+room_id+"] .temp_chart [hour="+hour.hour+"] [var=temp]").css("top",this.mapper.max_mapper(hour.temp_max+1)+"%");
                        $("[room_id="+room_id+"] .temp_chart [hour="+hour.hour+"] [var=temp]").css("bottom",this.mapper.min_mapper(hour.temp_min-1)+"%");
                        $("[room_id="+room_id+"] .temp_chart [hour="+hour.hour+"] [var=temp]").css("background-color",color);
                        $("[room_id="+room_id+"] .temp_chart [hour="+hour.hour+"] [var=temp]").attr("title","Indoors -- "+hours+am+"\nTemp: "+Math.round(hour.temp)+"° | "+Math.round(hour.temp_max)+"° / "+Math.round(hour.temp_min)+"°\nHum: "+Math.round(hour.hum)+"% | "+Math.round(hour.hum_max)+"% / "+Math.round(hour.hum_min)+"%");
                    });

                });
            });
        }
    }
}