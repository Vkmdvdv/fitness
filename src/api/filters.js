import apiClient from './client'

/**
 * API для работы с фильтрами упражнений
 */
export const filtersApi = {
  /**
   * Получить все доступные фильтры для упражнений
   * @returns {Promise<Object>} Объект с доступными фильтрами
   */
  async getExerciseFilters() {
    try {
      const response = await apiClient.get('/filters');
      return {
        success: true,
        filters: response.data.data
      };
    } catch (error) {
      console.error('Ошибка при получении фильтров:', error);
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось загрузить фильтры'
      };
    }
  },

  /**
   * Получить список всех мышц для фильтрации
   * @returns {Promise<Object>} Объект со списком мышц
   */
  async getMuscles() {
    try {
      const response = await apiClient.get('/filters/muscles');
      return {
        success: true,
        muscles: response.data
      };
    } catch (error) {
      console.error('Ошибка при получении списка мышц:', error);
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось загрузить список мышц'
      };
    }
  },

  /**
   * Получить список всего оборудования для фильтрации
   * @returns {Promise<Object>} Объект со списком оборудования
   */
  async getEquipment() {
    try {
      const response = await apiClient.get('/filters/equipment');
      return {
        success: true,
        equipment: response.data
      };
    } catch (error) {
      console.error('Ошибка при получении списка оборудования:', error);
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось загрузить список оборудования'
      };
    }
  },

  /**
   * Получить список всех тегов для фильтрации
   * @returns {Promise<Object>} Объект со списком тегов
   */
  async getTags() {
    try {
      const response = await apiClient.get('/filters/tags');
      return {
        success: true,
        tags: response.data
      };
    } catch (error) {
      console.error('Ошибка при получении списка тегов:', error);
      return {
        success: false,
        message: error.response?.data?.message || 'Не удалось загрузить список тегов'
      };
    }
  }
};