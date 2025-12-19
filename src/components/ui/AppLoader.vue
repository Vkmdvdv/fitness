<template>
    <div class="loader-container" :class="{ fullscreen, 'dark-mode': isDarkMode }">
      <div class="loader">
        <div class="lightning-container">
          <div class="lightning-icon" :class="{ 'dark-mode': isDarkMode }"></div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  import { useThemeStore } from '@/stores/theme'
  
  defineProps({
    message: {
      type: String,
      default: 'Загрузка...'
    },
    fullscreen: {
      type: Boolean,
      default: true
    }
  })
  
  // Проверяем, включена ли темная тема
  const themeStore = useThemeStore()
  const isDarkMode = computed(() => themeStore.darkMode)
  </script>
  
  <style scoped>
  .loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
  }
  
  .loader-container.fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.9);
    z-index: 9999;
    backdrop-filter: blur(5px);
  }
  
  .loader-container.fullscreen.dark-mode {
    background-color: rgba(17, 24, 39, 0.9);
  }
  
  .loader {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  
  .lightning-container {
    position: relative;
    width: 200px;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .lightning-icon {
    width: 180px;
    height: 180px;
    background-color: transparent;
    mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M11 15H6L13 1V9H18L11 23V15Z'/%3E%3C/svg%3E");
    mask-size: contain;
    mask-repeat: no-repeat;
    mask-position: center;
    animation: color-change 3s infinite alternate, scale-pulse 2s infinite;
    filter: drop-shadow(0 0 10px rgba(99, 102, 241, 0.7));
  }
  
  @keyframes color-change {
    0% {
      background-color: #6366f1; /* indigo-500 */
    }
    25% {
      background-color: #8b5cf6; /* violet-500 */
    }
    50% {
      background-color: #3b82f6; /* blue-500 */
    }
    75% {
      background-color: #6366f1; /* indigo-500 */
    }
    100% {
      background-color: #8b5cf6; /* violet-500 */
    }
  }
  
  @keyframes scale-pulse {
    0% {
      transform: scale(0.9);
      filter: drop-shadow(0 0 5px rgba(99, 102, 241, 0.5));
    }
    50% {
      transform: scale(1.2);
      filter: drop-shadow(0 0 20px rgba(99, 102, 241, 0.8));
    }
    100% {
      transform: scale(0.9);
      filter: drop-shadow(0 0 5px rgba(99, 102, 241, 0.5));
    }
  }
  </style>