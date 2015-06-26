
var Room = Vue.extend({
    template: '#room-template',

    props: ['data'],

    data: function() {
        return {}
    },

    ready: function() {
        Vue.config.debug = true;
        $('[data-toggle="tooltip"]').tooltip();

        console.log('Room',this.id, 'Ready');
    },

    methods: {

        heaterToggle: function() {
            if (this.heater.state == '1') {
                this.heater.state = '0';
            } else {
                this.heater.state = '1';
            }
            this.$http.put('/device/'+this.heater.id, {state: this.heater.state});
        },

        modeToggle: function() {
            if (this.mode == 'manual') {
                this.mode = 'auto';
            } else {
                this.mode = 'manual';
            }
            this.$http.put('/locations/'+this.id, {mode: this.mode});
        }
    }
});
Vue.component('room', Room);


new Vue({
    el: '#dashboard',

    props: ['location'],

    data: {

        rooms: []

    },

    ready: function() {
        Vue.config.debug = true;
        this.loadRooms();
        console.log('Location',this.location, 'Ready');
    },

    methods: {

        loadRooms: function() {

            this.$http.get('/locations/'+this.location+'/rooms', function(rooms) {
                this.rooms = rooms;
            });
        }
    }

});