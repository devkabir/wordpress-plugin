<script>
import axios from "axios";
import {reactive, ref} from "vue"
import {Notyf} from 'notyf';
export default {
  setup() {
    const notyf = new Notyf();
    const settings = reactive({
      base: '',
      key: '',
      secret: '',
    })

    const isLoading = ref(false)
    const processSettings = async (data) => {
      if (isLoading.value) return;
      isLoading.value = true
      let serializedPost = new FormData()
      for (let item in settings) {
        if (settings.hasOwnProperty(item)) {
          serializedPost.append(item, settings[item])
        }
      }
      serializedPost.append('action', 'wordpress_plugin_settings')

      axios.post('https://your-domain.com/wp-admin/admin-ajax.php', serializedPost)
          .then(response => {
            notyf.success(response.data)
          })
          .catch(error => {
            notyf.error(error.message)
          })
          .finally(() => isLoading.value = false)
    }
    return {
      settings, processSettings, isLoading
    }
  }
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
      <div class="mt-5 md:col-span-2 md:mt-0">
        <form action="#" class="settings-form" method="POST" @submit.prevent="processSettings">
          <div class="overflow-hidden shadow sm:rounded-md">
            <div class="bg-white px-4 py-5 sm:p-6">
              <div class="grid  gap-6">
                <div class="col-span-full ">
                  <label class="input-label" for="base">Base Url</label>
                  <input id="base" v-model="settings.base" class="text-input" type="text"/>
                </div>
                <div class="col-span-full">
                  <label class="input-label" for="api-secret">API secret</label>
                  <input id="api-secret" v-model="settings.secret" class="text-input" type="text"/>
                </div>
                <div class="col-span-full">
                  <label class="input-label" for="api-key">API Key</label>
                  <input id="api-key" v-model="settings.key" class="text-input" type="text"/>
                </div>
              </div>
            </div>
            <div class="bg-gray-100 px-4 py-3 text-right sm:px-6">
              <button class="submit" type="submit">
                <span v-if="isLoading">Processing...</span>
                <span v-else>Save</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .settings-form {
    .input-label {
      @apply block text-sm font-medium text-gray-700;
    }
    .text-input {
      @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm;
    }
    .submit {
      @apply inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2;
    }
  }
</style>