import { ref, watch } from 'vue'
import { defineStore } from 'pinia'
export type Language = "BR" | "EN";

export const useLanguageStore = defineStore('language', () => {
  const language = ref(localStorage.getItem('language') || 'EN');

  watch(language, (newLanguage) => {
    localStorage.setItem('language', newLanguage);
    document.documentElement.setAttribute("data-language", newLanguage)
  }, { immediate: true });

  function toggleLanguage() {
    language.value = language.value === 'EN' ? 'BR' : 'EN'
  }

  return { language, toggleLanguage }
});
