import axios from 'axios'

// Используем переменную окружения для базового URL
const apiBaseUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

// Создаем экземпляр axios с базовым URL
const apiClient = axios.create({
  baseURL: apiBaseUrl,
  headers: {
    'Content-Type': 'application/json',
    'Accept-Language': 'ru'
  },
})

/**
 * Устанавливает токен авторизации для всех последующих запросов
 * @param {string|null} token - JWT токен или null для удаления токена
 */
export const setAuthToken = (token) => {
  if (token) {
    apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`
    // Можно также сохранить токен в localStorage
    localStorage.setItem('authToken', token)
  } else {
    delete apiClient.defaults.headers.common['Authorization']
    // Удаляем токен из localStorage при выходе
    localStorage.removeItem('authToken')
  }
}

// Инициализация: проверяем, есть ли сохраненный токен
const token = localStorage.getItem('authToken')
if (token) {
  setAuthToken(token)
}

// Предполагаемый код для перехватчика ответов
apiClient.interceptors.response.use(
  response => response,
  error => {
    // Проверка на ошибку аутентификации
    if (error.response?.status === 401) {
      // Сохраняем текущий путь для возврата после авторизации
      const currentPath = window.location.pathname
      localStorage.setItem('redirectAfterLogin', currentPath)

      // Перенаправляем на страницу авторизации
      window.location.href = '/auth'
    }

    return Promise.reject(error)
  }
)

export default apiClient