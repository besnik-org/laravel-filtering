import {createApp} from 'vue'

import App from './App.vue'
import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


if (document.getElementById('vueApp')) {

    const app = createApp(App)

    app.mount('#vueApp')

}