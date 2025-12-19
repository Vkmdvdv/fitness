<script setup>
import { useRouter } from 'vue-router'
import { useWorkoutsStore } from '@/stores/workouts'
import BaseButton from '@/components/ui/BaseButton.vue'

const router = useRouter()
const workoutsStore = useWorkoutsStore()

const goToWorkout = (workoutId) => {
  router.push(`/workouts/${workoutId}`)
}

const goToBuilder = () => {
  router.push('/workouts/builder')
}
</script>

<template>
  <main class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h2
          class="text-3xl sm:text-4xl font-bold mb-2 bg-gradient-to-r from-violet-600 via-fuchsia-500 to-pink-500 bg-clip-text text-transparent">
          Мои тренировки
        </h2>
        <p class="text-gray-600 dark:text-gray-400">Создавайте и управляйте своими тренировками</p>
      </div>

      <BaseButton @click="goToBuilder">
        <span class="hidden md:inline">Создать тренировку</span>
        <template #icon-right>
          <span class="i-mdi-plus"></span>
        </template>
      </BaseButton>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="workout in workoutsStore.workouts" :key="workout.id"
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer"
        @click="goToWorkout(workout.id)">
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-xl font-semibold mb-1 dark:text-white">{{ workout.name }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ workout.exercises.length }} упражнений</p>
          </div>
          <span class="i-mdi-chevron-right text-gray-400 dark:text-gray-500"></span>
        </div>

        <div class="flex flex-wrap gap-2">
          <span v-for="exercise in workout.exercises.slice(0, 3)" :key="exercise.id"
            class="px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
            {{ exercise.translated_name }}
          </span>
          <span v-if="workout.exercises.length > 3"
            class="px-2 py-1 text-xs rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
            +{{ workout.exercises.length - 3 }}
          </span>
        </div>
      </div>

      <div v-if="workoutsStore.workouts.length === 0"
        class="col-span-full flex flex-col items-center justify-center py-12 text-center">
        <div class="text-gray-500 dark:text-gray-400 mb-4">У вас пока нет тренировок</div>
        <BaseButton @click="goToBuilder">
          Создать первую тренировку
          <template #icon-right>
            <span class="i-mdi-plus"></span>
          </template>
        </BaseButton>
      </div>
    </div>
  </main>
</template>