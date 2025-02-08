<template>
    <label class="relative inline-flex items-center cursor-pointer">
      <input type="checkbox" v-model="isDarkMode" class="sr-only peer" @change="toggleDarkMode" />
      <div
        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer 
        peer-checked:after:translate-x-5 peer-checked:after:border-white
        after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white 
        after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 
        after:transition-all peer-checked:bg-black"
      ></div>
    </label>
  </template>
  
  <script setup>
  import { ref } from "vue";
  
  const isDarkMode = ref(false);
  
  const toggleDarkMode = () => {
    document.documentElement.classList.toggle("dark", isDarkMode.value);
  };
  </script>
  