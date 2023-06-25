import {
  AdjustmentsVerticalIcon,
  ClipboardDocumentListIcon,
} from '@heroicons/vue/24/outline';

let routes = [
  {
    path: '/',
    name: 'Settings',
    component: ()=> import('@/pages/Settings.vue'),
    icon: AdjustmentsVerticalIcon,
  }, {
    path: '/logs',
    name: 'Logs',
    component: () => import( '@/pages/Logs.vue' ),
    icon: ClipboardDocumentListIcon,
  }];

export default routes;
