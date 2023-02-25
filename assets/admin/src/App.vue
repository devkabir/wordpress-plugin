<template>

  <div class="mr-4 bg-slate-100 h-full-screen overflow-y-auto">
    <Navigation/>
    <div class="mx-auto shadow max-w-7xl py-6 sm:px-6 lg:px-8">
      <router-view/>
    </div>
  </div>

</template>
<script>
import Dashboard from "./pages/Settings.vue";
import Navigation from "./components/Navigation.vue";

export default {
  components: {Navigation, Dashboard}
}
</script>