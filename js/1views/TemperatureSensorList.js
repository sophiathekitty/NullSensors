class TemperatureSensorList extends View {
    constructor(debug = false){
        super(TemperatureSensorCollection.instance,null,new Template("dht11_item","/plugins/NullSensors/templates/items/dht11.html"),90000,debug);
    }
    build(){
        this.display();
    }
    refresh(){
        this.display();
    }
    display(){
        if(this.model && this.item_template){
            this.item_template.getData(html=>{
                this.model.getData(json=>{
                    $("ul[collection=temperature]").html("");
                    json.temperature.forEach((sensor,index)=>{
                        $(html).appendTo("ul[collection=temperature]").attr("index",index);
                        $("ul[collection=temperature] [index="+index+"]").attr("remote_id",sensor.remote_id);
                        $("ul[collection=temperature] [index="+index+"]").attr("mac_address",sensor.mac_address);
                        $("ul[collection=temperature] [index="+index+"]").attr("error",sensor.error);
                        $("ul[collection=temperature] [index="+index+"] [var=name]").html(sensor.name);
                        if(sensor.error == "ok"){
                            $("ul[collection=temperature] [index="+index+"] [var=temp]").html(sensor.temp);
                            $("ul[collection=temperature] [index="+index+"] [var=temp_max]").html(sensor.temp_max);
                            $("ul[collection=temperature] [index="+index+"] [var=temp_min]").html(sensor.temp_min);
                            $("ul[collection=temperature] [index="+index+"] [var=hum]").html(sensor.hum);
                            $("ul[collection=temperature] [index="+index+"] [var=hum_max]").html(sensor.hum_max);
                            $("ul[collection=temperature] [index="+index+"] [var=hum_min]").html(sensor.hum_min);    
                        } else {
                            $("ul[collection=temperature] [index="+index+"] [var=temp]").html(sensor.error);
                        }
                    });
                });
            });
        }
    }
}