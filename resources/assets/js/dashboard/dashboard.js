
var Room = Vue.extend({
    template: '#room-template',

    props: ['data'],

    data: function() {
        return {}
    },

    ready: function() {
        Vue.config.debug = true;
        $('[data-toggle="tooltip"]').tooltip();

        console.log('Room',this.id, 'Ready');
    },

    methods: {

        heaterToggle: function() {
            this.heater.state = !this.heater.state;
            this.$http.put('/device/'+this.heater.id, {state: this.heater.state});
        },

        modeToggle: function() {
            if (this.mode == 'manual') {
                this.mode = 'auto';
            } else {
                this.mode = 'manual';
            }
            this.$http.put('/locations/'+this.id, {mode: this.mode});
        }
    }
});
Vue.component('room', Room);


new Vue({
    el: '#dashboard',

    props: ['location'],

    data: {

        rooms: [],
        forecast: {
            temperature: 12.6,
            humidity: 89,
            duePoint: 10.4,
            condition: "Very Comfortable",
            futureForecast: {
                time: 1435287600,
                summary: "Clear",
                icon: "clear-night",
                precipIntensity: 0,
                precipProbability: 0,
                temperature: 53.91,
                apparentTemperature: 53.91,
                dewPoint: 50.94,
                humidity: 0.9,
                windSpeed: 2.23,
                windBearing: 222,
                visibility: 10,
                cloudCover: 0.13,
                pressure: 1018.97,
                ozone: 336.13
            }
        }

    },

    ready: function() {
        Vue.config.debug = true;
        this.loadRooms();
        this.loadForecast();

        console.log('Location',this.location, 'Ready');
    },

    methods: {

        loadRooms: function() {

            this.$http.get('/locations/'+this.location+'/rooms', function(rooms) {
                this.rooms = rooms;
            });
        },
        loadForecast: function() {

            this.$http.get('/forecast/'+this.location, function(forecast) {
                this.forecast = forecast;
            });
        }
    }

});