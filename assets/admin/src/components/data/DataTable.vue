<script>
import { computed, inject, onBeforeMount, ref } from 'vue'
import { Notyf }                                from 'notyf'
import { onBeforeRouteLeave }                   from 'vue-router'

export default {
  name: 'DataTable',
  props: ['model', 'endpoint'],
  setup(props) {
    const notyf    = new Notyf()
    let isLoading  = ref(false)
    let sortedType = ref(false)

    const records         = ref([])
    const currentPage     = ref(1)
    const perPage         = ref(10)
    const maxVisiblePages = ref(5)

    const pages = ref([])

    const columns             = ref([])
    const toggleColumns       = ref([])
    const totalPages          = ref(0)
    const totalResults        = ref(0)
    const startIndex          = ref(0)
    const endIndex            = ref(0)
    const sortedColumn        = ref('id')
    const { model, endpoint } = props
    let oldSelection          = localStorage.getItem(endpoint)
    if (oldSelection !== null) {
      toggleColumns.value = JSON.parse(oldSelection)
    }
    const axios     = inject('axios')
    let selectedColumns
    const fetchData = async () => {
      // Checking if the data is loading and if it is, it will return and not load the data again.
      if (isLoading.value) return
      isLoading.value = true
      // Making an API call to the endpoint and then setting the data to the records.value.
      if (toggleColumns.value && toggleColumns.value.length > 0) {
        let filter      = toggleColumns.value.filter(i => i.value)
        selectedColumns = filter.map(i => i.key).join(',')
      }

      let params = {
        'per_page': perPage.value,
        'page': currentPage.value,
        'columns': selectedColumns,
        'sort': sortedColumn.value,
        'desc': sortedType.value,
      }
      await axios.get(endpoint, {
        params
      }).then(response => {
        records.value    = response.data.items
        totalPages.value = response.data.total_pages
        columns.value    = Object.keys(records.value[0])
        if (toggleColumns.value && toggleColumns.value.length === 0) {
          toggleColumns.value = columns.value.map(key => ({ key, value: true }))
        }
        totalResults.value = response.data.total_items

        const startIndexComputed = computed(() => (currentPage.value - 1) * perPage.value + 1)
        const endIndexComputed   = computed(() => (currentPage.value * perPage.value > totalResults.value ? totalResults.value : currentPage.value * perPage.value))
        const pagesComputed      = computed(() => Array.from({ length: totalPages.value }, (_, index) => index + 1))

        startIndex.value = startIndexComputed.value
        endIndex.value   = endIndexComputed.value
        pages.value      = pagesComputed.value

      }).finally(() => isLoading.value = false)
    }


    const clearAll = async () => {
      // Checking if the data is loading and if it is, it will return and not load the data again.
      if (isLoading.value) return
      isLoading.value = true
      await axios.get(endpoint + '/clear-all')
          .catch(error => notyf.error(error.message))
          .then(response => {
            notyf.success(response.data.message)
          })
          .finally(() => {
            isLoading.value = false
            fetchData()
          })
    }
    const prevPage = () => {
      currentPage.value--
      fetchData()
    }

    const nextPage   = () => {
      currentPage.value++
      fetchData()
    }
    const changePage = ((page) => {
      currentPage.value = page
      fetchData()
    })
    const hasHTML    = str => {
      const pattern = /<[a-z][\s\S]*>/i
      return pattern.test(str)
    }

    const shouldShowPage = page => {
      if (pages.value.length <= maxVisiblePages.value) {
        return true
      }
      if (currentPage.value <= Math.floor(maxVisiblePages.value / 2)) {
        return page <= maxVisiblePages.value
      }
      if (currentPage.value >= pages.value.length - Math.floor(maxVisiblePages.value / 2)) {
        return page >= pages.value.length - maxVisiblePages.value + 1
      }
      return page >= currentPage.value - Math.floor(maxVisiblePages.value / 2) && page <= currentPage.value + Math.floor(maxVisiblePages.value / 2)

    }
    const toggleColumn   = column => {
      toggleColumns.value = toggleColumns.value.map(i => {
        if (i.key === column) {
          i.value = !i.value
        }
        return i
      })
      console.log(toggleColumns.value)
      fetchData()
    }

    const resetColumns = () => {
      toggleColumns.value = toggleColumns.value.map(i => {
        i.value = true
        return i
      })
      fetchData()
    }
    const sortColumn   = column => {
      sortedColumn.value = column
      sortedType.value   = !sortedType.value
      fetchData()
    }
    onBeforeMount(fetchData)
    onBeforeRouteLeave(() => {
      localStorage.setItem(endpoint, JSON.stringify(toggleColumns.value))
    })
    return {
      model,
      isLoading,
      records,
      columns,
      totalPages,
      currentPage,
      perPage,
      totalResults,
      startIndex,
      endIndex,
      changePage,
      pages,
      toggleColumns,
      sortedColumn,
      sortedType,
      prevPage,
      nextPage,
      shouldShowPage,
      clearAll,
      hasHTML,
      toggleColumn,
      resetColumns,
      sortColumn,
    }
  },
}
</script>
<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900" v-text="model"></h1>
        <p class="mt-2 text-sm text-gray-700">A list of all the {{ model.toLowerCase() }}.</p>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <button class="button-success" type="button" @click="clearAll">
          Clear All
        </button>
      </div>
    </div>
    <div class="mt-8 flex flex-col">
      <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle">
          <div class="overflow-hidden">
            <h3 v-if="isLoading" class="datatable-message">Loading...</h3>
            <div v-else-if="!isLoading && records.length > 0" class="bg-white">
              <div class="flex justify-between p-2 my-2">
                <div>
                  <div v-for="(column, index) in toggleColumns" :key="index"
                       class="relative inline-flex items-start mx-2">
                    <div class="flex h-5 items-center">
                      <input :id="column"
                             :checked="column.value"
                             :value="column.key" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" type="checkbox" @click="toggleColumn(column.key)">
                    </div>
                    <div class="ml-3 text-sm">
                      <label :for="column" class="font-medium text-gray-700 capitalize"
                             v-text="column.key.toString().replace('_', ' ').toUpperCase()"></label>
                    </div>
                  </div>
                </div>
                <button class="button-success-slim" type="button" @click="resetColumns">Reset</button>
              </div>
              <table class="min-w-full divide-y divide-gray-300 shadow">
                <thead class="bg-gray-50">
                <tr>
                  <th v-for="(column, index) in columns" :key="index"
                      class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 capitalize" scope="col">
                    <button class="group inline-flex" type="button" @click="sortColumn(column)">
                      {{ column.toString().replace('_', ' ').toUpperCase() }}
                      <span v-show="sortedColumn === column"
                            :class="sortedType === true ? 'bg-gray-200 text-gray-900 group-hover:bg-gray-300' : 'invisible text-gray-400 group-hover:visible group-focus:visible'"
                            class="ml-2 flex-none rounded bg-gray-200 text-gray-900 group-hover:bg-gray-300">
                          <!-- Heroicon name: mini/chevron-down -->
                          <svg aria-hidden="true" class="h-5 w-5" fill="currentColor"
                               viewBox="0 0 20 20"
                               xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd"
                                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                  fill-rule="evenodd"/>
                          </svg>
                        </span>
                    </button>

                  </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="(record, index) in records" :key="index"
                    :class="index % 2 === 0 ? 'bg-white' : 'bg-slate-100'">

                  <td v-for="(column, index) in columns" :key="index"
                      :class="{ 'break-all': record[column].length > 30, 'whitespace-nowrap': record[column].length < 50 }"
                      class="px-3 py-4 text-sm text-gray-500">
                    <span v-if="hasHTML(record[column])" class="html" v-html="record[column]"></span>
                    <span v-else class="string" v-text="record[column]"></span>
                  </td>

                </tr>
                </tbody>
                <tfoot>
                <tr>
                  <td :colspan="columns.length">
                    <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                      <div class="flex flex-1 justify-between sm:hidden">
                        <button
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            type="button" @click="prevPage">Previous
                        </button>
                        <button
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                            type="button" @click="nextPage">Next
                        </button>
                      </div>
                      <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                        <div>
                          <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium" v-text="startIndex"></span>
                            to
                            <span class="font-medium" v-text="endIndex"></span>
                            of
                            <span class="font-medium" v-text="totalResults"></span>
                            results
                          </p>
                        </div>
                        <div>
                          <nav aria-label="Pagination">


                            <ul class="inline-flex">
                              <li v-for="page in pages" :key="page">
                                <button v-if="shouldShowPage(page)"
                                        :class="{ 'datatable-active': currentPage === page }" aria-current="page"
                                        class="relative inline-flex items-center  px-4 py-2 text-sm font-medium border rounded-md shadow-sm focus:z-20"
                                        type="button" @click="changePage(page)"
                                        v-text="shouldShowPage(page) ? page : '...'"></button>
                              </li>
                            </ul>
                          </nav>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                </tfoot>
              </table>
            </div>

            <h3 v-else class="datatable-message">No records yet</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.datatable-message {
  @apply bg-white p-6 text-center;
}

.datatable-active {
  @apply z-10 bg-indigo-50 border-indigo-500 text-indigo-600;
}
</style>