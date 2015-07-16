
module.exports = {

    name: 'ColourPatch',

    template: '<div style="background-color:hsla({{hue}}, 100%, 50%, {{saturation / 100}}); width:20px; height:20px; display:inline-block;"></div>',

    props: [
        'raw-colour',
    ],

    data: function() {
        return {
            hue: 20,
            saturation: 100,
            brightness: 100
        }
    },

    ready: function() {
        this.updateColour();
        this.$watch('rawColour', function () {
            this.updateColour();
        })
    },

    methods: {
        updateColour: function() {
            var hsbParts = this.rawColour.split(',');
            this.hue = hsbParts[0];
            this.saturation = hsbParts[1];
            this.brightness = hsbParts[2];
        }
    }
}

//Usage - <colour value="#000000" on="change: updateLightingColor"></colour>