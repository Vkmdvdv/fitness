import apiClient, { setAuthToken } from './client'

/**
 * Сервис для работы с аутентификацией
 */
export const authApi = {
  /**
   * Выход из системы
   * @returns {Promise<Object>} - Результат операции
   */
  logout: async () => {
    try {
      await apiClient.post('/auth/logout')
      setAuthToken(null)
      return { success: true }
    } catch (error) {
      console.error('Ошибка при выходе из системы:', error)
      // Даже при ошибке очищаем токен
      setAuthToken(null)
      return { success: false, message: 'Ошибка при выходе из системы' }
    }
  },

  /**
   * Авторизация по логину и паролю
   * @param {Object} credentials - Учетные данные пользователя
   * @param {string} credentials.email - Email пользователя
   * @param {string} credentials.password - Пароль пользователя
   * @returns {Promise<Object>} - Данные пользователя и токен
   */
  login: async (credentials) => {
    try {
      const response = await apiClient.post('/auth/login', credentials)
      const { token, user } = response.data

      if (token) {
        setAuthToken(token)
      }

      return { success: true, user, token }
    } catch (error) {
      console.error('Ошибка при авторизации:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Ошибка при авторизации'
      }
    }
  },

  /**
   * Регистрация нового пользователя
   * @param {Object} userData - Данные нового пользователя
   * @param {string} userData.email - Email пользователя
   * @param {string} userData.password - Пароль пользователя
   * @param {string} userData.name - Имя пользователя
   * @returns {Promise<Object>} - Данные пользователя и токен
   */
  register: async (userData) => {
    try {
      const response = await apiClient.post('/auth/register', userData)
      const { token, user } = response.data

      if (token) {
        setAuthToken(token)
      }

      return { success: true, user, token }
    } catch (error) {
      console.error('Ошибка при регистрации:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Ошибка при регистрации'
      }
    }
  },

  /**
   * Проверка текущего токена
   * @returns {Promise<Object>} - Данные пользователя
   */
  checkToken: async () => {
    try {
      const response = await apiClient.get('/user/info')
      return { success: true, user: response.data.data.user }
    } catch {
      setAuthToken(null)
      return { success: false }
    }
  },

  /**
   * Обновление данных пользователя
   * @param {Object} userData - Новые данные пользователя
   * @returns {Promise<Object>} - Обновленные данные пользователя
   */
  updateUser: async (userData) => {
    try {
      const response = await apiClient.put('/auth/user', userData)
      return { success: true, user: response.data }
    } catch (error) {
      console.error('Ошибка при обновлении данных пользователя:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Ошибка при обновлении данных пользователя'
      }
    }
  },
}