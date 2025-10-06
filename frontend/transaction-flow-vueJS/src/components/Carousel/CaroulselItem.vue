<template>
    <div :id="item.id" class="carousel-item relative w-full">
        <div class="card bg-primary image-full w-full shadow-sm">
            <figure class="w-full">
                <img :src="item.image" :alt="item.alt" />
            </figure>
            <div class="card-body m-12">
                <h2 class="card-title rounded shadow-sm border-b p-2 inline text-2xl text-center">
                    {{ item.title }}
                </h2>
                <div class="flex h-full w-full flex flex-col">
                    <p v-for="(text, index) in item.texts" :key="index" class="text-xl p-5 text-end">
                        {{ text }}
                    </p>
                </div>
            </div>
        </div>
        <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
            <a :href="item.prev" class="btn btn-circle">❮</a>
            <a :href="item.next" class="btn btn-circle">❯</a>
        </div>
    </div>
</template>


<script setup lang="ts">
import type { CarouselItem as CarouselItemType } from '@/types/CarouselItem';
import { onMounted } from 'vue';
onMounted(() => {
    alert("Montado");
});
defineProps<{ item: CarouselItemType }>()
</script>