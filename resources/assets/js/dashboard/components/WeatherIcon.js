
var Skycons = require('skycons')(global);

module.exports = {
    template: '<canvas id="{{ id }}" width="{{ width }}" height="{{ height }}"></canvas>',

    props: ['icon', 'width', 'height'],

    data: function() {
        return {
            rendered: false,
            id: null
        }
    },

    ready: function() {
        //console.log("WeatherIcon Ready", this.icon);

        this.skycons = new Skycons();
        this.id = 'WeatherIcon-' + (Math.random() + 1).toString(36).substring(7);
    },

    watch: {
        icon: function(val, oldVal) {
            //console.log('new: %s, old: %s', val, oldVal);
            this.updateIcon();
        }
    },

    methods: {
        updateIcon: function() {
            if (this.rendered) {
                //console.log("WeatherIcon Updated");
                this.skycons.set(this.id, this.icon);
            } else {
                //console.log('WeatherIcon First Render')
                this.skycons.add(this.id, this.icon);
                this.skycons.play();
                this.rendered = true;
            }
        }
    }
};

//Usage - <weather-icon width="200" height="200" icon="sleet"></weather-icon>