<script setup lang="ts">
import carouselComponent from '@/components/Carousel/CarouselComponent.vue';
import type { CarouselItem } from '@/types/CarouselItem'

const slides: CarouselItem[] = [
    {
        id: 'slide1',
        image: 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp',
        alt: 'Shoes',
        title: 'TransactionFlow - Faça suas transações seguras!',
        texts: [
            'Crie sua conta gratuitamente!',
            'Eiusmod nisi ad esse. Aute cupidatat eu reprehenderit incididunt proident.'
        ],
        prev: '#slide2',
        next: '#slide2'
    },
    {
        id: 'slide2',
        image: 'https://picsum.photos/800/400',
        alt: 'Outro exemplo',
        title: 'Outro Slide',
        texts: ['Texto 1', 'Texto 2'],
        prev: '#slide1',
        next: '#slide1'
    }
]


</script>

<template>
    <carouselComponent :items="slides" />
</template>
