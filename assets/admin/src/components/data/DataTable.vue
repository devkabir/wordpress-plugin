<script setup>
import {useBulkAction, useDataTable, usePagination, useSearch, useSort} from '@/composables/data-table.js';
import Loading from '@/components/Loading.vue';
import {useRoute} from 'vue-router';
import {ChevronDownIcon, ChevronLeftIcon, ChevronRightIcon, ChevronUpIcon} from '@heroicons/vue/24/outline';

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
  per_page: {
    type: String,
    required: true,
  },
});
const route = useRoute();
const {
  isLoading,
  data,
  columns,
  generateClass,
  formatColumnData,
} = useDataTable(props);

const {
  sortData,
  sortedColumns,
} = useSort(data);

const {
  search,
  anySearchable,
  searchResults,
} = useSearch(data, props);

const {
  from,
  to,
  totalResults,
  totalPages,
  currentPage,
  paginatedData,
  nextPage,
  previousPage,
  pageNumbers,
  goTo,
} = usePagination(searchResults, props);
const {
  selectKey,
  selected,
  allSelected,
  selectAll,
  selectedRows,
  deleteAll,
} = useBulkAction(paginatedData, props.columns);
</script>
<template>
  <div>
    <div v-if="anySearchable.length" class="flex justify-end">
      <div class="m-4">
        <input v-model="search" class="text-input" placeholder="Search here..." type="search" @keyup="goTo(1)">
      </div>
    </div>
    <div class="relative overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
      <div v-if="isLoading" class="placeholder">
        <Loading class="text-gray-900"/>
      </div>
      <!-- Selected row actions, only show when rows are selected. -->
      <div v-if="selectKey && selectedRows.length"
           class="bulk-wrapper">
        <button class="px-10 button-light" type="button" @click="deleteAll">Delete all</button>
      </div>
      <table v-if="paginatedData && paginatedData.length" class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-50">
        <tr>
          <th v-if="selectKey" class="relative w-12 px-6 sm:w-16 sm:px-8" scope="col">
            <input v-model="allSelected"
                   class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6"
                   type="checkbox"
                   @change="selectAll">
          </th>
          <th v-for="(header, headerIndex) in columns" :key="headerIndex" :class="generateClass(header)"
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

        <tr v-for="(row, rowIndex) in paginatedData" :key="rowIndex" class="divide-x divide-gray-200">
          <td v-if="selectKey" class="relative w-12 px-6 sm:w-16 sm:px-8">
            <!-- Selected row marker, only show when row is selected. -->
            <div v-if="selectedRows.find(id => id=== row[selectKey])"
                 class="absolute inset-y-0 left-0 w-0.5 bg-indigo-600"></div>

            <input v-model="selectedRows" :value="row[selectKey]"
                   class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 sm:left-6"
                   type="checkbox"
                   @change="selected">
          </td>
          <td v-for="(column, columnIndex) in columns" :key="columnIndex" :class="generateClass(column)"
              v-html="formatColumnData(row,columns,column)"></td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td :colspan="props.columns.length +1">
            <div class="flex items-center justify-between bg-gray-50 px-4 py-3 sm:px-6">
              <div class="flex flex-1 justify-between sm:hidden">
                <button :disabled="currentPage===1" class="button-light" type="button" @click="previousPage">Previous
                </button>
                <button :disabled="currentPage===totalPages" class="button-light" type="button" @click="nextPage">Next
                </button>
              </div>
              <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium"
                          v-text="from"></span>
                    to
                    <span class="font-medium" v-text="to"></span>
                    of
                    <span class="font-medium" v-text="totalResults"></span>
                    results
                  </p>
                </div>
                <div>
                  <nav aria-label="Pagination" class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                    <button :disabled="currentPage===1" class="button-previous" type="button"
                            @click="previousPage">
                      <span class="sr-only">Previous</span>
                      <ChevronLeftIcon class="w-5 h-5"/>
                    </button>
                    <button v-for="page in pageNumbers" :key="page" :class="{'button-page-current': page===currentPage}"
                            aria-current="page" class="button-page"
                            type="button"
                            @click="goTo(page)" v-text="page"></button>
                    <button :disabled="currentPage===totalPages" class="button-next" type="button"
                            @click="nextPage">
                      <span class="sr-only">Next</span>
                      <ChevronRightIcon class="w-5 h-5"/>
                    </button>
                  </nav>
                </div>
              </div>
            </div>
          </td>
        </tr>

        </tfoot>
      </table>
      <div v-if="!isLoading && paginatedData.length === 0" class="placeholder">No {{ route.name.toLowerCase() }} yet!
      </div>
    </div>
  </div>

</template>
<style scoped>
.default-column {
  @apply px-3 py-3.5 text-left text-sm text-gray-900 sticky;
}

.primary-column {
  @apply py-3.5 pl-4 pr-3 text-left text-sm  text-gray-900 sm:pl-6;
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

.bulk-wrapper {
  @apply w-full  absolute top-0 left-12 flex h-12 items-center space-x-3 bg-gray-50 sm:left-16;
}

.button-pagination {
  @apply relative inline-flex items-center  border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20
}

.button-previous {
  @apply button-pagination rounded-l-md;
}

.button-next {
  @apply button-pagination rounded-r-md;
}

.button-page {
  @apply relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20
}

.button-page-current {
  @apply z-10 bg-indigo-50 border-indigo-500 text-indigo-600;
}
</style>

