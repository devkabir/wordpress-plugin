<template>
  <div class="bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <WP/>
          </div>
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <router-link active-class="active" class="menu-link" to="/">Settings</router-link>
              <router-link active-class="active" class="menu-link" to="/logs">Logs</router-link>
            </div>
          </div>
        </div>
        <div class="-mr-2 flex md:hidden">
          <button
              class="notification-button"
              type="button">
            <span class="sr-only">View notifications</span>
            <outfill-bell/>
          </button>
          <!-- Mobile menu button -->
          <button aria-controls="mobile-menu"
                  aria-expanded="false"
                  class="mobile-menu-toggle"
                  type="button"
                  @click="showMobileMenu"
          >
            <span class="sr-only">Open main menu</span>
            <OutfillBars v-if="!showMenu"/>
            <OutlineX v-else/>
          </button>
        </div>
      </div>
    </div>
    <div id="mobile-menu" class="md:hidden" :class="showMenu ? 'block' : 'hidden'" >
      <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
        <router-link active-class="active" class="menu-link" to="/">Settings</router-link>
        <router-link active-class="active" class="menu-link" to="/logs">Logs</router-link>
      </div>
    </div>
  </div>
</template>
<script>
import {ref}       from "vue";
import OutfillBell from "./icons/OutfillBell.vue";
import OutfillBars from "./icons/OutfillBars.vue";
import OutlineX    from "./icons/OutlineX.vue";
import WP          from './icons/WP.vue';
export default {
  name: 'Navigation',
  components: {OutlineX, OutfillBars, OutfillBell, WP},
  setup() {
    let showMenu = ref(false)
    const showMobileMenu = () => showMenu.value = !showMenu.value
    return {
      showMenu, showMobileMenu
    }
  }
}
</script>
<style scoped lang="scss">
  .menu-link {
    @apply text-gray-600 hover:bg-gray-700 hover:text-white hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium;
  }

  .active {
    @apply bg-gray-900 text-white;
  }

  .mobile-menu-toggle {
    @apply inline-flex items-center justify-center rounded-md  p-2  hover:bg-gray-700 hover:text-white;
  }

  .mobile-menu-link {
    @apply text-gray-300 hover:bg-gray-700 hover:text-white block px-3 py-2 rounded-md text-base font-medium;
  }
</style>