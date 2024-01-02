import { acceptHMRUpdate, defineStore } from 'pinia'
import { reactive, ref } from 'vue'
import { notify, useFetch } from '@/composable'
import type { ApiResponse } from '@/types'

export const useSettingsStore = defineStore('settings', () => {
  const api = window.your_plugin_name.settings_url
  const loading = ref(false)
  const settings = reactive({
    enable: false
  })
  const { send, get } = useFetch()
  async function fetch() {
    await get(api)
      .then((data: ApiResponse) => {
        console.log(data)

        if (data.success) {
          settings.enable = data.data.enable
        }
      })
      .catch((error) => {
        notify.error(error.message)
      })
  }
  async function save() {
    loading.value = true
    await send(window.your_plugin_name.settings_url, settings)
      .then((data: ApiResponse) => {
        if (data.success) {
          settings.enable = data.data.enable
          notify.success(data.message)
        } else {
          notify.error(data.message)
        }
      })
      .catch((error) => {
        notify.error(error.message)
      })
      .finally(() => {
        loading.value = false
      })
  }

  return {
    loading,
    settings,
    save,
    fetch
  }
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useSettingsStore, import.meta.hot))
}
