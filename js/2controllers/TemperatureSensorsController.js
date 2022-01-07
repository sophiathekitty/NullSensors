class TemperatureSensorController extends Controller {
    //static instance = new TemperatureSensorController();
    constructor(){
        super(new TemperatureSensorList());
    }
    ready(){
        this.view.build();
        this.refreshInterval(60000*View.refresh_ratio);
    }
    refresh(){
        View.refresh_ratio += 0.001;
        this.view.display();
    }
}