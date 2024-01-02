<script setup lang="ts">
import { pluginStrings } from '@/composable';
const dashboardStrings = pluginStrings.dashboard;
</script>
<template >
    <div class="max-w-3xl">
        <p class="mb-2 text-sm font-semibold text-indigo-600">{{ dashboardStrings.title }}</p>
        <h1 class="block text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">
            {{ dashboardStrings.description }}
        </h1>

        <div class="flex flex-col items-center gap-2 mt-5 sm:flex-row sm:gap-3">
            <a class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-semibold text-white bg-indigo-600 border border-transparent rounded-lg sm:w-auto gap-x-2 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                href="https://github.com/devkabir/wordpress-plugin" target="_blank">
                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                </svg>
                {{ dashboardStrings.links.github }}
            </a>

        </div>
    </div>
</template>
