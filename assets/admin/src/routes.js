let routes = [
    {
        path: '/', name: 'Settings', component: () => import( './pages/Settings.vue' )
    },
    {
        path: '/logs', name: 'Logs', component: () => import( './pages/Logs.vue' )
    }
];

export default routes
