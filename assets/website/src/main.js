import { createApp } from 'vue'
import { createPinia } from 'pinia'
import ShortcodeOne from './ShortcodeOne.vue'
import ShortcodeTwo from './ShortcodeTwo.vue'

const pinia = createPinia();

createApp(ShortcodeOne).use(pinia).mount('#shortcode-1')
createApp(ShortcodeTwo).use(pinia).mount('#shortcode-2')
