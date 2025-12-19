import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLoaderStore = defineStore('loader', () => {
  const isLoading = ref(false)
  const isInitializing = ref(false)
  const message = ref('Загрузка...')

  function showLoader(customMessage = 'Загрузка...') {
    message.value = customMessage
    isLoading.value = true
  }

  function hideLoader() {
    isLoading.value = false
  }

  function showInitLoader(customMessage = 'Инициализация приложения...') {
    message.value = customMessage
    isInitializing.value = true
  }

  function hideInitLoader() {
    isInitializing.value = false
  }

  return {
    isLoading,
    isInitializing,
    message,
    showLoader,
    hideLoader,
    showInitLoader,
    hideInitLoader
  }
})