<template>
    <div class="flex flex-col w-full h-full justify-content-center items-center flex-nowrap py-2">
        <div class="flex flex-row w-full max-w-6xl flex-1 flex-nowrap">
            <div class="divider divider-horizontal hidden sm:block"></div>
            <div class="flex flex-col w-full flex-1 flex-nowrap">
                <slot></slot>
            </div>
            <div class="divider divider-horizontal hidden sm:block"></div>
        </div>
    </div>
</template>


<script setup lang="ts">

</script>