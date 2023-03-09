<template>

  <!-- Sidebar component, swap this element with another sidebar if you like -->
  <div class="flex flex-grow flex-col overflow-y-auto bg-indigo-700 pt-5">
    <div class="flex flex-shrink-0 items-center px-4">
      <WP/>
    </div>
    <div class="mt-5 flex flex-1 flex-col">
      <nav class="flex-1 space-y-1 px-2 pb-4">

        <router-link active-class="active" class="link" to="/">
          <AdjustmentsVerticalIcon class="icon"/>
          Settings
        </router-link>
        <router-link active-class="active" class="link" to="/logs">
          <ClipboardDocumentListIcon class="icon"/>
          Logs
        </router-link>
      </nav>
    </div>
  </div>
</template>

<script setup>
import {ref, watchEffect} from "vue";
import WP from "@/components/WP.vue";
import {AdjustmentsVerticalIcon, ClipboardDocumentListIcon} from "@heroicons/vue/24/outline";

let props = defineProps(['show']);
let showMobileMenu = ref(false);
const toggleMobileMenu = () => {
  showMobileMenu.value = false;
}
watchEffect(() => {
  showMobileMenu.value = props.show ? true : true
})
</script>
<style scoped>
.icon {
  @apply mr-3 h-6 w-6 flex-shrink-0 text-indigo-300
}

.active {
  @apply bg-indigo-800 text-white;
}

.link {
  @apply text-indigo-100 hover:bg-indigo-600  flex items-center px-2 py-2 text-sm font-medium rounded-md;
}
</style>



