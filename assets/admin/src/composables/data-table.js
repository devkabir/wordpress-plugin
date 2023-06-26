import {computed, inject, onBeforeMount, ref} from 'vue';


/*
 |--------------------------------------------------------------------------
 | Basic functionality
 |--------------------------------------------------------------------------
 */
export function useDataTable(props) {
  // State variables
  const isLoading = ref(false);
  const axios = inject('axios');
  const notify = inject('notify');
  const data = ref([]);

  // Fetch data before the table is mounted
  onBeforeMount(async () => {
    if (isLoading.value) {
      return;
    }
    isLoading.value = true;
    try {
      const { data: responseData } = await axios.get(props.endpoint);
      data.value = responseData;
    } catch (error) {
      notify.error(error.message);
    } finally {
      isLoading.value = false;
    }
  });

  // Format column data based on type
  const formatColumnData = (row, columns, column) => {
    let result = row[column.key];

    if (column.hasOwnProperty('type')) {
      let text = column.text || result;
      result = generateHtml(column, result, text);
    }

    if (column.hasOwnProperty('primary')) {
      result += '<table class="font-normal sm:hidden">';
      columns.forEach(col => {
        if (col.key !== column.key) {
          result += '<tr><td class="font-semibold">' + col.label + '</td><td>' +
              generateHtml(col, row[col.key], col.text) + '</td></tr>';
        }
      });
      result += '</table>';
    }

    return result;
  };

  // Generate responsive class based on column definition
  const generateClass = column => {
    let classNames = 'default-column';
    if (column.hasOwnProperty('primary') && column.primary) {
      classNames = 'primary-column';
    }
    if (column.hasOwnProperty('showInLg') && column.showInLg) {
      classNames = 'lg-column';
    }
    if (column.hasOwnProperty('showInMd') && column.showInMd) {
      classNames = 'md-column';
    }
    if (column.hasOwnProperty('showInSm') && column.showInSm) {
      classNames = 'sm-column';
    }
    return classNames;
  };

  const columns = props.columns.filter(obj => !obj.hidden);

  // Generate HTML based on column type
  const generateHtml = (column, result, text) => {
    switch (column.type) {
      case 'link':
        return `<a href="${result}" target="_blank">${text}</a>`;
      case 'email':
        return `<a href="mailto:${result}" target="_blank">${text}</a>`;
      case 'tel':
        return `<a href="tel:${result}" target="_blank">${text}</a>`;
      case 'date':
        const date = new Date(result);
        return date.toDateString();
      default:
        return result;
    }
  };

  // Return the state variables and functions
  return {
    isLoading,
    data,
    columns,
    generateClass,
    formatColumnData,
  };
}


/*
 |--------------------------------------------------------------------------
 | Sort functionality
 |--------------------------------------------------------------------------
 */
export function useSort(data) {
  const sortedColumns = ref({});
  const sortData = column => {
    sortedColumns.value[column] = !sortedColumns.value[column];
    data.value.sort((a, b) => {
      let result;
      if (typeof a[column] === 'string') {
        result = a[column].localeCompare(b[column]);
      } else {
        result = a[column] - b[column];
      }
      return sortedColumns.value[column] ? result : -result;
    });
  };
  return {
    sortData, sortedColumns,
  };
}

/*
|--------------------------------------------------------------------------
| Search functionality
|--------------------------------------------------------------------------
*/
export function useSearch(data, props) {
  const search = ref('');
  const anySearchable = computed(() => props.columns.flatMap(
      obj => obj.search === true ? [obj.key] : []));
  const searchResults = computed(() => {
    const query = search.value.trim().toLowerCase();
    if (!query) {
      return data.value;
    }

    return data.value.filter(item => anySearchable.value.some(
        column => String(item[column]).toLowerCase().includes(query)));
  });

  return {
    search, anySearchable, searchResults,
  };
}


/*
|--------------------------------------------------------------------------
| Pagination functionality
|--------------------------------------------------------------------------
*/
export function usePagination(data, props) {
  let currentPage = ref(1);
  const per_page = parseInt(props.per_page);
  const totalPages = computed(() => {
    return Math.ceil(data.value.length / per_page);
  });
  let paginatedData = computed(() => {
    // Apply pagination based on the currentPage and itemsPerPage variables
    const start = (currentPage.value - 1) * per_page;
    const end = start + per_page;
    return data.value.length ? data.value.slice(start, end) : data.value;
  });
  let pageNumbers = computed(() => {
    return Array.from({length: totalPages.value}, (x, i) => i + 1);
  });

  const previousPage = () => currentPage.value === 1 ?
      null :
      currentPage.value--;
  const nextPage = () => currentPage.value === totalPages.value ?
      null :
      currentPage.value++;
  const goTo = page => currentPage.value = page;
  let from = computed(() => (currentPage.value - 1) * per_page + 1);
  let totalResults = computed(() => data.value.length);
  let to = computed(() => Math.min(currentPage.value * per_page, totalResults.value));
  return {
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
  };
}


/*
|--------------------------------------------------------------------------
| Bulk action functionality
|--------------------------------------------------------------------------
*/
export function useBulkAction(data, columns) {
  const notify = inject('notify');
  const selectedRows = ref([]);
  const selectKey = columns.find(column => column.select)?.key;
  const allSelected = ref(false);
  const selected = () => allSelected.value = selectedRows.value.length ===
      data.value.length;
  const selectAll = () => {
    selectedRows.value = allSelected.value ?
        [...data.value.map(item => item[selectKey])] :
        [];
  };
  const deleteAll = () => {
    if (confirm('Are you sure??')) {
      console.log(selectedRows.value);
      notify.success('Done');
    }
  };
  return {
    selected, selectKey, allSelected, selectedRows, selectAll, deleteAll,
  };
}
