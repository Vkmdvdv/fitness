import { defineStore } from 'pinia'

export const useThemeStore = defineStore('theme', {
  state: () => ({
    darkMode: false
  }),

  getters: {
    isDarkMode: (state) => state.darkMode
  },

  actions: {
    toggleDarkMode() {
      this.darkMode = !this.darkMode
      this.applyTheme()
      localStorage.setItem('darkMode', this.darkMode ? 'true' : 'false')
    },

    initTheme() {
      // Проверяем только сохраненную тему, игнорируем системные настройки
      const savedTheme = localStorage.getItem('darkMode')

      if (savedTheme !== null) {
        this.darkMode = savedTheme === 'true'
      } else {
        // По умолчанию используем светлую тему
        this.darkMode = false
      }

      this.applyTheme()
    },

    applyTheme() {
      // Добавляем или удаляем класс dark для html
      if (this.darkMode) {
        document.documentElement.classList.add('dark')
      } else {
        document.documentElement.classList.remove('dark')
      }
    }
  }
})