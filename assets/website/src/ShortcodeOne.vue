<template>
  <h1>{{ count }}</h1>
</template>
<script setup>
import {useCounterStore} from "./stores/counter";
import {storeToRefs} from "pinia";

const { count } = storeToRefs(useCounterStore())
</script>
