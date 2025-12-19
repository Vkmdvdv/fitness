import apiClient from './client'

/**
 * Сервис для работы с данными пользователя
 */
export const userApi = {
  /**
   * Получить информацию о текущем пользователе
   * @returns {Promise<Object>} Данные пользователя
   */
  getUserInfo: async () => {
    try {
      const response = await apiClient.get('/user/info')
      return {
        success: true,
        user: response.data.data.user,
      }
    } catch (error) {
      console.error('Ошибка при получении данных пользователя:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось загрузить данные пользователя',
      }
    }
  },

  updateUser: async (data) => {
    try {
      const response = await apiClient.put('/user', data)
      return {
        success: true,
        user: response.data,
      }
    } catch (error) {
      console.error('Ошибка при обновлении данных пользователя:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось обновить данные пользователя',
      }
    }
  },
  updateUserInfo: async (data) => {
    try {
      const response = await apiClient.post('/user/info', data)
      return {
        success: true,
        user: response.data,
      }
    } catch (error) {
      console.error('Ошибка при обновлении данных пользователя:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось обновить данные пользователя',
      }
    }
  },

  /**
   * Обновить данные персонажа пользователя
   * @param {Object} characterData - Данные персонажа
   * @returns {Promise<Object>} Результат операции
   */
  updateCharacter: async (characterData) => {
    try {
      const response = await apiClient.post('/user/character', characterData)
      return {
        success: true,
        character: response.data,
      }
    } catch (error) {
      console.error('Ошибка при обновлении персонажа:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось обновить персонажа',
      }
    }
  },

  /**
   * Купить предмет для персонажа
   * @param {Object} data - Данные о покупке
   * @param {string} data.itemId - ID предмета
   * @param {number} data.price - Цена предмета
   * @returns {Promise<Object>} Результат операции
   */
  buyCharacterItem: async (data) => {
    try {
      const response = await apiClient.post('/user/character/buy', data)
      return {
        success: true,
        result: response.data,
      }
    } catch (error) {
      console.error('Ошибка при покупке предмета:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось купить предмет',
      }
    }
  },

  /**
   * Экипировать предмет персонажа
   * @param {Object} data - Данные об экипировке
   * @param {string} data.itemId - ID предмета
   * @param {string} data.category - Категория предмета (hair, clothes, accessories)
   * @returns {Promise<Object>} Результат операции
   */
  equipCharacterItem: async (data) => {
    try {
      const response = await apiClient.post('/user/character/equip', data)
      return {
        success: true,
        result: response.data,
      }
    } catch (error) {
      console.error('Ошибка при экипировке предмета:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось экипировать предмет',
      }
    }
  },

  /**
   * Получить историю тренировок пользователя
   * @param {Object} params - Параметры запроса (page, limit и т.д.)
   * @returns {Promise<Object>} История тренировок
   */
  getWorkoutHistory: async (params = {}) => {
    try {
      const response = await apiClient.get('/user/workouts/history', { params })
      return {
        success: true,
        history: response.data,
      }
    } catch (error) {
      console.error('Ошибка при получении истории тренировок:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось загрузить историю тренировок',
      }
    }
  },

  /**
   * Получить статистику пользователя
   * @returns {Promise<Object>} Статистика пользователя
   */
  getUserStats: async () => {
    try {
      const response = await apiClient.get('/user/stats')
      return {
        success: true,
        stats: response.data,
      }
    } catch (error) {
      console.error('Ошибка при получении статистики:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось загрузить статистику',
      }
    }
  },

  /**
   * Обновить настройки пользователя
   * @param {Object} settings - Настройки пользователя
   * @returns {Promise<Object>} Результат операции
   */
  updateSettings: async (settings) => {
    try {
      const response = await apiClient.post('/user/settings', settings)
      return {
        success: true,
        settings: response.data,
      }
    } catch (error) {
      console.error('Ошибка при обновлении настроек:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось обновить настройки',
      }
    }
  },
}