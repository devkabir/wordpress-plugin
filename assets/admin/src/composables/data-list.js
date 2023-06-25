import {ref, computed, watch, onBeforeMount, inject} from 'vue';

export function useDataList(props) {
  const isLoading = ref(false);
  const axios = inject('axios');
  const notify = inject('notify');
  const apiResponse = ref([]);
  const selectedTab = ref();

  const fetchData = async () => {
    if (isLoading.value) return;
    isLoading.value = true;

    try {
      const response = await axios.get(props.endpoint);
      apiResponse.value = response.data;
      selectedTab.value = Object.keys(apiResponse.value)[0];
    } catch (error) {
      notify.error(error.message);
    } finally {
      isLoading.value = false;
    }
  };

  onBeforeMount(fetchData);

  const tabs = computed(() => Object.keys(apiResponse.value));

  const selectedTabContent = computed(() => apiResponse.value[selectedTab.value]);

  const selectTab = (tab) => {
    selectedTab.value = tab;
  };

  watch(selectedTab, () => {
    selectedTabContent.value = apiResponse.value[selectedTab.value];
  });

  const clean = async (tab) => {
    if (isLoading.value) return;
    isLoading.value = true;

    try {
      await axios.get(`${props.endpoint}/clean`, {
        params: {
          file: tab,
        },
      });
      notify.success('Data cleaned successfully');
      delete apiResponse.value[tab];
    } catch (error) {
      notify.error(error.message);
    } finally {
      isLoading.value = false;
    }
  };

  return {
    tabs,
    selectedTab,
    selectTab,
    clean,
    isLoading,
    selectedTabContent,
  };
}
