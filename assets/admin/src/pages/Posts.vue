<script setup>
import DataTable from '@/components/data/DataTable.vue';
const columns = [
  {
    key:'ID',
    hidden:true,
    select:true
  },
  {
    key: 'post_title',
    label: 'Title',
    search:true,
    primary:true
  },
  {
    key: 'post_date',
    label: 'Posted',
    type: 'date',
    sort: true,
    showInMd: true
  },
  {
    key: 'guid',
    label: 'URL',
    type: 'link',
    text: 'Visit',
    showInSm: true
  },

]
</script>

<template>
  <data-table :columns="columns" endpoint="posts" per_page="5"/>
</template>

<style scoped>

</style>