var Vue = require('vue');
var Room = Vue.extend({
    template: '#room-template',

    props: ['data'],

    components: {
        temperature: require('./Temperature'),
        colour: require('./Colour'),
        'colour-patch': require('./ColourPatch'),
        weatherIcon: require('./WeatherIcon')
    },

    data: function() {
        return {
            lightColour: '#cccccc',
            autoShowControl: false,
            heatingShowControl: false,
            fanShowControl: false,
            lightingShowControl: false
        }
    },

    computed: {
        lightColour: function () {

            console.log(this.lighting.value);
            var hsbParts = this.lighting.value.split(',');
            var hslColour = tinycolor("hsl(" + hsbParts[0] + ", " + hsbParts[1] + "%, " + hsbParts[2] + "%)");
            //return '#111111';
            console.log(hslColour.toHexString());
            return hslColour.toHexString();
        }
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

        updateLightingColour: function(newColour) {
            this.lighting.value = newColour;

            //Reset the last timeout
            if(typeof this.lightingColourDebouceTimer == "number") {
                window.clearTimeout(this.lightingColourDebouceTimer);
                delete this.lightingColourDebouceTimer;
            }

            //Set a timeout so the new value gets uploaded in half a second
            var self = this;
            this.lightingColourDebouceTimer = window.setTimeout(function() {
                self.$http.put('/api/device/'+self.lighting.id, {value: self.lighting.value});
            }, 500);

        },

        modeToggle: function() {
            if (this.mode == 'manual') {
                this.mode = 'auto';
            } else {
                this.mode = 'manual';
            }
            this.$http.put('/api/locations/'+this.id, {mode: this.mode});
        },

        autoControlToggle: function() {
            this.autoShowControl = !this.autoShowControl;
        },

        heatingControlToggle: function() {
            this.heatingShowControl = !this.heatingShowControl;
        },

        fanControlToggle: function() {
            this.fanShowControl = !this.fanShowControl;
        },

        lightingControlToggle: function() {
            this.lightingShowControl = !this.lightingShowControl;
        }
    }
});
export default Room;