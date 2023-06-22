<script setup>
import {inject, onBeforeMount, reactive, ref} from 'vue';
import {Notyf} from 'notyf';
import Loading from '@/components/data/Loading.vue';

const notyf = new Notyf();
const settings = reactive({
  track: false,
});

const isLoading = ref(false);
const axios = inject('axios');
const processSettings = async (data) => {
  if (isLoading.value) return;
  isLoading.value = true;
  let serializedPost = new FormData();
  for (let item in settings) {
    if (settings.hasOwnProperty(item)) {
      serializedPost.append(item, settings[item]);
    }
  }

  axios.post('settings', serializedPost).then(response => {
    const updated = response.data;
    settings.track = updated.data.track;
    notyf.success(updated.message);
  }).catch(error => {
    notyf.error(error.message);
  }).finally(() => isLoading.value = false);
};
const loadSettings = async (data) => {
  if (isLoading.value) return;
  isLoading.value = true;

  axios.get('settings').then(response => {
    const updated = response.data.data;
    settings.track = updated.track;
  }).catch(error => {
    notyf.error(error.message);
  }).finally(() => isLoading.value = false);
};
onBeforeMount(() => loadSettings());
// expose to template and other options API hooks

</script>
<template>
  <div class="sm:m-4 p-4">
    <div class="md:grid md:grid-cols-3 md:gap-6">
      <div>
        <div class="px-4 sm:px-0">
          <h3 class="text-lg font-medium leading-6 text-gray-900">Settings</h3>

        </div>
      </div>
      <div :class="isLoading ? 'loading' : null" class="mt-5 md:col-span-2 md:mt-0">
        <form action="#" class="settings-form" method="POST" @submit.prevent="processSettings">
          <div class="overflow-hidden shadow sm:rounded-md">
            <div class="bg-white px-4 py-5 sm:p-6">
              <div class="grid  gap-6">
                <div class="flex items-start">
                  <div class="flex h-5 items-center">
                    <input id="track" v-model="settings.track"
                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" name="track"
                           type="checkbox"/>
                  </div>
                  <div class="ml-3 text-sm">
                    <label class="font-medium text-gray-700" for="track">Track</label>
                    <p class="text-gray-500">Get an entry what user is doing with your site</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-100 px-4 py-3 text-right sm:px-6">
              <button class="submit" type="submit">
                <Loading v-if="isLoading"/>
                <span v-else>Save</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<style>
input[type=checkbox]:checked::before {
  content: unset !important;
}
</style>