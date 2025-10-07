<script setup lang="ts">
import caroulselItem from '@/components/layout/caroulselItem.vue';
import type { CarouselItem } from '@/types/CarouselItem'
import { ref, watch } from 'vue';
import { useLanguageStore } from '@/stores/language';
const languageStore = useLanguageStore();
const slides = ref<CarouselItem[]>([]);

function slidesUpdate() {
    slides.value = <CarouselItem[]>([
        {
            id: 'slide1',
            image: 'https://picsum.photos/800/400',
            alt: 'Shoes',
            title: languageStore.language === "BR" ? 'TransactionFlow - Faça suas transações seguras!' : 'TransactionFlow - Make your transactions secure!',
            prev: '#slide2',
            next: '#slide2'
        },
        {
            id: 'slide2',
            image: 'https://picsum.photos/800/400',
            alt: languageStore.language === "BR" ? 'Outro exemplo' : "Another example",
            title: 'Slide de teste',
            prev: '#slide1',
            next: '#slide1'
        }
    ]);
}
slidesUpdate();

watch(() => languageStore.language, () => {
    slidesUpdate();
});

</script>

<template>
    <div class="carousel w-full">
        <caroulselItem v-for="(slide, index) in slides" :key="slide.id" :item="slide">
            <template v-if="index === 0">
                <div class>
                    <p class="text-xl text-center font-light mt-2">CRIE SUA CONTA GRATUITAMENTE!</p>
                    <div class="flex flex-row justify-center items-center w-full p-4">
                        <a class="btn btn-outline btn-accent">Clique aqui e crie sua conta!</a>
                    </div>
                    <span class="divider"></span>
                    <p class="text-xl text-center">Além de outros beneficíos!</p>

                    <div
                        class="flex flex-col justify-center items-center text-center uppercase w-full border-y p-1 mt-2">
                        <ul class="list-disc text-xl list-inside font-light leading-loose">
                            <li>Nulla deserunt dolore do aute pariatur cillum aliqua ad.</li>
                            <li>Sunt esse veniam ipsum labore magna adipisicing irure labore sunt.</li>
                            <li>Elit nulla laborum eu consectetur consequat enim culpa.</li>
                        </ul>
                    </div>
                </div>
            </template>
            <template v-if="index === 1">
                <div class>
                    <p class="text-xl text-center font-light mt-2">Sit commodo sunt dolor id nulla consectetur enim
                        consectetur.</p>
                    <div class="flex flex-row justify-center items-center w-full p-4">
                        <p class="text-xl text-center font-light mt-2">Ea exercitation voluptate qui incididunt
                            voluptate in. Ut
                            irure incididunt enim deserunt consequat ea exercitation. Aliqua ea sit officia aliquip in
                            voluptate. Incididunt irure adipisicing commodo deserunt et reprehenderit.</p>
                    </div>
                    <span class="divider"></span>
                    <p class="text-xl text-center">Ullamco pariatur adipisicing nostrud ad laboris enim tempor laboris.
                    </p>
                </div>
            </template>
        </caroulselItem>
    </div>

    <span class="divider"></span>
    <div class="flex flex-col w-full">
        <h1 class="uppercase text-2xl font-bold border-b p-2 mt-10">Sobre nós</h1>
        <p class="p-4 border-b">
            Reprehenderit eu et anim sint. Deserunt anim fugiat mollit excepteur cupidatat in magna ea. Adipisicing
            cupidatat sit incididunt nulla duis anim ea. Fugiat est aute mollit eu minim excepteur eu. Commodo aliquip
            dolor
            adipisicing Lorem dolor ad elit incididunt adipisicing cupidatat laboris enim elit consectetur.
        </p>
        <p class="p-4 border-b">

            Veniam aute mollit dolore eu velit quis ad culpa. Anim ut officia laboris deserunt irure. Officia nostrud
            tempor
            aute dolore. Laboris nulla sunt excepteur sunt adipisicing duis adipisicing nulla magna consequat et. Dolor
            dolor reprehenderit magna minim culpa nostrud elit consectetur velit enim laborum mollit adipisicing sit. Id
            ut
            occaecat sit ad qui reprehenderit Lorem aliqua dolore commodo.
        </p>
        <h1 class="uppercase text-2xl font-bold border-b p-2 mt-10">O que você encontrará</h1>
        <p class="p-4 border-b">
            Reprehenderit eu et anim sint. Deserunt anim fugiat mollit excepteur cupidatat in magna ea. Adipisicing
            cupidatat sit incididunt nulla duis anim ea. Fugiat est aute mollit eu minim excepteur eu. Commodo aliquip
            dolor
            adipisicing Lorem dolor ad elit incididunt adipisicing cupidatat laboris enim elit consectetur.
        </p>
        <p class="p-4">

            Veniam aute mollit dolore eu velit quis ad culpa. Anim ut officia laboris deserunt irure. Officia nostrud
            tempor
            aute dolore. Laboris nulla sunt excepteur sunt adipisicing duis adipisicing nulla magna consequat et. Dolor
            dolor reprehenderit magna minim culpa nostrud elit consectetur velit enim laborum mollit adipisicing sit. Id
            ut
            occaecat sit ad qui reprehenderit Lorem aliqua dolore commodo.
        </p>
        <h1 class="uppercase text-2xl font-bold border-b p-2 mt-10">A idéia</h1>
        <p class="p-4 border-b">
            Reprehenderit eu et anim sint. Deserunt anim fugiat mollit excepteur cupidatat in magna ea. Adipisicing
            cupidatat sit incididunt nulla duis anim ea. Fugiat est aute mollit eu minim excepteur eu. Commodo aliquip
            dolor
            adipisicing Lorem dolor ad elit incididunt adipisicing cupidatat laboris enim elit consectetur.
        </p>
        <p class="p-4">

            Veniam aute mollit dolore eu velit quis ad culpa. Anim ut officia laboris deserunt irure. Officia nostrud
            tempor
            aute dolore. Laboris nulla sunt excepteur sunt adipisicing duis adipisicing nulla magna consequat et. Dolor
            dolor reprehenderit magna minim culpa nostrud elit consectetur velit enim laborum mollit adipisicing sit. Id
            ut
            occaecat sit ad qui reprehenderit Lorem aliqua dolore commodo.
        </p>
        <p class="p-4 border-b">
            Reprehenderit eu et anim sint. Deserunt anim fugiat mollit excepteur cupidatat in magna ea. Adipisicing
            cupidatat sit incididunt nulla duis anim ea. Fugiat est aute mollit eu minim excepteur eu. Commodo aliquip
            dolor
            adipisicing Lorem dolor ad elit incididunt adipisicing cupidatat laboris enim elit consectetur.
        </p>
        <p class="p-4">

            Veniam aute mollit dolore eu velit quis ad culpa. Anim ut officia laboris deserunt irure. Officia nostrud
            tempor
            aute dolore. Laboris nulla sunt excepteur sunt adipisicing duis adipisicing nulla magna consequat et. Dolor
            dolor reprehenderit magna minim culpa nostrud elit consectetur velit enim laborum mollit adipisicing sit. Id
            ut
            occaecat sit ad qui reprehenderit Lorem aliqua dolore commodo.
        </p>
        <p class="p-4 border-b">
            Reprehenderit eu et anim sint. Deserunt anim fugiat mollit excepteur cupidatat in magna ea. Adipisicing
            cupidatat sit incididunt nulla duis anim ea. Fugiat est aute mollit eu minim excepteur eu. Commodo aliquip
            dolor
            adipisicing Lorem dolor ad elit incididunt adipisicing cupidatat laboris enim elit consectetur.
        </p>
        <p class="p-4">

            Veniam aute mollit dolore eu velit quis ad culpa. Anim ut officia laboris deserunt irure. Officia nostrud
            tempor
            aute dolore. Laboris nulla sunt excepteur sunt adipisicing duis adipisicing nulla magna consequat et. Dolor
            dolor reprehenderit magna minim culpa nostrud elit consectetur velit enim laborum mollit adipisicing sit. Id
            ut
            occaecat sit ad qui reprehenderit Lorem aliqua dolore commodo.
        </p>
        <p class="p-4 border-b">
            Reprehenderit eu et anim sint. Deserunt anim fugiat mollit excepteur cupidatat in magna ea. Adipisicing
            cupidatat sit incididunt nulla duis anim ea. Fugiat est aute mollit eu minim excepteur eu. Commodo aliquip
            dolor
            adipisicing Lorem dolor ad elit incididunt adipisicing cupidatat laboris enim elit consectetur.
        </p>
        <p class="p-4">

            Veniam aute mollit dolore eu velit quis ad culpa. Anim ut officia laboris deserunt irure. Officia nostrud
            tempor
            aute dolore. Laboris nulla sunt excepteur sunt adipisicing duis adipisicing nulla magna consequat et. Dolor
            dolor reprehenderit magna minim culpa nostrud elit consectetur velit enim laborum mollit adipisicing sit. Id
            ut
            occaecat sit ad qui reprehenderit Lorem aliqua dolore commodo.
        </p>
    </div>
</template>
