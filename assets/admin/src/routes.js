import {createMemoryHistory, createRouter, createWebHashHistory} from "vue-router";

const Dashboard = () => import( './pages/Settings.vue');
const Logs = () => import( './pages/Logs.vue');
const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createMemoryHistory(),
    routes: [
        {
            path: '/',
            name: 'Dashboard',
            component: Dashboard
        },
        {
            path: '/logs',
            name: 'Logs',
            component: Logs
        }
    ],
})
export default router