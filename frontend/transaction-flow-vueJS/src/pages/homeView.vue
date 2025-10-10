<script setup lang="ts">
import carouselComponent from '@/components/carouselComponent.vue';
import loginLayout from '@/components/layout/loginLayout.vue';
import type { CarouselItem } from '@/types/CarouselItem'
import { ref, watch } from 'vue';
import { useLanguageStore } from '@/stores/language';

const languageStore = useLanguageStore();
const slides = ref<CarouselItem[]>([]);

const textContents = ref();

function textContentsUpdate() {
    if (languageStore.language == 'BR')
        textContents.value = {
            'carousel': {
                0: {
                    'title': 'CRIE SUA CONTA GRATUITAMENTE!',
                    'subtitle': 'Clique aqui e crie sua conta!',
                    'substitle_2': 'Além de outros beneficíos'
                }

            },
            'content': ['Sobre nós', 'O que você encontrará', 'A idéia']
        };
    else
        textContents.value = {
            'carousel': {
                0: {
                    'title': 'CREATE YOUR ACCOUNT FOR FREE!',
                    'subtitle': 'Click here and create your account!',
                    'substitle_2': 'Plus other benefits!'
                }
            },
            'content': ['About Us', 'What You\'ll Find', 'The Idea']
        };
}
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
            title: languageStore.language === "BR" ? 'Slide de teste' : "Test slide",
            prev: '#slide1',
            next: '#slide1'
        }
    ]);
}
slidesUpdate();
textContentsUpdate();

watch(() => languageStore.language, () => {
    slidesUpdate();
    textContentsUpdate();
});

</script>

<template>
    <div class="carousel w-full">
        <carouselComponent v-for="(slide, index) in slides" :key="slide.id" :item="slide">
            <template v-if="index === 0">
                <div class>
                    <p class="text-xl text-center font-light mt-2">{{ textContents['carousel'][index]['title'] }}</p>
                    <div class="flex flex-row justify-center items-center w-full p-4">
                        <a class="btn btn-outline btn-accent">{{ textContents['carousel'][index]['subtitle'] }}</a>
                    </div>
                    <span class="divider"></span>


                    <div
                        class="flex flex-col justify-center items-center text-center uppercase w-full border-y p-1 mt-2">
                        <p class="text-xl text-start my-2">{{ textContents['carousel'][index]['subtitle_2'] }}</p>
                        <ul
                            class="block list-disc text-xl list-inside font-light leading-loose w-auto sm:w-1/2 text-center">
                            <li class="border-t my-1 py-2">Nulla deserunt dolore do aute pariatur cillum aliqua ad.</li>
                            <li class="border-t my-1 py-2">Sunt esse veniam ipsum labore magna adipisicing irure labore
                                sunt.
                            </li>
                            <li class="border-t my-1 py-2">Elit nulla laborum eu consectetur consequat enim culpa.</li>
                        </ul>
                    </div>
                </div>
            </template>
            <template v-if="index === 1">
                <div class="w-full h-full flex flex-col justify-around mt-2">
                    <p class="flex-none text-xl text-center w-full font-light uppercase">Sit commodo sunt
                        dolor
                        id
                        nulla
                        consectetur
                        enim
                        consectetur.</p>
                    <p class="flex-none text-xl text-center font-normal">Ea exercitation voluptate qui
                        incididunt
                        voluptate in. Ut
                        irure incididunt enim deserunt consequat ea exercitation. Aliqua ea sit officia aliquip in
                        voluptate. Incididunt irure adipisicing commodo deserunt et reprehenderit.</p>
                    <span class="divider"></span>
                    <p class="flex-none text-xl text-center">Ullamco pariatur adipisicing nostrud ad laboris
                        enim
                        tempor laboris.
                    </p>
                </div>
            </template>
        </carouselComponent>
    </div>

    <span class="divider"></span>
    <div class="flex flex-col w-full">
        <h1 class="uppercase text-2xl font-bold border-b p-2 mt-10">{{ textContents['content'][0] }}</h1>
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
        <h1 class="uppercase text-2xl font-bold border-b p-2 mt-10">{{ textContents['content'][1] }}</h1>
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
        <div class="hero min-h-none"
            style="background-image: url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp);">
            <div class="hero-overlay"></div>
            <div class="hero-content text-neutral-content text-center">
                <div class="max-w-md">
                    <h1 class="mb-5 text-5xl font-bold">Hello there</h1>
                    <p class="mb-5">
                        Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
                        quasi. In deleniti eaque aut repudiandae et a id nisi.
                    </p>
                    <button class="btn btn-primary">Get Started</button>
                </div>
            </div>
        </div>
        <h1 class="uppercase text-2xl font-bold border-b p-2 mt-10">{{ textContents['content'][2] }}</h1>
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
        <loginLayout></loginLayout>
    </div>
</template>
