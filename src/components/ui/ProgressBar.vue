<template>
    <div
      class="progress-bar"
      :class="{ 'is-loading': isLoading, 'dark-mode': isDarkMode }"
      :style="{ '--progress-width': `${progress}%` }"
    ></div>
  </template>
  
  <script setup>
  import { ref, computed, watch, onMounted } from 'vue'
  import { useThemeStore } from '@/stores/theme'
  
  const props = defineProps({
    isLoading: {
      type: Boolean,
      required: true
    }
  })
  
  const progress = ref(0)
  const themeStore = useThemeStore()
  const isDarkMode = computed(() => themeStore.darkMode)
  
  // Следим за изменением состояния загрузки
  watch(() => props.isLoading, (newValue) => {
    if (newValue) {
      // Если началась загрузка, сбрасываем прогресс и начинаем анимацию
      progress.value = 0
      startProgressAnimation()
    } else {
      // Если загрузка завершена, быстро доводим прогресс до 100%
      progress.value = 100
      setTimeout(() => {
        progress.value = 0
      }, 500) // Скрываем полосу через 500мс после завершения
    }
  })
  
  // Функция для анимации прогресса
  const startProgressAnimation = () => {
    // Быстро доходим до 30%
    setTimeout(() => {
      progress.value = 30
    }, 100)
  
    // Затем медленнее до 70%
    setTimeout(() => {
      progress.value = 70
    }, 500)
  
    // И еще медленнее до 90%
    setTimeout(() => {
      progress.value = 90
    }, 1500)
  
    // Оставшиеся 10% будут заполнены при завершении загрузки
  }
  
  // Инициализация при монтировании компонента
  onMounted(() => {
    if (props.isLoading) {
      startProgressAnimation()
    }
  })
  </script>
  
  <style scoped>
  .progress-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background-color: transparent;
    z-index: 9999;
    pointer-events: none;
    overflow: hidden;
  }
  
  .progress-bar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: var(--progress-width, 0%);
    height: 100%;
    background: linear-gradient(to right, #6366f1, #8b5cf6);
    transition: width 0.3s ease;
  }
  
  .progress-bar.dark-mode::before {
    background: linear-gradient(to right, #818cf8, #a78bfa);
  }
  
  .progress-bar:not(.is-loading) {
    opacity: 0;
    transition: opacity 0.5s ease;
  }
  
  .progress-bar.is-loading {
    opacity: 1;
  }
  </style>