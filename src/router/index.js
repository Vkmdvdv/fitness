import { createRouter, createWebHistory } from 'vue-router'
import AuthLayout from '@/layouts/AuthLayout.vue'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import AuthPage from '@/views/Auth/AuthPage.vue'
import { useLoaderStore } from '@/stores/loader'
import { useUserStore } from '@/stores/user'

const routes = [
  // Маршрут авторизации с вложенным компонентом
  {
    path: '/auth',
    component: AuthLayout,
    children: [
      {
        path: '',
        name: 'auth',
        component: AuthPage,
        meta: { requiresGuest: true },
      },
    ],
  },

  // Маршруты для авторизованных пользователей
  {
    path: '/',
    component: DefaultLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'home',
        component: () => import('@/views/HomeView.vue'),
      },
      {
        path: 'profile',
        name: 'profile',
        component: () => import('@/views/Profile/ProfileView.vue'),
      },
      {
        path: 'add-exercise',
        name: 'addExercise',
        component: () => import('@/views/Exercise/AddExerciseView.vue'),
      },
      {
        path: 'exercise/:id',
        name: 'exerciseDetail',
        component: () => import('@/views/Exercise/ExerciseDetailView.vue'),
      },
      {
        path: 'workouts',
        name: 'workouts',
        component: () => import('@/views/Workout/WorkoutsView.vue'),
      },
      {
        path: 'workouts/builder',
        name: 'workout-builder',
        component: () => import('@/views/Workout/WorkoutBuilderView.vue'),
      },
      {
        path: 'workouts/:id',
        name: 'workout',
        component: () => import('@/views/Workout/WorkoutView.vue'),
      },
      {
        path: 'workouts/:id/training',
        name: 'workout-training',
        component: () => import('@/views/Workout/WorkoutTrainingView.vue'),
      },
      {
        path: 'workouts/:id/summary',
        name: 'workout-summary',
        component: () => import('@/views/Workout/WorkoutSummaryView.vue'),
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Глобальные хуки для отображения лоадера и проверки авторизации
router.beforeEach(async (to, from, next) => {
  // Показываем лоадер при начале навигации
  const loaderStore = useLoaderStore()
  loaderStore.showLoader() // Используем обычный лоадер без сообщения

  // Используем хранилище пользователя для проверки авторизации
  const userStore = useUserStore()

  // Проверяем авторизацию только если еще не проверяли
  if (!userStore.isAuthenticated && localStorage.getItem('authToken')) {
    await userStore.checkAuth()
  }

  const isAuthenticated = userStore.isAuthenticated

  if (to.matched.some((record) => record.meta.requiresAuth) && !isAuthenticated) {
    next({ name: 'auth' })
    return
  }

  if (to.matched.some((record) => record.meta.requiresGuest) && isAuthenticated) {
    next({ name: 'home' })
    return
  }

  next()
})

router.afterEach(() => {
  const loaderStore = useLoaderStore()
  loaderStore.hideLoader()
})

router.onError(() => {
  const loaderStore = useLoaderStore()
  loaderStore.hideLoader()
})

export default router