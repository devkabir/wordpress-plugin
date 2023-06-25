import {computed, inject, onBeforeMount, onMounted, ref, watch} from 'vue';

export function useDataTable(props) {
  // State variables
  const isLoading = ref(false);
  const axios = inject('axios');
  const notify = inject('notify');
  const data = ref([]);
  const apiResponse = ref([]);
  const selectedRows = ref({});
  const selectAll = computed({
    get: () => false,
    set: (val) => {
      if (val) {
        data.value.forEach(row => {
          selectedRows.value[row.id] = true;
        });
      } else {
        selectedRows.value = {};
      }
    },
  });

  // Fetch data before the table is mounted
  onBeforeMount(async () => {
    if (isLoading.value) return;
    isLoading.value = true;
    try {
      const response = await axios.get(props.endpoint);
      data.value = response.data;
      apiResponse.value = response.data;
    } catch (error) {
      notify.error(error.message);
    } finally {
      isLoading.value = false;
    }
  });

  // Format column data based on type
  const formatColumnData = (data, column) => {
    if (column.hasOwnProperty('type')) {
      let text = column.hasOwnProperty('text') ? column.text : data;
      if (column.type === 'link') {
        return `<a href="${data}" target="_blank">${text}</a>`;
      }
      if (column.type === 'email') {
        return `<a href="mailto:${data}" target="_blank">${text}</a>`;
      }
      if (column.type === 'tel') {
        return `<a href="tel:${data}" target="_blank">${text}</a>`;
      }
      if (column.type === 'date') {
        let date = new Date(data);
        return date.toDateString();
      }
    } else {
      return data;
    }
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
  /*
  |--------------------------------------------------------------------------
  | Sort functionality
  |--------------------------------------------------------------------------
  */
  // Sort table based on column definition
  const sortedColumns = ref({});
  const sortData = column => {
    sortedColumns.value[column] = !sortedColumns.value[column];
    if (sortedColumns.value[column]) {
      data.value.sort((a, b) => {
        if (typeof a[column] === 'string') {
          return a[column].localeCompare(b[column]);
        } else {
          return a[column] - b[column];
        }
      });
    } else {
      data.value.sort((a, b) => {
        if (typeof a[column] === 'string') {
          return b[column].localeCompare(a[column]);
        } else {
          return b[column] - a[column];
        }
      });
    }
  };

  /*
  |--------------------------------------------------------------------------
  | Search functionality
  |--------------------------------------------------------------------------
  */
  const search = ref(null);
  const searchTimeoutId = ref();
  const anySearchable = ref([]);
  // Collect searchable columns
  onMounted(() => {
    anySearchable.value = props.columns.filter(obj => obj.search === true).map(obj => obj.key);
  });
  // Watch search input and filter data accordingly
  watch(search, (value) => {
    if (value.length !== 0) {
      clearTimeout(searchTimeoutId.value);
      searchTimeoutId.value = setTimeout(() => {
        data.value = apiResponse.value.filter(item =>
            anySearchable.value.some(column =>
                String(item[column]).toLowerCase().includes(search.value.toLowerCase())
            )
        );
      }, 300);
    } else {
      data.value = apiResponse.value;
    }
  });


  // Return the state variables and functions
  return {
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
  };
}