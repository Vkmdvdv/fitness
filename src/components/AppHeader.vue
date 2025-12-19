<script setup>
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { computed, ref } from 'vue'
import ThemeToggle from '@/components/ui/ThemeToggle.vue'

const route = useRoute()
const router = useRouter()
const isHidden = ref(false)

const isActiveRoute = computed(() => (path) => {
  if (path === '/') {
    return route.path === path
  }
  return route.path.startsWith(path)
})

const navItems = [
  {
    path: '/',
    name: 'Главная',
    icon: 'i-mdi-home text-xl'
  },
  {
    path: '/workouts',
    name: 'Тренировки',
    icon: 'i-mdi-dumbbell text-xl'
  },
  {
    path: '/profile',
    name: 'Профиль',
    icon: 'i-mdi-account text-xl'
  }
]

const toggleElements = () => {
  isHidden.value = true
  setTimeout(() => {
    isHidden.value = false
  }, 500)

  router.push('/')
}
</script>

<template>
  <header
    class="sticky top-0 z-50 backdrop-blur-sm bg-white/80 dark:bg-gray-900/80 border-b border-gray-200 dark:border-gray-700">
    <nav class="container mx-auto flex justify-between items-center max-w-7xl h-16 md:px-6 px-2">
      <div class="flex items-center gap-2 cursor-pointer" @click="toggleElements">
        <div :class="[
          'i-mdi-thunder text-2xl md:text-3xl text-violet-600 dark:text-violet-400 transition-all duration-300',
          isHidden ? 'opacity-0 -translate-y-4 rotate-180' : 'opacity-100 translate-y-0 rotate-0'
        ]"></div>
        <div class="flex items-center">
          <h1
            class="text-lg md:text-xl font-bold bg-gradient-to-r from-indigo-600 to-blue-500 bg-clip-text text-transparent">
            Boost Mode
          </h1>
          <span :class="[
            'hidden md:inline-block text-sm font-medium text-gray-600 dark:text-gray-400 ml-3 normal-case transition-all duration-200',
            isHidden ? 'opacity-0 translate-x-4' : 'opacity-100 translate-x-0'
          ]">
            | Переключайся в режим зверя
          </span>
        </div>
      </div>

      <div class="flex gap-2 md:gap-3 items-center">
        <ThemeToggle />
        <RouterLink v-for="item in navItems" :key="item.name" :to="item.path" :class="[
          'flex items-center gap-2 transition-all duration-300',
          'px-3 py-2 md:px-4 md:py-2 rounded-xl font-medium',
          isActiveRoute(item.path)
            ? 'bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-lg shadow-indigo-500/20 scale-105'
            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-indigo-600 dark:hover:text-indigo-400'
        ]" :title="item.name">
          <span :class="item.icon"></span>
          <span class="hidden md:inline">{{ item.name }}</span>
        </RouterLink>
      </div>
    </nav>
  </header>
</template>