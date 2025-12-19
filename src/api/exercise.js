import apiClient from './client'

/**
 * Сервис для работы с упражнениями
 */
export const exerciseApi = {
  /**
   * Получить все упражнения с пагинацией и фильтрацией
   * @param {Object} params - Параметры запроса (page, difficulty, muscles, equipment, search и т.д.)
   * @returns {Promise<Object>} Объект с упражнениями и метаданными пагинации
   */
  async getAllExercises(params = {}) {
    try {
      const response = await apiClient.get('/exercises', { params })
      return {
        success: true,
        exercises: response.data
      }
    } catch (error) {
      console.error('Ошибка при получении упражнений:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось загрузить упражнения'
      }
    }
  },

  /**
   * Получение информации об упражнении по ID
   * @param {string} id - ID упражнения
   * @returns {Promise<Object>} - Информация об упражнении
   */
  getExerciseById: async (id) => {
    try {
      const response = await apiClient.get(`/exercises/${id}`)
      return { success: true, exercise: response.data }
    } catch (error) {
      console.error('Ошибка при получении упражнения:', error)
      return { success: false, message: 'Ошибка при получении упражнения' }
    }
  },

  /**
   * Создание нового упражнения
   * @param {Object} exerciseData - Данные нового упражнения
   * @returns {Promise<Object>} - Результат операции
   */
  createExercise: async (exerciseData) => {
    try {
      const response = await apiClient.post('/exercises', exerciseData)
      return { success: true, exercise: response.data }
    } catch (error) {
      console.error('Ошибка при создании упражнения:', error)
      return { success: false, message: 'Ошибка при создании упражнения' }
    }
  },

  /**
   * Обновление существующего упражнения
   * @param {string} id - ID упражнения
   * @param {Object} exerciseData - Обновленные данные упражнения
   * @returns {Promise<Object>} - Результат операции
   */
  updateExercise: async (id, exerciseData) => {
    try {
      const response = await apiClient.put(`/exercises/${id}`, exerciseData)
      return { success: true, exercise: response.data }
    } catch (error) {
      console.error('Ошибка при обновлении упражнения:', error)
      return { success: false, message: 'Ошибка при обновлении упражнения' }
    }
  },

  /**
   * Удаление упражнения
   * @param {string} id - ID упражнения
   * @returns {Promise<Object>} - Результат операции
   */
  deleteExercise: async (id) => {
    try {
      await apiClient.delete(`/exercises/${id}`)
      return { success: true }
    } catch (error) {
      console.error('Ошибка при удалении упражнения:', error)
      return { success: false, message: 'Ошибка при удалении упражнения' }
    }
  },

  /**
   * Обновление главного изображения упражнения
   * @param {string} id - ID упражнения
   * @param {FormData} formData - Данные изображения
   * @returns {Promise<Object>} - Результат операции
   */
  updateMainImage: async (id, formData) => {
    try {
      const response = await apiClient.post(`/exercises/${id}/image`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        }
      })
      return { success: true, imageUrl: response.data.url }
    } catch (error) {
      console.error('Ошибка при обновлении изображения:', error)
      return {
        success: false,
        message: error.response?.data?.message || 'Ошибка при обновлении изображения'
      }
    }
  }
}