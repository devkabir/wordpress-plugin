import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useCounterStore = defineStore('counter', () => {
  const count = ref(0)
  function increment() {
    count.value++
  }

  function doubleCount() {
    count.value = count.value * 2
  }

  return { count, doubleCount, increment }
})
