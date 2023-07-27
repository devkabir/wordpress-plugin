// Import necessary dependencies
import { createApp } from "vue";
import { createMemoryHistory, createRouter } from "vue-router";
import axios from "axios";
import { Notyf } from "notyf";

// Import styles
import "./style.scss";

// // Import the main App component
import App from "./App.vue";

// Import the routes configuration
import routes from "./routes";

// Retrieve the values of `nonce` and `api_endpoint` from the global `window` object
const { nonce, api_endpoint } = window.your_plugin_name;

// Create a new instance of the `Notyf` class for displaying notifications
const notify = new Notyf();

// Define a configuration object for axios requests
const config = {
  baseURL: api_endpoint,
};

// Add an `X-WP-Nonce` header to axios requests in production mode
if (import.meta.env.MODE === "production") {
  config.headers = { "X-WP-Nonce": nonce };
}

// Create an axios instance with the provided configuration
const axiosInstance = axios.create(config);

// Create a new Vue app instance
createApp(App)
  // Provide the axios instance and notify object to all components in the app
  .provide("axios", axiosInstance)
  .provide("notify", notify)
  // Create and configure the Vue Router instance
  .use(
    createRouter({
      history: createMemoryHistory(),
      routes,
    })
  )
  // Mount the app to the DOM element with the ID "your-plugin-name"
  .mount("#your-plugin-name");
