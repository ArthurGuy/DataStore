window.Promise = window.Promise || require('es6-promise').Promise;

global.jQuery = require('jquery');
require('whatwg-fetch');
require('bootstrap-sass');
var Vue = require('vue');
var vueResource = require('vue-resource');
Vue.use(vueResource);




/////////////////////////////////////////
////// Register the Service Worker //////
/////////////////////////////////////////

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




/////////////////////////////////////////
////////// Vue JS - Room Panel //////////
/////////////////////////////////////////

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




/////////////////////////////////////////
/////////// Vue JS - Dashboard //////////
/////////////////////////////////////////

new Vue({
    el: '#dashboard',

    data: {
        meta: {
            version: null
        },
        latitude: null,
        longitude: null,
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

        if ("geolocation" in navigator) {


        }



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
        loadMeta: function() {

            var that = this;
            return fetch('/api/meta').then(function(response) {
                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' + response.status);
                    return;
                }
                response.json().then(function(meta) {
                    that.meta = meta;
                });
            });

        },
        loadData: function() {
            this.loading = true;
            this.loadMeta();
            this.loadLocation();
            this.loadForecast();
        },
        refreshData: function() {
            this.loadData();
        },
        showConnectionError: function () {
            console.log("Connectivity issue!");
        },
        fetchCordinates: function() {
            var that = this;
            var watchID = navigator.geolocation.watchPosition(function(position) {
                that.latitude = position.coords.latitude;
                that.longitude = position.coords.longitude
            }, function(error) {
                console.log("Error - No position available", error.code, error.message);
            }, {
                enableHighAccuracy: true,
                maximumAge        : 30000
            });
            //navigator.geolocation.clearWatch(watchID);
        }
    }

});