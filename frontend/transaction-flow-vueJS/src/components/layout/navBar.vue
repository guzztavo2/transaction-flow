<template>
  <div class="navbar bg-primary text-primary-content">
    <div class="navbar-start">
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
          </svg>
        </div>
        <ul tabindex="0"
          class="menu bg-primary border-2 border-solid border-info menu-sm dropdown-content rounded-box z-1 mt-3 w-52 p-4 text-4xl font-bold shadow">
          <li><a class="block text-center">Login</a></li>
          <li><a class="block text-center">Register</a></li>
          <li><a class="block text-center">About</a></li>
        </ul>
      </div>
    </div>
    <div class="navbar-center">
      <a href="/" class="btn btn-ghost text-xl">TransactionFLOW</a>
    </div>
    <div class="navbar-end">
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" class="toggle theme-controller" :checked="theme === 'dark'"
          @change="theme = theme === 'light' ? 'dark' : 'light'" />
        <span>{{ theme === 'light' ? '🌞' : '🌙' }}</span> </label>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from "vue";

const theme = ref('dark');

onMounted(() => {
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme) {
    theme.value = savedTheme;
    document.documentElement.setAttribute("data-theme", savedTheme);
  }

  watch(theme, (newTheme) => {
    localStorage.setItem("theme", newTheme);
    document.documentElement.setAttribute("data-theme", newTheme);
  });
})
</script>
