<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useWorkoutsStore } from '@/stores/workouts'
import BaseButton from '@/components/ui/BaseButton.vue'

const route = useRoute()
const router = useRouter()
const workoutsStore = useWorkoutsStore()
const workout = ref(null)
const expandedExercises = ref(new Set())

onMounted(async () => {
  const workoutId = route.params.id
  workout.value = workoutsStore.getWorkoutById(workoutId)
})

const toggleExercise = (exerciseId) => {
  if (expandedExercises.value.has(exerciseId)) {
    expandedExercises.value.delete(exerciseId)
  } else {
    expandedExercises.value.add(exerciseId)
  }
}

const goToExercise = (exerciseId) => {
  router.push(`/exercise/${exerciseId}`)
}

const formatTime = (seconds) => {
  const minutes = Math.floor(seconds / 60)
  const remainingSeconds = seconds % 60
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`
}

const goBack = () => {
  if (window.history.length > 2) {
    router.back()
  } else {
    router.push('/workouts')
  }
}

const startWorkout = () => {
  router.push(`/workouts/${workout.value.id}/training`)
}
</script>

<template>
  <main class="container mx-auto py-8">
    <div class="flex items-center justify-between mb-8">
      <BaseButton variant="secondary" @click="goBack">
        Назад
        <template #icon-right>
          <span class="i-mdi-arrow-left"></span>
        </template>
      </BaseButton>

      <BaseButton v-if="workout" @click="startWorkout">
        Начать тренировку
        <template #icon-right>
          <span class="i-mdi-play"></span>
        </template>
      </BaseButton>
    </div>

    <div v-if="workout" class="space-y-8">
      <div>
        <h2
          class="text-3xl sm:text-4xl font-bold mb-2 bg-gradient-to-r from-violet-600 via-fuchsia-500 to-pink-500 bg-clip-text text-transparent">
          {{ workout.name }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
          {{ workout.exercises.length }} упражнений
        </p>
      </div>

      <div class="grid grid-cols-1 gap-6">
        <div v-for="(exercise, index) in workout.exercises" :key="exercise.id"
          class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
          <div class="p-6">
            <div class="flex items-start justify-between">
              <div>
                <h3 class="text-xl font-semibold dark:text-white">{{ exercise.name }}</h3>
                <div class="flex flex-wrap gap-2 mt-2">
                  <span v-for="muscle in exercise.targetMuscles" :key="muscle"
                    class="px-2 py-1 text-xs rounded-lg bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300">
                    {{ muscle }}
                  </span>
                </div>
              </div>
              <span class="text-2xl font-bold text-gray-300 dark:text-gray-600">{{ index + 1 }}</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
              <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Подходы</div>
                <div class="font-semibold flex items-center gap-2 dark:text-white">
                  <span class="i-mdi-repeat text-violet-500 dark:text-violet-400"></span>
                  {{ exercise.sets }}
                </div>
              </div>

              <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Повторения</div>
                <div class="font-semibold flex items-center gap-2 dark:text-white">
                  <span class="i-mdi-refresh text-violet-500 dark:text-violet-400"></span>
                  {{ exercise.reps }}
                </div>
              </div>

              <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Отдых</div>
                <div class="font-semibold flex items-center gap-2 dark:text-white">
                  <span class="i-mdi-timer text-violet-500 dark:text-violet-400"></span>
                  {{ formatTime(exercise.restTime) }}
                </div>
              </div>

              <div v-if="exercise.useWeight" class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4">
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Вес</div>
                <div class="font-semibold flex items-center gap-2 dark:text-white">
                  <span class="i-mdi-weight text-violet-500 dark:text-violet-400"></span>
                  {{ exercise.weight }} кг
                </div>
              </div>
            </div>

            <div class="flex items-center justify-between mt-6">
              <BaseButton variant="secondary" @click="goToExercise(exercise.id)">
                <span class="hidden md:inline">Подробнее об упражнении</span>
                <span class="inline md:hidden">Подробнее</span>
                <template #icon-right>
                  <span class="i-mdi-arrow-right"></span>
                </template>
              </BaseButton>
              <BaseButton @click="toggleExercise(exercise.id)">
                {{ expandedExercises.has(exercise.id) ? 'Скрыть технику' : 'Показать технику' }}
                <template #icon-right>
                  <span :class="[
                    expandedExercises.has(exercise.id) ? 'i-mdi-chevron-up' : 'i-mdi-chevron-down'
                  ]"></span>
                </template>
              </BaseButton>
            </div>
          </div>

          <div v-if="expandedExercises.has(exercise.id)"
            class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
            <div class="p-6 space-y-6">
              <div v-if="exercise.techniqueVideo"
                class="aspect-video rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-600">
                <video :src="exercise.techniqueVideo" controls class="w-full h-full object-cover">
                  Ваш браузер не поддерживает видео
                </video>
              </div>
              <div v-else-if="exercise.techniqueImage"
                class="aspect-video rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-600">
                <img :src="exercise.techniqueImage" :alt="exercise.name" class="w-full h-full object-cover">
              </div>

              <div class="space-y-4">
                <h4 class="font-semibold text-lg dark:text-white">Как выполнять:</h4>
                <div class="space-y-2">
                  <ol v-if="exercise.technique?.length" class="list-decimal list-inside space-y-3">
                    <li v-for="(step, index) in exercise.technique" :key="index"
                      class="text-gray-600 dark:text-gray-300 pl-2">
                      {{ step }}
                    </li>
                  </ol>
                  <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
                    Описание техники выполнения пока не добавлено
                  </div>
                </div>
              </div>

              <div v-if="exercise.tips?.length" class="space-y-3">
                <h4 class="font-semibold text-lg dark:text-white">Важные моменты:</h4>
                <ul class="list-disc list-inside space-y-2">
                  <li v-for="(tip, index) in exercise.tips" :key="index" class="text-gray-600 dark:text-gray-300">
                    {{ tip }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="flex items-center justify-center h-64">
      <div class="text-center">
        <div class="text-xl font-semibold text-gray-600 dark:text-gray-400 mb-2">
          Тренировка не найдена
        </div>
        <BaseButton @click="goBack">
          Вернуться к списку
        </BaseButton>
      </div>
    </div>
  </main>
</template>