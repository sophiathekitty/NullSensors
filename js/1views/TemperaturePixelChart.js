class TemperaturePixelChart extends View {
    constructor(debug = IndoorTemperatureHourlyChart.debug_temperature){
        super(IndoorTemperatureHourlyChart.instance,
            new Template("indoor_pixel","/plugins/NullSensors/templates/charts/pixel.html"),
            null,
            90000,debug);
            this.pallet = ColorPallet.getPallet("weather");
    }
    buildWeather(){
        if(this.debug) console.log("TemperaturePixelChart::Build",$("#indoors_weather_stamp").length);
        if($("#indoors_weather_stamp").length > 0){
            if(this.template){
                this.template.getData(html=>{
                    if($("#indoors_weather_stamp .temp_chart.indoors.simple").length == 0) $(html).appendTo("#indoors_weather_stamp");
                    this.displayWeather();
                });
            }
        }
    }
    refreshWeather(){
        if(this.debug) console.log("TemperaturePixelChart::Refresh",$("#indoors_weather_stamp .temp_chart.indoors.simple").length);
        if($("#indoors_weather_stamp .temp_chart.indoors.simple").length == 0){
            this.buildWeather();
        } else {
            this.displayWeather();
        }
    }
    displayWeather(){
        if(this.debug) console.log("TemperaturePixelChart::Display");
        if(this.model){
            this.model.getData(json=>{
                if(this.debug) console.log("TemperaturePixelChart::Display-json",json);
                json.temperature.forEach(hour=>{
                    if(this.debug) console.log("TemperaturePixelChart::Display-hour",hour);
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
                        //$("#indoors_weather_stamp .temp_chart.indoors.simple [hour="+hour.hour+"]").css("background-color",color);
                        //$("#indoors_weather_stamp .temp_chart.indoors.simple [hour="+hour.hour+"]").attr("title","Indoors -- "+hours+am+"\nTemp: "+Math.round(hour.temp)+"° | "+Math.round(hour.temp_max)+"° / "+Math.round(hour.temp_min)+"°\nHum: "+Math.round(hour.hum)+"% | "+Math.round(hour.hum_max)+"% / "+Math.round(hour.hum_min)+"%");
                        $(".clock .temp_chart.indoors.simple [hour="+hour.hour+"]").css("background-color",color);
                        $(".clock .temp_chart.indoors.simple [hour="+hour.hour+"]").attr("title","Indoors -- "+hours+am+"\nTemp: "+Math.round(hour.temp)+"° | "+Math.round(hour.temp_max)+"° / "+Math.round(hour.temp_min)+"°\nHum: "+Math.round(hour.hum)+"% | "+Math.round(hour.hum_max)+"% / "+Math.round(hour.hum_min)+"%");
                    });

                });
            });
        }
    }
    build(room_id){
        //if(this.debug) console.log("TemperaturePixelChart::Build(room_id)",room_id);
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
        //if(this.debug) console.log("TemperaturePixelChart::Refresh(room_id)",room_id);
        this.display(room_id);
    }
    display(room_id){
        //if(this.debug) console.log("TemperaturePixelChart::Display(room_id)",room_id);
        if(this.model){
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
                        $("[room_id="+room_id+"] .temp_chart.simple [hour="+hour.hour+"]").css("background-color",color);
                        $("[room_id="+room_id+"] .temp_chart.simple [hour="+hour.hour+"]").attr("title","Indoors -- "+hours+am+"\nTemp: "+Math.round(hour.temp)+"° | "+Math.round(hour.temp_max)+"° / "+Math.round(hour.temp_min)+"°\nHum: "+Math.round(hour.hum)+"% | "+Math.round(hour.hum_max)+"% / "+Math.round(hour.hum_min)+"%");
                    });

                });
            });
        }
    }
}