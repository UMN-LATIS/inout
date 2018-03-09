
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'a93fe5d596909fc211e3',
    cluster: 'mt1',
    encrypted: true
});

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

Vue.component('users', require('./components/Users.vue'));
Vue.component('inoutentry', require('./components/InoutEntry.vue'));
Vue.component('admin', require('./components/Admin.vue'));
Vue.component('edituser', require('./components/EditUser.vue'));
Vue.component('viewuser', require('./components/ViewUser.vue'));

import PrettyCheckbox from 'pretty-checkbox-vue';
Vue.use(PrettyCheckbox);

import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

const app = new Vue({
    el: '#app'
});
