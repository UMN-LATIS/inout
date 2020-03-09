
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// import fontawesome from '@fortawesome/fontawesome'
// fontawesome.config = {
//   familyPrefix: 'fa'
// }


require('date-input-polyfill')

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import Echo from "laravel-echo"

window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
    // auth: {
    //     headers: {
    //         Authorization: 'Bearer ' + "2b54f921c0e9394855626e3641cb91c4",
    //     },
    // },
});


// Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component('users', require('./components/Users.vue').default);
Vue.component('inoutentry', require('./components/InoutEntry.vue').default);
Vue.component('admin', require('./components/Admin.vue').default);
Vue.component('edituser', require('./components/EditUser.vue').default);
Vue.component('viewuser', require('./components/ViewUser.vue').default);



import PrettyCheckbox from 'pretty-checkbox-vue';
Vue.use(PrettyCheckbox);

import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

const app = new Vue({
    el: '#app'
});
