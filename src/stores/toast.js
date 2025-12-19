import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useToastStore = defineStore('toast', () => {
  const toasts = ref([])

  const addToast = ({ message, type = 'info', timeout = 3000 }) => {
    const id = Date.now()

    toasts.value.push({
      id,
      message,
      type,
      timeout,
    })

    setTimeout(() => {
      removeToast(id)
    }, timeout)
  }

  const removeToast = (id) => {
    const index = toasts.value.findIndex((toast) => toast.id === id)
    if (index > -1) {
      toasts.value.splice(index, 1)
    }
  }

  return {
    toasts,
    addToast,
    removeToast,
  }
})