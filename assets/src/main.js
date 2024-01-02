import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import routes from './routes'

import { createMemoryHistory, createRouter } from 'vue-router'

const app = createApp(App)

app.use(createPinia())
app.use(
  createRouter({
    history: createMemoryHistory(import.meta.env.BASE_URL),
    routes
  })
)

app.mount('#your-plugin-name')
