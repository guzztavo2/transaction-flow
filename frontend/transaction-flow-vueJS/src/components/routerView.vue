<script setup lang="ts">
import { defineAsyncComponent, computed } from 'vue';
import { resolveRoute } from '../routes/index';

const view = computed(() => defineAsyncComponent(() => resolveRoute()['component']()));
console.log(view);
</script>

<template>
    <component :is="view" />
</template>