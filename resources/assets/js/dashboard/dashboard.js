
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
        this.loadLocation();

        console.log('Location',this.locationId, 'Ready');
    },

    methods: {

        loadLocation: function() {
            this.$http.get('/locations/'+this.locationId, function(rooms) {
                this.location = rooms;

                document.title = this.location.name + ' Dashboard';

                this.loadData();
            });
        },
        loadRooms: function() {
            this.$http.get('/locations/'+this.locationId+'/rooms', function(rooms) {
                this.rooms = rooms;
                //this.$set("rooms", rooms);
            });
        },
        loadForecast: function() {

            this.$http.get('/forecast/'+this.locationId, function(forecast) {
                this.forecast = forecast;
                this.forecastAvailable = true;
                this.loading = false; //this is a hack - we need to detect the actual change
            });

        },
        loadData: function() {
            this.loading = true;
            this.loadRooms();       //this can be fetched through the location lookup
            this.loadForecast();
        },
        refreshData: function() {
            this.loadData();
        }
    }

});