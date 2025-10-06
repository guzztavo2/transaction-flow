<template>
  <div class="navbar bg-primary text-primary-content p-4 rounded shadow-lg">
    <div class="navbar-start">
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
          </svg>
        </div>
        <ul tabindex="0"
          class="menu bg-primary border-2 border-solid border-info menu-sm dropdown-content rounded-box z-1 mt-3 w-52 p-4 text-4xl font-bold shadow">

          <li v-for="(value, index) in contentMenu[languageStore.language as Language]" :key="value">
            <template v-if="index == 0">
              <a class="block text-center" href="/">{{ value }}</a>
            </template>
            <template v-if="index == 1">
              <a class="block text-center" href="/about">{{ value }}</a>
            </template>
            <template v-if="index == 2">
              <a class="block text-center" href="/login">{{ value }}</a>
            </template>
            <template v-if="index == 3">
              <a class="block text-center" href="/register">{{ value }}</a>
            </template>
          </li>

        </ul>
      </div>
    </div>
    <div class="navbar-center">
      <a href="/" class="btn btn-ghost text-xl">TransactionFLOW</a>
    </div>
    <div class="navbar-end">
      <div class="flex flex-row flex-wrap align-center justify-center gap-4">
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" class="toggle theme-controller" :checked="theme === 'dark'"
            @change="theme = theme === 'light' ? 'dark' : 'light'" />
          <span class="shadow block bg-neutral text-xl p-2 rounded">{{ theme === 'light' ? '🌞' : '🌙' }}</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" class="toggle theme-controller" :checked="languageStore.language === 'BR'"
            @change="languageStore.toggleLanguage()" />
          <span class="shadow block bg-neutral text-xl p-2 rounded">{{ languageStore.language === 'BR' ? '🇧🇷' :
            '🇺🇸'
          }}</span>
        </label>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { useLanguageStore, type Language } from "@/stores/language";

const theme = ref('dark');

const languageStore = useLanguageStore();

function handleChangeTheme() {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    theme.value = savedTheme;
    document.documentElement.setAttribute("data-theme", savedTheme);
  }

  watch(theme, (newTheme) => {
    localStorage.setItem("theme", newTheme);
    document.documentElement.setAttribute("data-theme", newTheme);
  });
}

onMounted(() => {
  handleChangeTheme();
});

const contentMenu: Record<Language, string[]> = {
  "BR": ["Início", "Sobre", "Entrar", "Registrar"],
  "EN": ["Home", "About", "Login", "Register",]
}

</script>
