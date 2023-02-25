import {createApp} from 'vue'
import './style.scss'
import App from './App.vue'
import routes from "./routes";
import {createMemoryHistory, createRouter} from "vue-router";
import axios from "axios";

const config = {
  baseURL: import.meta.env.VITE_API_DOMAIN,
};
if (import.meta.env.PROD){
  config.headers =  {'X-WP-Nonce': window.your_plugin_name.nonce2}
}
const axiosInstance = axios.create(config);
createApp(App)
    .provide('axios', axiosInstance)
    .use(createRouter({
      history: createMemoryHistory(),
      routes,
    }))
    .mount('#your-plugin-name')
