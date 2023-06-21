import { createApp } from 'vue';
import './style.scss';
import App from './App.vue';
import routes from './routes';
import { createMemoryHistory, createRouter } from 'vue-router';
import axios from 'axios';

const config = {
    baseURL: window.location.origin + '/wp-json/your-plugin-name/v1/',
};
if (import.meta.env.PROD) {
    config.headers = { 'X-WP-Nonce': window.your_plugin_name.nonce };
}
const axiosInstance = axios.create(config);
createApp(App)
    .provide('axios', axiosInstance)
    .use(
        createRouter(
            {
                history: createMemoryHistory(),
                routes,
            }
        )
    )
    .mount('#your-plugin-name');
