
var WeatherIcon = Vue.extend({
    template: '<canvas id="random1" width="{{ width }}" height="{{ height }}"></canvas>',

    props: ['icon', 'width', 'height'],

    data: function() {
        return {
            rendered: false
        }
    },

    ready: function() {
        console.log("WeatherIcon Ready", this.icon);
        this.skycons = new Skycons({"color": "black"});

    },

    watch: {
        icon: function(val, oldVal) {
            console.log('new: %s, old: %s', val, oldVal);
            this.updateIcon();
        }
    },

    methods: {
        updateIcon: function() {
            if (this.rendered) {
                console.log("WeatherIcon Updated");
                this.skycons.set("random1", this.icon);
            } else {
                console.log('WeatherIcon First Render')
                this.skycons.add("random1", this.icon);
                this.skycons.play();
                this.rendered = true;
            }
        }
    }
});
Vue.component('weather-icon', WeatherIcon);