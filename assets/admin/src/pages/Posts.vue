<script setup>
import DataTable from '@/components/data/DataTable.vue';
const columns = [
  {
    key: 'post_title',
    label: 'Title',
    search:true
  },
  {
    key: 'post_date',
    label: 'Posted',
    type: 'date',
    sort: true
  },
  {
    key: 'guid',
    label: 'URL',
    type: 'link',
    text: 'Visit'
  },

]
</script>

<template>
  <data-table :columns="columns" endpoint="posts"/>
</template>

<style scoped>

</style>