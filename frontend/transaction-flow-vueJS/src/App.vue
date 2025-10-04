<script setup lang="ts">
import navBar from './components/layout/navBar.vue';
import routerView from './components/routerView.vue';
</script>

<template>
  <div class="flex flex-col w-full h-screen justify-content-center align-items-center flex-nowrap p-1">
    <navBar></navBar>

    <routerView></routerView>
  </div>
</template>

<style></style>
