import SettingsPage from '@/pages/SettingsPage.vue'
import DashboardPage from '@/pages/DashboardPage.vue'
const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: DashboardPage
  },
  {
    path: '/settings',
    name: 'Settings',
    component: SettingsPage
  }
]
export default routes
