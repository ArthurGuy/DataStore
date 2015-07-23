window.Promise = window.Promise || require('es6-promise').Promise;

global.jQuery = require('jquery');
require('whatwg-fetch');
require('bootstrap-sass');
var Vue = require('vue');
var vueResource = require('vue-resource');
Vue.use(vueResource);
var moment = require('moment');
var tinycolor = require("tinycolor2");

var isPushEnabled = false;



/////////////////////////////////////////
////// Register the Service Worker //////
/////////////////////////////////////////

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js')
        .then(initialiseNotifications)
        .then(function(registration) {
        // Registration was successful
        console.log('ServiceWorker registration successful:');


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

    var pushButton = document.querySelector('.js-push-button');
    pushButton.addEventListener('click', function() {
        if (isPushEnabled) {
            unsubscribe();
        } else {
            subscribe();
        }
    });
}




/////////////////////////////////////////
////////// Vue JS - Room Panel //////////
/////////////////////////////////////////

Vue.config.debug = true;

Vue.filter('simple-date', function (value) {
    return value.format('D/M/YY, H:mm:ss');
});

var Room = require("./components/Room");
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

        //console.log('Location',this.locationId, 'Ready');

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
                    //console.log(location.rooms[0]);
                    that.location = location;

                    //On first load add the rooms
                    if (that.rooms.length == 0) {
                        that.rooms = location.rooms;
                    } else {
                        //Loop through the rooms and update the bits fo data one at a time
                        // this wont break the dom and any interaction currently in progress
                        for(var i in that.rooms) {
                            for(var key in that.rooms[i]) {
                                if (typeof location.rooms[i][key] !== 'undefined') {
                                    that.rooms[i][key] = location.rooms[i][key];
                                }
                            }
                        }
                    }

                    //that.rooms.push(location.rooms[0]);

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



/////////////////////////////////////////
/////// Notification Registration ///////
/////////////////////////////////////////

function initialiseNotifications() {

    console.log("Initialising Notifications");

    // Are Notifications supported in the service worker?
    if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
        console.warn('Notifications aren\'t supported.');
        return;
    }

    // Check the current Notification permission.
    // If its denied, it's a permanent block until the
    // user changes the permission
    if (Notification.permission === 'denied') {
        console.warn('The user has blocked notifications.');
        return;
    }

    // Check if push messaging is supported
    if (!('PushManager' in window)) {
        console.warn('Push messaging isn\'t supported.');
        return;
    }

    // We need the service worker registration to check for a subscription
    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        // Do we already have a push message subscription?
        serviceWorkerRegistration.pushManager.getSubscription()
            .then(function(subscription) {
                // Enable any UI which subscribes / unsubscribes from
                // push messages.
                var pushButton = document.querySelector('.js-push-button');
                pushButton.disabled = false;

                if (!subscription) {
                    // We aren't subscribed to push, so set UI
                    // to allow the user to enable push
                    return;
                }

                // Keep your server in sync with the latest subscriptionId
                sendSubscriptionToServer(subscription);

                // Set your UI to show they have subscribed for
                // push messages
                pushButton.textContent = 'Disable Push Messages';
                isPushEnabled = true;
            })
            .catch(function(err) {
                console.warn('Error during getSubscription()', err);
            });
    });
}

function sendSubscriptionToServer(subscription) {
    console.log('subscription to send to the server', subscription);

    fetch('/api/notification', {
        method: 'post',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            endpoint: subscription.endpoint,
            building_id: window.location.pathname.substr(window.location.pathname.lastIndexOf('/') + 1)
        })
    })
        .then(function() { console.log("Notification details sent"); })
        .catch(function(error) {console.log("Error posting notification details", error);});
}

function removeSubscriptionFromServer(subscription) {
    console.log('subscription to delete from the server', subscription);

    fetch('/api/notification', {
        method: 'delete',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            endpoint: subscription.endpoint,
            building_id: window.location.pathname.substr(window.location.pathname.lastIndexOf('/') + 1)
        })
    })
        .then(function() { console.log("Notification detail delete message sent"); })
        .catch(function(error) {console.log("Error deleting notification details", error);});
}

function subscribe() {
    // Disable the button so it can't be changed while
    // we process the permission request
    var pushButton = document.querySelector('.js-push-button');
    pushButton.disabled = true;

    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        serviceWorkerRegistration.pushManager.subscribe({userVisibleOnly: true})
            .then(function(subscription) {
                // The subscription was successful
                isPushEnabled = true;
                pushButton.textContent = 'Disable Push Messages';
                pushButton.disabled = false;

                // TODO: Send the subscription subscription.endpoint
                // to your server and save it to send a push message
                // at a later date
                return sendSubscriptionToServer(subscription);
            })
            .catch(function(e) {
                if (Notification.permission === 'denied') {
                    // The user denied the notification permission which
                    // means we failed to subscribe and the user will need
                    // to manually change the notification permission to
                    // subscribe to push messages
                    console.log('Permission for Notifications was denied');
                    pushButton.disabled = true;
                } else {
                    // A problem occurred with the subscription, this can
                    // often be down to an issue or lack of the gcm_sender_id
                    // and / or gcm_user_visible_only
                    console.log('Unable to subscribe to push.', e);
                    pushButton.disabled = false;
                    pushButton.textContent = 'Enable Push Messages';
                }
            });
    });
}

function unsubscribe() {
    var pushButton = document.querySelector('.js-push-button');
    pushButton.disabled = true;

    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
        // To unsubscribe from push messaging, you need get the
        // subscription object, which you can call unsubscribe() on.
        serviceWorkerRegistration.pushManager.getSubscription().then(
            function(subscription) {
                // Check we have a subscription to unsubscribe
                if (!subscription) {
                    // No subscription object, so set the state
                    // to allow the user to subscribe to push
                    isPushEnabled = false;
                    pushButton.disabled = false;
                    pushButton.textContent = 'Enable Push Messages';
                    return;
                }

                removeSubscriptionFromServer(subscription);

                // We have a subscription, so call unsubscribe on it
                subscription.unsubscribe().then(function(successful) {
                    pushButton.disabled = false;
                    pushButton.textContent = 'Enable Push Messages';
                    isPushEnabled = false;
                }).catch(function(e) {
                    // We failed to unsubscribe, this can lead to
                    // an unusual state, so may be best to remove
                    // the users data from your data store and
                    // inform the user that you have done so

                    console.log('Unsubscription error: ', e);
                    pushButton.disabled = false;
                    pushButton.textContent = 'Enable Push Messages';
                });
            }).catch(function(e) {
                console.error('Error thrown while unsubscribing from push messaging.', e);
            });
    });
}