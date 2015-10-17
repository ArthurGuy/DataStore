
module.exports = {

    name: 'TempSlider',

    template: '<div class="colour-slider"><input type="range" name="{{name}}" value="{{value}}" max="25" min="15" step="0.5" v-on="input:updateTemp" style="padding: 5px 25px;"></div>',

    props: [
        'value',
        'name',
        {
            name: 'on-update',
            type: Function,
            required: true
        }
    ],

    data: function() {
        return {
            value: 20
        }
    },

    ready: function() {
        console.log(this.value);
    },

    methods: {
        updateTemp: function (e) {
            this.value = e.target.value;

            this.onUpdate(this.value);
        }
    }
}

//Usage - <colour value="#000000" on="change: updateLightingColor"></colour>