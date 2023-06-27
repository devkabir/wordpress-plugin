import { computed, inject, onBeforeMount, ref } from 'vue';

export function useDataList(props) {
  // Reactive state variables
  const isLoading = ref(false);
  const axios = inject('axios');
  const notify = inject('notify');
  const apiResponse = ref({});
  const selectedTab = ref('');

  // Fetch data before the component is mounted
  const fetchData = async () => {
    if (isLoading.value) {
      return;
    }
    isLoading.value = true;

    try {
      // Make API call to fetch data
      const { data } = await axios.get(props.endpoint);
      apiResponse.value = data;
      // Set the initially selected tab to the first tab in the response
      selectedTab.value = Object.keys(apiResponse.value)[0];
    } catch (error) {
      // Handle error
      notify.error(error.message);
    } finally {
      isLoading.value = false;
    }
  };

  // Compute the available tabs from the API response
  const tabs = computed(() => Object.keys(apiResponse.value));

  // Compute the content of the selected tab
  const selectedTabContent = computed(() => apiResponse.value[selectedTab.value]);

  // Function to select a tab
  const selectTab = (tab) => {
    selectedTab.value = tab;
  };

  // Function to clean data of a specific tab
  const clean = async (tab) => {
    if (isLoading.value) {
      return;
    }
    isLoading.value = true;

    try {
      // Make API call to clean the data of the specified tab
      await axios.get(`${props.endpoint}/clean`, {
        params: {
          file: tab,
        },
      });
      delete apiResponse.value[tab];
      // Display success notification and remove the cleaned tab from the response
      notify.success('Data cleaned successfully');
    } catch (error) {
      // Handle error
      notify.error(error.message);
    } finally {
      isLoading.value = false;
    }
  };

  // Fetch data before the component is mounted
  onBeforeMount(fetchData);

  // Return the reactive variables and functions
  return {
    tabs,
    selectedTab,
    selectTab,
    clean,
    isLoading,
    selectedTabContent,
  };
}
