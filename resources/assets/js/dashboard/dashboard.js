

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
            this.$http.put('/api/device/'+this.heater.id, {state: this.heater.state});
        },

        fanToggle: function() {
            this.fan.state = !this.fan.state;
            this.$http.put('/api/device/'+this.fan.id, {state: this.fan.state});
        },

        modeToggle: function() {
            if (this.mode == 'manual') {
                this.mode = 'auto';
            } else {
                this.mode = 'manual';
            }
            this.$http.put('/api/locations/'+this.id, {mode: this.mode});
        }
    }
});
Vue.component('room', Room);



new Vue({
    el: '#dashboard',

    data: {
        loading: false,
        locationId: null,
        location: {
            home: false
        },
        rooms: [],
        forecastAvailable: false,
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

        //Fetch the location id from the url
        this.locationId = window.location.pathname.substr(window.location.pathname.lastIndexOf('/') + 1);

        //Make an ajax request to get the location
        this.loadData();

        console.log('Location',this.locationId, 'Ready');
    },

    methods: {

        loadLocation: function() {
            this.$http.get('/api/locations/'+this.locationId, function(location) {
                this.location = location;
                this.rooms = location.rooms;

                document.title = this.location.name + ' Dashboard';
            });
        },
        loadForecast: function() {

            this.$http.get('/api/forecast/'+this.locationId, function(forecast) {
                this.forecast = forecast;
                this.forecastAvailable = true;
                this.loading = false; //this is a hack - we need to detect the actual change
            });

        },
        loadData: function() {
            this.loading = true;
            this.loadLocation();
            this.loadForecast();
        },
        refreshData: function() {
            this.loadData();
        }
    }

});