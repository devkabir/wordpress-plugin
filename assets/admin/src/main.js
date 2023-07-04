import { createApp } from "vue";
import { createMemoryHistory, createRouter } from "vue-router";
import axios from "axios";
import { Notyf } from "notyf";

import "./style.scss";
import App from "./App.vue";
import routes from "./routes";

const notify = new Notyf();
// let domain = window.location.origin;
let domain = "https://wp.dev";
const config = {
  baseURL: domain + "/wp-json/your-plugin-name/v1/",
};
if (import.meta.env.PROD) {
  config.headers = { "X-WP-Nonce": window.your_plugin_name.nonce };
}
const axiosInstance = axios.create(config);

createApp(App)
  .provide("axios", axiosInstance)
  .provide("notify", notify)
  .use(
    createRouter({
      history: createMemoryHistory(),
      routes,
    })
  )
  .mount("#your-plugin-name");
