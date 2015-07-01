window.Promise = window.Promise || require('es6-promise').Promise;

require('whatwg-fetch');
var Vue = require('vue');
var vueResource = require('vue-resource');
Vue.use(vueResource);
global.jQuery = require('jquery');
require('bootstrap-sass');




if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
        // Registration was successful
        console.log('ServiceWorker registration successful:', registration);


        //navigator.serviceWorker.controller.postMessage('hello');

        if (0) {
            registration.unregister().then(function (boolean) {
                // if boolean = true, unregister is successful
                if (boolean) {
                    console.log("Service worker unregistered");
                }
            });
        }

    }).catch(function(err) {
        // registration failed :(
        console.log('ServiceWorker registration failed: ', err);
    });
}



Vue.config.debug = true;

var Room = Vue.extend({
    template: '#room-template',

    props: ['data'],

    components: {
        temperature: require('./components/Temperature'),
        weatherIcon: require('./components/WeatherIcon')
    },

    data: function() {
        return {}
    },

    ready: function() {
        jQuery('[data-toggle="tooltip"]').tooltip();

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

    components: {
        temperature: require('./components/Temperature'),
        'weather-icon': require('./components/WeatherIcon')
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

            var opts = {credentials: 'include'};
            var that = this;
            return fetch('/api/locations/'+this.locationId, opts).then(function(response) {

                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' + response.status);
                    return;
                }

                response.json().then(function(location) {
                    //console.log(location);
                    that.location = location;
                    that.rooms = location.rooms;
                    document.title = that.location.name + ' Dashboard';
                });

            }).catch(that.showConnectionError);//.then(hideSpinner);

        },
        loadForecast: function() {

            var opts = {credentials: 'include'};
            var that = this;
            return fetch('/api/forecast/'+this.locationId, opts).then(function(response) {

                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' + response.status);
                    return;
                }

                response.json().then(function(forecast) {
                    that.forecast = forecast;
                    that.forecastAvailable = true;
                    that.loading = false; //this is a hack - we need to detect the actual change
                });

            }).catch(that.showConnectionError);//.then(hideSpinner);


        },
        loadData: function() {
            this.loading = true;
            this.loadLocation();
            this.loadForecast();
        },
        refreshData: function() {
            this.loadData();
        },
        showConnectionError: function () {
            console.log("Connectivity issue!");
        }
    }

});