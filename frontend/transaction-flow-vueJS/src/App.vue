<script setup lang="ts">
import navBar from './components/layout/navBar.vue';
import routerView from './components/routerView.vue';
import { onMounted } from 'vue';
import { ref } from 'vue';
const titles: string[] = ["-------- Transaction Flow App --------", "-------- Registre-se! --------", "-------- Bem-vindo! --------"];
const currentTitle = ref('');

let index: number = 0, charIndex = 0;
let typerInterval: unknown;

function typeWriterEffect() {
  const phrase = titles[index] ?? null;
  if (!phrase) {
    index += 1;
    return typeWriterEffect();
  }
  typerInterval = setInterval(() => {
    if (charIndex < phrase.length) {
      currentTitle.value += phrase[charIndex];
      document.title = currentTitle.value; // atualiza o título da aba
      charIndex++;
    } else {
      clearInterval(typerInterval as number);

      // espera 5 segundos antes de trocar
      setTimeout(() => {
        // reseta para próxima frase
        index = (index + 1) % titles.length;
        charIndex = 0;
        currentTitle.value = "";
        typeWriterEffect();
      }, 5000);
    }
  }, 150); // velocidade da digitação (ms)
}
onMounted(() => typeWriterEffect());
</script>

<template>
  <div class="flex flex-col w-full h-screen justify-content-center align-items-center flex-nowrap p-1">
    <navBar></navBar>

    <routerView></routerView>
  </div>
</template>

<style></style>
