<script setup>
import {inject, onBeforeMount, ref, watch} from 'vue';
import {Notyf} from 'notyf';
import Loading from '@/components/data/Loading.vue';

const props = defineProps(['model', 'endpoint']);
// Default
const notify = new Notyf();
let isLoading = ref(false);
const {model, endpoint} = props;
// Essential data
const records = ref([]);
const tabs = ref([]);
const selectedTab = ref();
const content = ref();
// Injecting the axios instance from the root component.
const axios = inject('axios');
// Making an API call to the endpoint and then setting the data to the records.value.
const fetchData = async () => {
  // Checking if the data is loading and if it is, it will return and not load the data again.
  if (isLoading.value) return;
  isLoading.value = true;
  // Making an API call to the endpoint and then setting the data to the records.value.
  await axios.get(endpoint).then(response => {
    records.value = response.data.items;
    if (typeof records.value === 'object') {
      tabs.value = Object.keys(records.value);
      selectedTab.value = tabs.value[0];
    }
  }).catch(error => notify.error(error.message)).finally(() => isLoading.value = false);
};
const clearAll = async () => {
  // Checking if the data is loading and if it is, it will return and not load the data again.
  if (isLoading.value) return;
  isLoading.value = true;
  await axios.get(endpoint + '/clear-all', {
    params: {
      file: selectedTab.value,
    },
  }).catch(error => notify.error(error.message)).then(response => notify.success(response.data.message)).finally(() => {
    isLoading.value = false;
    fetchData();
  });
};

const selectTab = async (tab) => {
  selectedTab.value = tab;
};
watch(selectedTab, (a,b)=>{
  content.value = records.value[a]
})
onBeforeMount(fetchData);

</script>

<template>
  <div class="px-4 sm:px-6 lg:px-8 ">
    <div class="sm:flex sm:items-center mb-3">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900" v-text="$route.name"></h1>
        <p class="mt-2 text-sm text-gray-700">A list of all the logs</p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <button
            class="submit"
            type="button"
            @click="clearAll"
            :disabled="isLoading"
        >
          <Loading v-if="isLoading"/>
          <span v-else>Clear</span>
        </button>
      </div>
    </div>
    <div v-if="tabs.length !== 0 ">
      <div class="sm:hidden">
        <label for="tabs" class="sr-only">Select a tab</label>
        <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
        <select id="tabs" name="tabs"
                v-model="selectedTab"
                @change="handleChange"
                class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
          <option class="capitalize" :selected="selectedTab === tab" v-for="(tab, index) in tabs" v-text="tab"
          >

          </option>
        </select>
      </div>
      <div class="hidden sm:block">
        <nav class="isolate flex divide-x divide-gray-200 rounded-lg shadow" aria-label="Tabs">
          <!-- Current: "text-gray-900", Default: "text-gray-500 hover:text-gray-700" -->
          <a href="#"
             v-for="(tab, index) in tabs" @click="selectTab(tab)"
             class="text-gray-900 rounded-l-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10"
             aria-current="page">
            <span class="capitalize" v-text="tab"></span>
            <span v-if="selectedTab === tab" aria-hidden="true"
                  class="bg-indigo-500 absolute inset-x-0 bottom-0 h-0.5"></span>
            <span v-else aria-hidden="true"
                  class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"></span>
          </a>
        </nav>
      </div>
    </div>
    <div class="flex flex-col ">
      <nav aria-label="Directory" class="h-full overflow-y-auto shadow rounded-md">
        <div class="relative">
          <ul class="relative z-0 divide-y divide-gray-200 " role="list">
            <li v-if="isLoading" class="bg-white">
              <div
                  class="relative flex items-center space-x-3 px-6 py-5 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 hover:bg-gray-50">
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-gray-900">Loading...</p>
                </div>
              </div>
            </li>
            <li v-else-if="content && content.length > 0" class="bg-white">
              <div
                  class="relative flex items-center space-x-3 px-6 py-5 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 hover:bg-gray-50">
                <div class="min-w-0 flex-1">
                  <pre class="whitespace-pre-wrap break-all text-sm font-medium text-gray-900" v-text="content"></pre>
                </div>
              </div>
            </li>
            <li v-else class="bg-white">
              <div
                  class="relative flex items-center space-x-3 px-6 py-5 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 hover:bg-gray-50">
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-gray-900">No {{ model.toLowerCase() }} yet!</p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>


</template>