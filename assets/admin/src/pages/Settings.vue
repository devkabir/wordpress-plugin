<script>
import {inject, onMounted, reactive, ref} from "vue"
import {Notyf} from 'notyf';

export default {
  setup() {
    const notyf = new Notyf();
    const settings = reactive({
      track: false,
    })

    const isLoading = ref(false)
    const axios = inject('axios')
    const processSettings = async (data) => {
      if (isLoading.value) return;
      isLoading.value = true
      let serializedPost = new FormData()
      for (let item in settings) {
        if (settings.hasOwnProperty(item)) {
          serializedPost.append(item, settings[item])
        }
      }


      axios.post('settings', serializedPost)
          .then(response => {
            const updated = response.data;
            console.log(updated)
            // console.log(updated.track);
            // settings.track = updated.track
            notyf.success('setting saved!')
          })
          .catch(error => {
            notyf.error(error.message)
          })
          .finally(() => isLoading.value = false)
    }
    const loadSettings = async (data) => {
      if (isLoading.value) return;
      isLoading.value = true

      axios.get('settings')
          .then(response => {
            const updated = response.data;
            settings.track = updated.track
          })
          .catch(error => {
            notyf.error(error.message)
          })
          .finally(() => isLoading.value = false)
    }
    onMounted(() => loadSettings())
    // expose to template and other options API hooks
    return {
      isLoading, processSettings, settings
    }
  },


}

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
                    <input id="track" v-model="settings.track" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" name="track"
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
                <svg v-if="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                     fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        fill="currentColor"></path>
                </svg>
                <span v-else>Save</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
