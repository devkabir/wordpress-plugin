<template>
  <button @click="increment">
    Add one
  </button>
  <button @click="doubleCount">
    Make it double
  </button>
</template>
<script setup>
import {useCounterStore} from "./stores/counter";

const {increment , doubleCount } = useCounterStore()
</script>
<style scoped>
button{
  cursor: pointer;
  border: 0 #abb8c3;
  margin-left: 5px;
  padding: 0.5rem 1rem;
  font-weight: 600;
  border-radius: 10px;
}
</style>
