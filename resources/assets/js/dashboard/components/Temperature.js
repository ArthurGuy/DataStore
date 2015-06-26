var Temperature = Vue.extend({
    template: '{{ value }}°C',

    props: ['value']
});
Vue.component('temperature', Temperature);

//Usage - <temperature value="23.1"></temperature>