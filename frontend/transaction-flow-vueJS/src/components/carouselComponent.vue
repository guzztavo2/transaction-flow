<template>
    <div :id="item.id" class="carousel-item relative w-full">
        <div class="card bg-primary image-full w-full shadow-sm">
            <figure class="w-full p-2">
                <img class="w-full opacity-75" :src="item.image" :alt="item.alt" />
            </figure>

            <div class="card-body p-5 m-5 min-h-[400px] sm:min-h-0 md:m-10">
                <h2 class="card-title rounded shadow-sm border-b p-2 inline text-2xl text-center">
                    {{ item.title }}
                </h2>

                <div class="flex h-full w-full flex flex-col items-center justify-center p-4">
                    <div class="w-full h-full">
                        <slot></slot>
                    </div>
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
defineProps<{ item: CarouselItemType }>()
</script>