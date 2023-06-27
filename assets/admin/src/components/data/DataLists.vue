<script setup>
import Loading from '@/components/Loading.vue';
import {useRoute} from 'vue-router';
import {XMarkIcon} from '@heroicons/vue/24/outline';
import {useDataList} from '@/composable/data-list.js';

/**
 * Defined constants for this component.
 */
const route = useRoute();
const props = defineProps({
  endpoint: {
    type: String,
    required: true,
  },
});
const {
  tabs,
  selectedTab,
  selectTab,
  clean,
  isLoading,
  selectedTabContent,
} = useDataList(props);
</script>

<template>
  <div class="bg-white">
    <div v-if="tabs.length !== 0 ">
      <div class="sm:hidden">
        <label class="sr-only" for="tabs">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select id="tabs" v-model="selectedTab"
                class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                name="tabs">
          <option v-for="(tab, index) in tabs" :key="index" :selected="selectedTab === tab" class="capitalize"
                  v-text="tab"
          >

          </option>
        </select>
      </div>
      <div class="hidden sm:block shadow">
        <nav aria-label="Tabs" class="isolate flex divide-x divide-gray-200 rounded-lg">
          <div
              v-for="(tab, index) in tabs" :key="index"
              aria-current="page"
              class="tab group"
              @click="selectTab(tab)">
            <span class="capitalize" v-text="tab"></span>
            <span v-if="selectedTab === tab" aria-hidden="true"
                  class="bg-indigo-500 absolute inset-x-0 bottom-0 h-0.5"></span>
            <span v-else aria-hidden="true" class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"></span>
            <button class="p-1 hover:bg-gray-400 text-gray-900  hover:text-white rounded" @click="clean(tab)">
              <XMarkIcon class="w-4 h-4  font-bold"/>
            </button>
          </div>
        </nav>
      </div>
    </div>
    <div>
      <div v-if="isLoading" class="placeholder">
        <Loading class="text-gray-900"/>
      </div>
      <div v-else-if="selectedTabContent && selectedTabContent.length > 0" class="tab-content">
         <pre class="whitespace-pre-wrap break-all text-sm font-medium text-gray-900"
              v-text="selectedTabContent"></pre>
      </div>
      <div v-else class="placeholder">No {{ route.name.toLowerCase() }} yet!</div>
    </div>
  </div>
</template>
<style scoped>
.tab {
  @apply flex justify-between text-gray-900 rounded-l-lg relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10;
}

.tab-content {
  @apply relative flex items-center space-x-3 px-6 py-5 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 hover:bg-gray-50
}
</style>