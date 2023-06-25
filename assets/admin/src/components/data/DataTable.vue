<script setup>
import {useDataTable} from '@/composables/data-table.js';
import Loading from '@/components/Loading.vue';
import {useRoute} from 'vue-router';
import {ChevronUpIcon, ChevronDownIcon, ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/24/outline';

/**
 * Props definition for this component
 */
const props = defineProps({
  endpoint: {
    type: String,
    required: true,
  },
  columns: {
    type: Object,
    required: true,
  },
  per_page:{
    type:String
  }
});
const route = useRoute();
const {
  anySearchable,
  search,
  isLoading,
  data,
  selectAll,
  generateClass,
  formatColumnData,
  sortData,
  sortedColumns,
  selectedRows,
} = useDataTable(props);
</script>
<template>
  <div>
    <div v-if="anySearchable.length" class="flex justify-end">
      <div class="m-4">
        <input v-model="search" class="text-input" placeholder="Search here..." type="search">
      </div>
    </div>
    <div class="relative overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
      <div v-if="isLoading" class="placeholder">
        <Loading class="text-gray-900"/>
      </div>
      <!-- Selected row actions, only show when rows are selected. -->
      <div v-if="Object.values(selectedRows).some(value => value === true)"
           class="bulk-wrapper">
        <button class="px-10 button-light" type="button">Delete all</button>
      </div>
      <table v-if="data && data.length" class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-50">
        <tr>
          <th class="relative w-12 px-6 sm:w-16 sm:px-8" scope="col">
            <input v-model="selectAll"
                   class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6"
                   type="checkbox">
          </th>
          <th v-for="(header, headerIndex) in props.columns" :key="headerIndex" :class="generateClass(header)"
              scope="col">
            <div v-if="header.sort" class="group inline-flex" @click="sortData(header.key)">
              {{ header.label }}
              <span class="sort">
              <ChevronDownIcon v-if="sortedColumns[header.key]" class="w-5 h-5"/>
              <ChevronUpIcon v-else class="w-5 h-5"/>
            </span>
            </div>
            <span v-else>{{ header.label }}</span>
          </th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">

        <tr v-for="(row, rowIndex) in data" :key="rowIndex" class="divide-x divide-gray-200">
          <td class="relative w-12 px-6 sm:w-16 sm:px-8">
            <!-- Selected row marker, only show when row is selected. -->
            <div v-if="selectedRows[row.id] === true" class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"></div>

            <input v-model="selectedRows[row.id]"
                   class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6"
                   type="checkbox">
          </td>
          <td v-for="(column, columnIndex) in columns" :key="columnIndex" :class="generateClass(column)"
              v-html="formatColumnData(row[column.key], column)"></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td :colspan="props.columns.length +1">
          </td>
        </tr>

        </tfoot>
      </table>
      <div v-if="!isLoading && data.length === 0" class="placeholder">No {{ route.name.toLowerCase() }} yet!</div>
    </div>
  </div>

</template>
<style scoped>
.default-column {
  @apply px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sticky;
}

.primary-column {
  @apply py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6;
}

.lg-column {
  @apply default-column hidden lg:table-cell
}

.md-column {
  @apply default-column hidden md:table-cell
}

.sm-column {
  @apply default-column hidden sm:table-cell
}

.sort {
  @apply bg-gray-200 text-gray-900 group-hover:bg-gray-300 ml-2 flex-none rounded;
}

.text-input {
  @apply block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm;
}
.bulk-wrapper{
  @apply w-full absolute top-0 left-12 flex h-12 items-center space-x-3 bg-gray-50 sm:left-16;
}
</style>

