<template>
    <div class="flex flex-col w-full h-full justify-content-center items-center flex-nowrap py-2">
        <div class="flex flex-row w-full max-w-6xl flex-1 flex-nowrap">
            <div class="divider divider-horizontal hidden sm:flex"></div>
            <div class="flex flex-col w-full flex-1 flex-nowrap justify-between">
                <slot></slot>
                <footerComponent></footerComponent>
            </div>
            <div class="divider divider-horizontal hidden sm:flex"></div>
        </div>
    </div>
</template>


<script setup lang="ts">
import footerComponent from '../footerComponent.vue';
</script>