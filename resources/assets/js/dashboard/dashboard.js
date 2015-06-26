
Vue.config.debug = true;

var Room = Vue.extend({
    template: '#room-template',

    props: ['data'],

    data: function() {
        return {}
    },

    ready: function() {
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

var Temperature = Vue.extend({
    template: '{{ value }}°C',

    props: ['value']
});
Vue.component('temperature', Temperature);



new Vue({
    el: '#dashboard',

    props: ['location'],

    data: {

        rooms: [],
        forecast: {
            temperature: 0,
            humidity: 0,
            duePoint: 0,
            condition: null,
            futureForecast: {
                time: 1435287600,
                summary: null,
                icon: null,
                precipIntensity: 0,
                precipProbability: 0,
                temperature: 0,
                apparentTemperature: 0,
                dewPoint: 0,
                humidity: 0,
                windSpeed: 0,
                windBearing: 0,
                visibility: 0,
                cloudCover: 0,
                pressure: 0,
                ozone: 0
            },
            dayWeather: {
                dayMaxTemperature: 0,
                dayMinTemperature: 0,
                daySummary: null
            }
        }

    },

    ready: function() {
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