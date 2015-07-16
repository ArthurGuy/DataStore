
module.exports = {
    template: '<input type="range" name="{{name}}" value="{{hue}}" max="360" min="0" step="1" v-on="change: updateRaw, input:updateHue" style="padding: 5px 25px;">' +
    '<input type="range" value="{{saturation}}" max="100" min="0" step="1" v-on="change: updateRaw, input:updateSaturation" style="padding: 5px 25px;">' +
    '<div style="background-color:hsla({{hue}}, 100%, 50%, {{saturation / 100}}); width:20px; height:20px;"></div>',

    props: [
        'raw-colour',
        'name',
        {
            name: 'on-update',
            type: Function,
            required: true
        }
    ],

    data: function() {
        return {
            hue: 20,
            saturation: 100,
            brightness: 100
        }
    },

    ready: function() {
        console.log(this.rawColour);
        var hsbParts = this.rawColour.split(',');
        this.hue = hsbParts[0];
        this.saturation = hsbParts[1];
        this.brightness = hsbParts[2];
    },

    methods: {
        updateHue: function (e) {
            this.hue = e.target.value;

            //this.updateRaw();
        },

        updateSaturation: function (e) {
            this.saturation = e.target.value;

            //this.updateRaw();
        },

        updateRaw: function () {
            this.rawColour = this.hue + ',' + this.saturation + ',' + this.brightness;

            this.onUpdate(this.rawColour);
        }
    }
}

//Usage - <colour value="#000000" on="change: updateLightingColor"></colour>