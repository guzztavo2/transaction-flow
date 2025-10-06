<script setup lang="ts">
import { defineAsyncComponent, computed } from 'vue';
import { resolveRoute } from '../routes/index';
import pageLayout from './layout/pageLayout.vue';

const view = computed(() => {
    const route = defineAsyncComponent(resolveRoute().component);
    return route;
});
</script>

<template>
    <page-layout>
        <component :is="view" />
    </page-layout>
</template>