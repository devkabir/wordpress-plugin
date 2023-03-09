<template>
  <div class="max-h-screen">
    <div aria-modal="true" class="relative z-40" role="dialog">
      <transition-group
          enter-active-class="transition-transform duration-500"
          enter-from-class="-translate-x-full"
          enter-to-class="translate-x-0"
          leave-active-class="transition-transform duration-500"
          leave-from-class="translate-x-0"
          leave-to-class="-translate-x-full"
      >
        <div v-if="showMobileMenu" class="fixed inset-0 z-40 flex w-full ">
          <Navigation/>
        </div>
      </transition-group>
    </div>
    <div class="hidden md:mt-8 md:fixed md:z-40 md:-ml-5 md:inset-y-0 md:flex md:w-48 md:flex-col">
      <Navigation/>
    </div>
    <div class="flex flex-1 flex-col md:z-40 md:pl-44">
      <div class="md:hidden sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow">
        <button
            class="border-r border-gray-200 px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden"
            type="button"
            @click="toggleMobileMenu"
        >
          <span class="sr-only">Open sidebar</span>
          <Bars3BottomLeftIcon class="h-6 w-6 "/>
        </button>
      </div>
      <main class="z-40">
        <div class="py-6">
          <div class="mx-auto max-w-7xl px-4 sm:px-6 md:px-8">
            <router-view/>
          </div>
        </div>
      </main>
    </div>
  </div>


</template>
<script setup>
import Navigation from "./components/Navigation.vue";
import {Bars3BottomLeftIcon} from '@heroicons/vue/24/outline'
import {ref} from "vue";

let showMobileMenu = ref(false);
const toggleMobileMenu = () => showMobileMenu.value = !showMobileMenu.value;
</script>
<style lang="scss" scoped>
.icon {
  @apply mr-3 h-6 w-6 flex-shrink-0 text-indigo-300
}

</style>