<template>
  <div class="flex min-h-0 flex-1 flex-col bg-indigo-700 justify-between">
    <div class="flex flex-1 flex-col overflow-y-auto pt-5 pb-4">
      <div class="flex flex-shrink-0 items-center px-4">
        <WP class="icon fill-white"/>
        <span class="ml-4 font-bold text-white text-2xl">
          WordPress
        </span>
      </div>
      <nav class="mt-5 flex-1 px-2">
          <router-link v-for="menu in routes" :key="menu.name" class="link" active-class="active" :to="menu.path">
            <Component :is="menu.icon" class="icon" />
            {{menu.name}}
          </router-link>
      </nav>
    </div>
    <div class="flex flex-shrink-0 border-t border-indigo-800 p-4">
      <a href="https://profiles.wordpress.org/devkabir/" class="group block w-full flex-shrink-0" target="_blank">
        <div class="flex items-center">
          <div>
            <img class="inline-block h-9 w-9 rounded-full"
              src="https://secure.gravatar.com/avatar/393959c42bc6fc108dd48efaca536d51?s=100&d=mm&r=g"
              alt="Image of dev kabir">
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-white">by <span class="font-bold">Dev Kabir</span></p>
            <p class="text-xs font-medium text-indigo-200 group-hover:text-white">View profile</p>
          </div>
        </div>
      </a>
    </div>
  </div>
</template>

<script setup>
  import WP from "@/components/WP.vue";
  import routes from '@/routes.js';
</script>
<style scoped>
  .icon {
    @apply mr-3 h-6 w-6
  }

  .active {
    @apply bg-indigo-800 text-white;
  }

  .link {
    @apply text-indigo-100 flex items-center px-2 py-2 text-sm font-medium rounded-md hover:bg-indigo-600 hover:text-white;
  }
</style>