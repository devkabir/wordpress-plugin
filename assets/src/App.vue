<script setup lang="ts">
import { RouterView } from 'vue-router'
import routes from '@/routes';
</script>

<template>
  <div role="main" class="mr-4 bg-white shadow-sm rounded-b-md">
    <!-- Nav -->
    <nav
      class="sticky top-[32px] bg-white text-sm  font-medium text-black ring-1 ring-gray-900 ring-opacity-5 border-t shadow-sm shadow-gray-100 pt-6 md:pb-6 -mt-px dark:bg-slate-900 dark:border-gray-800 dark:shadow-slate-700/[.7]"
      aria-label="Jump links">
      <div
        class="max-w-7xl snap-x w-full flex items-center overflow-x-auto px-4 sm:px-6 lg:px-8 pb-4 md:pb-0 mx-auto [&::-webkit-scrollbar]:h-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-slate-900">
        <div class="snap-center shrink-0 pe-5 sm:pe-8 sm:last-pe-0" v-for="(route, index) in routes" :key="index">
          <router-link active-class="font-bold text-indigo-600 dark:text-indigo-500"
            class="inline-flex items-center gap-x-2 focus:ring-0 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-500 dark:focus:outline-none dark:focus:ring-0 dark:focus:ring-gray-600"
            :to="route.path">
            {{ route.name }}
          </router-link>
        </div>
      </div>
    </nav>
    <!-- End Nav -->

    <div class="p-4 mx-auto max-w-7xl">
      <RouterView />
    </div>
  </div>
</template>
