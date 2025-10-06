<template>
    <template v-for="(value, index) in contentToRender[languageStore.language as Language]" :key="value">
        <template v-if="index == 0">
            <h1 class="mx-10 mt-10 text-xl uppercase font-light text-center">{{ value }}</h1>
            <div class="flex flex-row justify-center">
                <div class="divider max-w-52 w-full"></div>
            </div>
        </template>

        <p v-if="index == 1" class="text-center font-bold text-error">{{ value }}</p>

        <div v-if="index == 2" class="flex justify-center my-4">
            <a href="/" class="btn btn-xs sm:btn-sm md:btn-md">{{ value }}</a>
        </div>

        <span v-if="index === 3" class="text-center font-semibold underline">
            {{contentToRender[languageStore.language as Language].filter((_, index) => index >= 3)
                .map((value, index) => index === 0 ? `${value} ${timeToWait}` : value)
                .join(' ')}}
        </span>

    </template>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { currentRoute, navigateTo } from '@/routes/index';
import { useLanguageStore, type Language } from "@/stores/language";

const languageStore = useLanguageStore();
const timeToWait = ref(10);
import { onMounted } from 'vue';
const route = ref(currentRoute.value);
onMounted(() => {
    const timer = setInterval(() => {
        timeToWait.value -= 1;
        if (timeToWait.value <= 0) {
            navigateTo('/');
            clearInterval(timer);
        }
    }, 1000);
});

const contentToRender: Record<Language, string[]> = {
    'BR': ['Página não localizada', 'Você tentou acessar uma página que não existe: ' + route.value,
        'Clique aqui para voltar ao inicio', `Ou você pode esperar ( `, ` segundos ) e será redirecionado automaticamente.`
    ],
    'EN': ['Page not found', 'You tried to access a page that doesn\'t exist: ' + route.value,
        'Click here to return to the beginning', `Or you can wait ( `, ` seconds ) and you will be redirected automatically.`]
};
</script>
