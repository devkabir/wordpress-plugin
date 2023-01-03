import {createApp} from 'vue'
import './style.scss'
import App from './App.vue'
import router from "./routes";
const app = createApp(App)
app.use(router)
app.mount('#your-plugin-name')
