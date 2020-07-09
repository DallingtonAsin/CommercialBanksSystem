/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// import VueAxios from 'vue-axios';
// import axios from 'axios';
// import notification from './components/Notification.vue';

//require('./bootstrap');

// window.Vue = require('vue');
// window.axios = require('axios');


// Vue.use(VueAxios, axios);



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('notification', require('./components/Notification.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    // el: '#notification',
   
    // data:{
    //   notifications: ''
    // },
    // created(){
    //   axios.post('/notifications/get').then(response => {
    //     this.notifications = response.data;
    //   });
    // }
});
