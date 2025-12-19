import { defineStore } from 'pinia'
import { authApi } from '@/api/auth'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    isAuthenticated: false,
    isLoading: false,
    error: null,
    character: null,
  }),

  getters: {
    /**
     * Получить текущего пользователя
     * @returns {Object|null} - Данные пользователя или null
     */
    currentUser: (state) => state.user,

    /**
     * Проверить, авторизован ли пользователь
     * @returns {boolean} - true, если пользователь авторизован
     */
    isLoggedIn: (state) => state.isAuthenticated,

    /**
     * Получить имя пользователя для отображения
     * @returns {string} - Имя пользователя или "Пользователь"
     */
    displayName: (state) => state.user?.name || 'Пользователь',
  },

  actions: {
    /**
     * Установить данные пользователя
     * @param {Object} userData - Данные пользователя
     */
    setUser(userData) {
      this.user = userData
      this.isAuthenticated = !!userData
      this.error = null
    },

    /**
     * Установить ошибку
     * @param {string} error - Сообщение об ошибке
     */
    setError(error) {
      this.error = error
    },

    /**
     * Авторизация по логину и паролю
     * @param {Object} credentials - Учетные данные пользователя
     * @returns {Promise<boolean>} - Результат авторизации
     */
    async login(credentials) {
      this.isLoading = true
      this.error = null

      try {
        const { success, user, message } = await authApi.login(credentials)

        if (success && user) {
          this.setUser(user)
          return true
        } else {
          this.setError(message || 'Ошибка при авторизации')
          return false
        }
      } catch {
        this.setError('Произошла ошибка при авторизации')
        return false
      } finally {
        this.isLoading = false
      }
    },

    /**
     * Регистрация нового пользователя
     * @param {Object} userData - Данные нового пользователя
     * @returns {Promise<boolean>} - Результат регистрации
     */
    async register(userData) {
      this.isLoading = true
      this.error = null

      try {
        const { success, user, message } = await authApi.register(userData)

        if (success && user) {
          this.setUser(user)
          return true
        } else {
          this.setError(message || 'Ошибка при регистрации')
          return false
        }
      } catch {
        this.setError('Произошла ошибка при регистрации')
        return false
      } finally {
        this.isLoading = false
      }
    },

    /**
     * Выход из системы
     */
    async logout() {
      this.isLoading = true

      try {
        await authApi.logout()
      } finally {
        this.setUser(null)
        this.isLoading = false
      }
    },

    /**
     * Проверка текущего токена и получение данных пользователя
     * @returns {Promise<boolean>} - Результат проверки
     */
    async checkAuth() {
      this.isLoading = true

      try {
        const { success, user } = await authApi.checkToken()

        if (success && user) {
          this.setUser(user)
          return true
        } else {
          this.setUser(null)
          return false
        }
      } catch {
        this.setUser(null)
        return false
      } finally {
        this.isLoading = false
      }
    },
    setUserName(name) {
      this.user.name = name
    },
    setUserStats(stats) {
      this.user.info = stats
    },
    setCharacter(character) {
      this.character = character
    },
  }
})