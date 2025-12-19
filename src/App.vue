<script setup>
import { onMounted } from 'vue'
import Toast from '@/components/ui/Toast.vue'
import AppLoader from '@/components/ui/AppLoader.vue'
import ProgressBar from '@/components/ui/ProgressBar.vue'
import { useLoaderStore } from './stores/loader'
import { useThemeStore } from './stores/theme'

const loaderStore = useLoaderStore()
const themeStore = useThemeStore()

onMounted(async () => {
  themeStore.initTheme()

  loaderStore.showInitLoader('Инициализация приложения...')
  setTimeout(() => {
    loaderStore.hideInitLoader()
  }, 1000)
})
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <ProgressBar :is-loading="loaderStore.isLoading" />

    <RouterView />
    <Toast />

    <AppLoader v-if="loaderStore.isInitializing" />
  </div>
</template>

<style>
html,
body {
  margin: 0;
  padding: 0;
  height: 100%;
  width: 100%;
}

body {
  font-family: 'Inter', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  overflow-x: hidden;
}

.dark body {
  background-color: #111827;
  color: #e5e7eb;
}
</style>