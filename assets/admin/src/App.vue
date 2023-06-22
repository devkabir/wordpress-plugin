<template>
  <div class="max-h-screen -ml-4 md:-ml-6">
    <div class="relative md:hidden" role="dialog" aria-modal="true">
      <transition enter-active-class="transition-opacity ease-linear duration-300" enter-from-class="opacity-0"
                  enter-to-class="opacity-100" leave-active-class="transition-opacity ease-linear duration-300"
                  leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showMobileMenu" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-50"></div>
      </transition>
      <transition enter-active-class="transition-transform duration-500" enter-from-class="-translate-x-full"
                  enter-to-class="translate-x-0" leave-active-class="transition-transform duration-500"
                  leave-from-class="translate-x-0" leave-to-class="-translate-x-full">

        <div v-if="showMobileMenu" class="fixed inset-0 flex z-50">

          <div class="relative flex w-full max-w-xs flex-1 flex-col bg-indigo-700 py-5">

            <div class="absolute top-12 right-0 -mr-12 pt-2">
              <button type="button"
                      @click="toggleMobileMenu"
                      class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <XMarkIcon class="h-6 w-6 text-white"/>
              </button>
            </div>

            <div class="h-0 flex-1 overflow-y-auto pt-5 pb-4">
              <Navigation/>
            </div>
          </div>


        </div>
      </transition>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden md:mt-8 md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <Navigation/>
    </div>
    <div class="flex flex-1 flex-col md:pl-64">
      <div class="bg-white shadow sticky top-0  pl-1 pt-1 sm:pl-3 sm:pt-3 md:hidden">
        <button type="button"
                @click="toggleMobileMenu"
                class="-ml-0.5 -mt-0.5 inline-flex h-12 w-12 items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
          <span class="sr-only">Open sidebar</span>
          <Bars3BottomLeftIcon class="h-6 w-6"/>
        </button>
      </div>
      <main class="flex-1 bg-slate-100 min-h-screen">
        <div class="py-6">
          <router-view></router-view>
        </div>
      </main>
    </div>
  </div>
</template>
<script setup>
import {ref} from "vue";
import Navigation from "@/components/Navigation.vue";
import {Bars3BottomLeftIcon, XMarkIcon} from "@heroicons/vue/24/outline";

let showMobileMenu = ref(false);
const toggleMobileMenu = () => showMobileMenu.value = !showMobileMenu.value;


</script>