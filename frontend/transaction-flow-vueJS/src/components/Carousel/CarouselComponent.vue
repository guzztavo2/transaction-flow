<template>
    <div class="carousel w-full">
        <CaroulselItem v-for="(slide) in items" :key="slide.id" :item="slide" />
    </div>
</template>

<script setup lang="ts">
import type { CarouselItem as carouselType } from '@/types/CarouselItem';
import CaroulselItem from './CaroulselItem.vue';
defineProps<{ items: carouselType[] }>()



</script>