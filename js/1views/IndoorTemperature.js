/**
 * indoor temperature view
 */
class IndoorTemperatureView extends View {
    constructor(debug = true){
        super(new IndoorTemperatureModel(),
        new Template("indoor_temperature","/plugins/NullSensors/templates/bugs/indoors.html"),
        null ,60000, debug);
        this.pallet = ColorPallet.getPallet("weather");
    }
    build(){
        if(this.debug) console.log("IndoorTemperatureView::Build");
        if($("#indoors_weather_stamp .temp_chart.indoors.simple").length > 0){
            if(this.template){
                this.template.getData(html=>{
                    if($("#indoors_weather_stamp [model=indoor_temperature]").length == 0) $(html).appendTo("#indoors_weather_stamp");
                    this.display();
                });
            }
        }
    }
    display(){
        if(this.debug) console.log("IndoorTemperatureView::Display");
        if(this.model){
            this.model.getData(json=>{
                $("#indoors_weather_stamp [model=indoor_temperature] [var=temp]").html(json.temperature.temp);
                $("#indoors_weather_stamp [model=indoor_temperature] [var=hum]").html(json.temperature.hum);
                this.pallet.getColorLerp("temp",json.temperature.temp,color=>{
                    $("#indoors_weather_stamp [model=indoor_temperature] [var=temp]").css("color",color);
                });
                this.pallet.getColorLerp("hum",json.temperature.hum,color=>{
                    $("#indoors_weather_stamp [model=indoor_temperature] [var=hum]").css("color",color);
                });

            })
        }
    }
    refresh(){
        if(this.debug) console.log("IndoorTemperatureView::Refresh");
        if($("#indoors_weather_stamp [model=indoor_temperature]").length == 0) {
            this.build();
        } else {
            this.display();
        }
    }
}