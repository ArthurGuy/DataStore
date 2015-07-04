
var Skycons = require('./../vendor/Skycons')(global);


module.exports = {
    template: '<canvas id="{{ canvasId }}" width="{{ width }}" height="{{ height }}"></canvas>',

    props: ['icon', 'width', 'height'],

    data: function() {
        return {
            rendered: false,
            canvasId: 'hello',
            icon: null,
            Skycons: new Skycons({monochrome:false})
        }
    },

    ready: function() {
        //console.log("WeatherIcon Ready", this.icon);
        this.canvasId = 'WeatherIcon-' + (Math.random() + 1).toString(36).substring(7);

        //This will ensure the new id is set before trying to render
        this.$nextTick(function () {
            this.updateIcon();
        })
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
                this.Skycons.set(this.canvasId, this.icon);
            } else {
                //console.log('WeatherIcon First Render', this.canvasId, this.icon);
                this.Skycons.add(this.canvasId, this.icon);
                this.Skycons.play();
                this.rendered = true;
            }
        }
    }
};

//Usage - <weather-icon width="200" height="200" icon="sleet"></weather-icon>