<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useToastStore } from '@/stores/toast'
import { useWorkoutsStore } from '@/stores/workouts'
import WorkoutActionPanel from '@/components/Workout/WorkoutActionPanel.vue'
import WorkoutNameTab from '@/components/Workout/WorkoutNameTab.vue'
import WorkoutSelectTab from '@/components/Workout/WorkoutSelectTab.vue'
import WorkoutListTab from '@/components/Workout/WorkoutListTab.vue'

const router = useRouter()
const toastStore = useToastStore()
const workoutsStore = useWorkoutsStore()
const selectedExercises = ref([])
const workoutName = ref('Новая тренировка')
const activeTab = ref('name') // 'name', 'select', 'list'
const selectTabRef = ref(null)

const autoGenerateWorkout = () => {
  console.log(selectTabRef.value)
  if (!selectTabRef.value?.exerciseListRef?.value) return

  const filteredExercises = selectTabRef.value.exerciseListRef.value.getFilteredExercises()

  if (filteredExercises.length === 0) {
    toastStore.addToast({
      message: 'Нет упражнений, соответствующих фильтрам',
      type: 'warning'
    })
    return
  }

  selectedExercises.value = []

  const maxExercises = 6
  const shuffled = [...filteredExercises].sort(() => 0.5 - Math.random())
  const selected = shuffled.slice(0, maxExercises)

  selected.forEach(exercise => {
    selectedExercises.value.push({
      ...exercise,
      sets: 3,
      reps: 12,
      weight: 0,
      restTime: 60,
      useWeight: false,
    })
  })
  toastStore.addToast({
    message: `Автоматически добавлено ${selected.length} упражнений`,
    type: 'success'
  })

  activeTab.value = 'list'
}

const handleAddToWorkout = (exercise) => {
  selectedExercises.value.push({
    ...exercise,
    sets: 3,
    reps: 12,
    restTime: 60,
    useWeight: false,
    weight: 0
  })
  toastStore.addToast({
    message: `${exercise.translated_name} добавлено в тренировку`,
    type: 'success'
  })
}

const toggleWeight = (index) => {
  selectedExercises.value[index].useWeight = !selectedExercises.value[index].useWeight
}

const updateExerciseParams = (index, field, value) => {
  selectedExercises.value[index][field] = value
}

const removeExercise = (index) => {
  selectedExercises.value.splice(index, 1)
}

const saveWorkout = () => {
  try {
    workoutsStore.addWorkout({
      name: workoutName.value,
      exercises: selectedExercises.value
    })

    toastStore.addToast({
      message: 'Тренировка сохранена',
      type: 'success'
    })

    router.push('/workouts')
  } catch {
    toastStore.addToast({
      message: 'Ошибка при сохранении тренировки',
      type: 'error'
    })
  }
}

const setActiveTab = (tab) => {
  activeTab.value = tab
}

const goToNextTab = () => {
  if (activeTab.value === 'name') {
    activeTab.value = 'select'
  } else if (activeTab.value === 'select') {
    activeTab.value = 'list'
  }
}

const goToPrevTab = () => {
  if (activeTab.value === 'list') {
    activeTab.value = 'select'
  } else if (activeTab.value === 'select') {
    activeTab.value = 'name'
  }
}

const clearExercises = () => {
  selectedExercises.value = []
  toastStore.addToast({
    message: 'Все упражнения удалены',
    type: 'info'
  })
}

const reorderExercises = (fromIndex, toIndex) => {
  const updatedExercises = [...selectedExercises.value]

  const [movedItem] = updatedExercises.splice(fromIndex, 1)

  updatedExercises.splice(toIndex, 0, movedItem)

  selectedExercises.value = updatedExercises
}
</script>

<template>
  <main class="container mx-auto py-8 pb-24">
    <div class="space-y-8">
      <div>
        <h2
          class="text-3xl sm:text-4xl font-bold mb-2 bg-gradient-to-r from-violet-600 via-fuchsia-500 to-pink-500 bg-clip-text text-transparent">
          Конструктор тренировки
        </h2>
        <p class="text-gray-600 dark:text-gray-400">Создайте свою тренировку за три шага</p>
      </div>
      <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
        <button @click="setActiveTab('name')"
          class="py-3 px-6 font-medium text-sm transition-colors duration-200 ease-in-out"
          :class="activeTab === 'name' ? 'border-b-2 border-violet-500 text-violet-600 dark:text-violet-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
          1. Название
        </button>
        <button @click="setActiveTab('select')"
          class="py-3 px-6 font-medium text-sm transition-colors duration-200 ease-in-out"
          :class="activeTab === 'select' ? 'border-b-2 border-violet-500 text-violet-600 dark:text-violet-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
          2. Выбор упражнений
        </button>
        <button @click="setActiveTab('list')"
          class="py-3 px-6 font-medium text-sm transition-colors duration-200 ease-in-out"
          :class="activeTab === 'list' ? 'border-b-2 border-violet-500 text-violet-600 dark:text-violet-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
          3. Настройка упражнений
        </button>
      </div>

      <WorkoutNameTab v-if="activeTab === 'name'" v-model:workoutName="workoutName" @next="goToNextTab" />

      <WorkoutSelectTab v-if="activeTab === 'select'" ref="selectTabRef" :selectedExercises="selectedExercises"
        @add-to-workout="handleAddToWorkout" @auto-generate="autoGenerateWorkout" @prev="goToPrevTab"
        @next="goToNextTab" />

      <WorkoutListTab v-if="activeTab === 'list'" :selectedExercises="selectedExercises"
        @update-exercise-params="updateExerciseParams" @toggle-weight="toggleWeight" @remove-exercise="removeExercise"
        @clear-exercises="clearExercises" @reorder-exercises="reorderExercises" @prev="goToPrevTab"
        @save="saveWorkout" />
    </div>

    <WorkoutActionPanel v-if="activeTab === 'select'" :selected-exercises="selectedExercises"
      @auto-generate="autoGenerateWorkout" @save-workout="saveWorkout" />
  </main>
</template>

<style scoped>
.max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar {
  width: 8px;
}

.max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar-thumb {
  background: #ddd;
  border-radius: 4px;
}

.max-h-\[calc\(100vh-16rem\)\]::-webkit-scrollbar-thumb:hover {
  background: #ccc;
}
</style>