window.Promise = window.Promise || require('es6-promise').Promise;

global.jQuery = require('jquery');
require('whatwg-fetch');
require('bootstrap-sass');
var Vue = require('vue');
var vueResource = require('vue-resource');
Vue.use(vueResource);
var moment = require('moment');




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

Vue.filter('simple-date', function (value) {
    return value.format('D/M/YY, H:mm:ss');
});

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

        this.$data.home = this.$parent.$data.location.home;

        //console.log('Room',this.id, 'Ready');
    },

    methods: {

        heaterToggle: function() {
            this.heater.on = !this.heater.on;
            this.$http.put('/api/device/'+this.heater.id, {on: this.heater.on});
        },

        fanToggle: function() {
            this.fan.on = !this.fan.on;
            this.$http.put('/api/device/'+this.fan.id, {on: this.fan.on});
        },

        lightingToggle: function() {
            this.lighting.on = !this.lighting.on;
            this.$http.put('/api/device/'+this.lighting.id, {on: this.lighting.on});
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
        localLocation: false,
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
        },
        messageText: null,
        showMessage: false,
        appLoaded: false,
        lastDataUpdate: moment()

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

        //Load the forecast once, this is in case we don't get a location
        this.loadForecast();

        //Start updating the forecast based on the device location
        if ("geolocation" in navigator) {
            this.fetchCordinates();
        }

        //refresh the data every x seconds
        window.setInterval(this.refreshData, 30000);

        console.log('Location',this.locationId, 'Ready');

        this.appLoaded = true;

    },

    methods: {

        loadLocation: function() {

            var opts = {credentials: 'include'};
            var that = this;
            return fetch('/api/locations/'+this.locationId, opts).then(function(response) {

                if (response.status !== 200) {
                    this.displayMessage("Error loading location data", 3000);
                    console.log('Looks like there was a problem. Status Code: ' + response.status);
                    return;
                }

                response.json().then(function(location) {
                    //console.log(location);
                    that.location = location;
                    that.rooms = location.rooms;
                    document.title = that.location.name + ' Dashboard';

                    //update the last updated time
                    that.lastDataUpdate = moment();

                    that.loading = false; //this is a hack - we need to detect the actual change
                });

            }).catch(that.showConnectionError);//.then(hideSpinner);

        },

        loadForecast: function() {

            var forecastUrl;
            if (this.localLocation) {
                forecastUrl = '/api/forecast/'+this.latitude+'/'+this.longitude;
            } else {
                forecastUrl  = '/api/forecast/'+this.locationId;
            }
            var opts = {credentials: 'include'};
            var that = this;
            return fetch(forecastUrl, opts).then(function(response) {

                if (response.status !== 200) {
                    this.displayMessage("Error loading forecast", 3000);
                    console.log('Looks like there was a problem. Status Code: ' + response.status);
                    return;
                }

                response.json().then(function(forecast) {
                    that.forecast = forecast;
                    that.forecastAvailable = true;
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
        },

        refreshData: function() {
            this.loadData();
        },

        showConnectionError: function () {
            this.displayMessage("Connectivity Issue", 3000);
            console.log("Connectivity issue!");
            this.loading = false;
        },

        fetchCordinates: function() {
            var that = this;
            var watchID = navigator.geolocation.watchPosition(function(position) {
                that.latitude = Math.round(position.coords.latitude * 10000000) / 10000000;
                that.longitude = Math.round(position.coords.longitude * 10000000) / 10000000;
                that.localLocation = true;
                that.loadForecast();

            }, function(error) {
                that.displayMessage("No position available", 3000);
                console.log("Error - No position available", error.code, error.message);
            }, {
                enableHighAccuracy: true,
                maximumAge        : 30000
            });
            //navigator.geolocation.clearWatch(watchID);
        },

        displayMessage: function (msg, duration) {
            this.messageText = msg;
            this.showMessage = true;
            //msgEl.style.display = 'block';
            //msgEl.offsetWidth;
            var that = this;
            setTimeout(function() {
                that.showMessage = false;
            }, duration);
        }
    }

});