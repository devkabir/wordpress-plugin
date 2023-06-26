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
  // Create a reactive object to store the sort state of each column
  const sortedColumns = ref({});

  // Define a function to sort the data based on the given column
  const sortData = column => {
    // Toggle the sort state of the column
    sortedColumns.value[column] = !sortedColumns.value[column];

    // Sort the data based on the column and its sort state
    data.value.sort((a, b) => {
      let result;
      // Compare string values using localeCompare
      if (typeof a[column] === 'string') {
        result = a[column].localeCompare(b[column]);
      } else {
        // Compare numeric values using subtraction
        result = a[column] - b[column];
      }
      // Reverse the result if the column is not sorted in ascending order
      return sortedColumns.value[column] ? result : -result;
    });
  };

  // Return the sortData function and the sortedColumns object
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
  // Create a reactive object to store the search query
  const search = ref('');

  // Compute the keys of the searchable columns
  const anySearchable = computed(() => props.columns.flatMap(
      obj => obj.search === true ? [obj.key] : []));

  // Compute the search results based on the search query and the searchable columns
  const searchResults = computed(() => {
    // Trim and lowercase the search query
    const query = search.value.trim().toLowerCase();

    // If the query is empty, return all data
    if (!query) {
      return data.value;
    }

    // Filter the data based on the search query and the searchable columns
    return data.value.filter(item => anySearchable.value.some(
        column => String(item[column]).toLowerCase().includes(query)));
  });

  // Return the search object and the searchResults computed property
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
  // Create a reactive object to store the current page
  let currentPage = ref(1);

  // Parse the number of items per page from the props
  const per_page = parseInt(props.per_page);

  // Compute the total number of pages based on the data length and items per page
  const totalPages = computed(() => {
    return Math.ceil(data.value.length / per_page);
  });

  // Compute the paginated data based on the current page and items per page
  let paginatedData = computed(() => {
    // Calculate the start and end indices for the current page
    const start = (currentPage.value - 1) * per_page;
    const end = start + per_page;

    // Slice the data to get only the items for the current page
    return data.value.length ? data.value.slice(start, end) : data.value;
  });

  // Compute an array of page numbers for pagination controls
  let pageNumbers = computed(() => {
    return Array.from({length: totalPages.value}, (x, i) => i + 1);
  });

  // Define methods to navigate between pages
  const previousPage = () => currentPage.value === 1 ?
      null :
      currentPage.value--;
  const nextPage = () => currentPage.value === totalPages.value ?
      null :
      currentPage.value++;
  const goTo = page => currentPage.value = page;

  // Compute some additional properties for displaying pagination information
  let from = computed(() => (currentPage.value - 1) * per_page + 1);
  let totalResults = computed(() => data.value.length);
  let to = computed(() => Math.min(currentPage.value * per_page, totalResults.value));

  // Return the state variables and methods
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
  // Inject the notify function from the parent component
  const notify = inject('notify');

  // Create a reactive object to store the selected rows
  const selectedRows = ref([]);

  // Find the key of the column used for selecting rows
  const selectKey = columns.find(column => column.select)?.key;

  // Create a reactive object to store the state of the "select all" checkbox
  const allSelected = ref(false);

  // Define a method to update the state of the "select all" checkbox
  const selected = () => allSelected.value = selectedRows.value.length ===
      data.value.length;

  // Define a method to select or deselect all rows
  const selectAll = () => {
    selectedRows.value = allSelected.value ?
        [...data.value.map(item => item[selectKey])] :
        [];
  };

  // Define a method to delete all selected rows
  const deleteAll = () => {
    if (confirm('Are you sure??')) {
      console.log(selectedRows.value);
      notify.success('Done');
    }
  };

  // Return the state variables and methods
  return {
    selected, selectKey, allSelected, selectedRows, selectAll, deleteAll,
  };
}

