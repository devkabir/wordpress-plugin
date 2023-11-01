<script setup>
import { ref } from "vue";
import { fetchData } from "./composables";
import { useCounterStore } from "./stores/counter";
const store = useCounterStore();
const response = ref();
const testAjax = async () => {
  response.value = await fetchData('ajax_example', 'ajax_example');
  console.log(response);
}
testAjax();
</script>

<template>
  <div>
    <h1>Counter from store</h1>
    <h1>{{ store.count }}</h1>
    <button @click="store.increment">
      Add one
    </button>
    <button @click="store.doubleCount">
      Make it double
    </button>
    <h1>Test Ajax action</h1>
    <p v-html="response?.file"></p>
  </div>
</template>

<style scoped>
button {
  cursor: pointer;
  border: 0 #abb8c3;
  margin-left: 5px;
  padding: 0.5rem 1rem;
  font-weight: 600;
  border-radius: 10px;
}
</style>
