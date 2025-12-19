<script setup>
import { ref } from 'vue'
import BaseButton from '@/components/ui/BaseButton.vue'

defineProps({
  selectedExercises: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['update-exercise-params', 'toggle-weight', 'remove-exercise', 'prev', 'save', 'clear-exercises', 'reorder-exercises'])

// Состояние для отслеживания развернутых упражнений
const expandedExercises = ref({})

const toggleExercise = (id) => {
  expandedExercises.value[id] = !expandedExercises.value[id]
}

const isExpanded = (id) => {
  return !!expandedExercises.value[id]
}

const formatTime = (seconds) => {
  const minutes = Math.floor(seconds / 60)
  const remainingSeconds = seconds % 60
  return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`
}

// Состояние для перетаскивания
const draggedItem = ref(null)
const dragOverItem = ref(null)

// Функция для очистки всех упражнений
const clearAllExercises = () => {
  if (confirm('Вы уверены, что хотите удалить все упражнения?')) {
    emit('clear-exercises')
  }
}

// Функции для перетаскивания
const handleDragStart = (index) => {
  draggedItem.value = index
}

const handleDragOver = (e, index) => {
  e.preventDefault()
  dragOverItem.value = index

  // Добавляем визуальный индикатор для элемента, над которым находится перетаскиваемый элемент
  const items = document.querySelectorAll('.exercise-item')
  items.forEach(item => item.classList.remove('drag-over'))

  if (draggedItem.value !== dragOverItem.value) {
    items[index].classList.add('drag-over')
  }
}

const handleDragEnd = () => {
  // Удаляем визуальные индикаторы
  const items = document.querySelectorAll('.exercise-item')
  items.forEach(item => item.classList.remove('drag-over'))

  // Если элементы разные, меняем их местами
  if (draggedItem.value !== null && dragOverItem.value !== null && draggedItem.value !== dragOverItem.value) {
    emit('reorder-exercises', draggedItem.value, dragOverItem.value)
  }

  // Сбрасываем состояние
  draggedItem.value = null
  dragOverItem.value = null
}

const handleDrop = (e) => {
  e.preventDefault()
}
</script>

<template>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xl font-semibold dark:text-white">Настройка упражнений</h3>
      <BaseButton
        v-if="selectedExercises.length > 0"
        variant="danger"
        size="sm"
        @click="clearAllExercises"
      >
        Стереть
        <template #icon-right>
          <span class="i-mdi-delete-sweep"></span>
        </template>
      </BaseButton>
    </div>

    <div class="grid grid-cols-1 gap-4">
      <div v-if="selectedExercises.length === 0"
        class="text-center py-8 text-gray-500 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 rounded-xl">
        Добавьте упражнения в тренировку
      </div>

      <div
        v-for="(exercise, index) in selectedExercises"
        :key="exercise.id"
        class="bg-gray-50 dark:bg-gray-700 rounded-xl group exercise-item overflow-hidden"
        draggable="true"
        @dragstart="handleDragStart(index)"
        @dragover="handleDragOver($event, index)"
        @dragend="handleDragEnd"
        @drop="handleDrop"
      >
        <!-- Заголовок упражнения (всегда видимый) -->
        <div
          class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
          @click="toggleExercise(exercise.id)"
        >
          <div class="flex items-center gap-3 flex-grow">
            <span class="i-mdi-drag-vertical text-gray-400 cursor-move" @click.stop></span>

            <div>
              <p class="font-medium dark:text-white">{{ exercise.translated_name }}</p>
              <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ exercise.sets }} × {{ exercise.reps }}
                <span v-if="exercise.useWeight">• {{ exercise.weight }} кг</span>
                <span>• {{ formatTime(exercise.restTime) }} отдых</span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <BaseButton
              variant="danger"
              size="sm"
              @click.stop="$emit('remove-exercise', index)"
              class="opacity-70 hover:opacity-100"
            >
              <span class="i-mdi-delete"></span>
            </BaseButton>

            <span
              :class="[
                isExpanded(exercise.id) ? 'i-mdi-chevron-up' : 'i-mdi-chevron-down',
                'text-gray-500 dark:text-gray-400 text-xl transition-transform'
              ]"
            ></span>
          </div>
        </div>

        <!-- Детали упражнения (показываются при раскрытии) -->
        <div
          v-if="isExpanded(exercise.id)"
          class="p-4 pt-0 border-t border-gray-200 dark:border-gray-600"
        >
          <div class="mt-4">
            <!-- Подходы и повторения в одной строке -->
            <div class="flex flex-row gap-4 mb-4">
              <div class="w-1/2 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Подходы
                </label>
                <div class="flex items-center gap-2">
                  <BaseButton size="sm" @click="$emit('update-exercise-params', index, 'sets', Math.max(1, exercise.sets - 1))">
                    <span class="i-mdi-minus"></span>
                  </BaseButton>
                  <span class="w-8 text-center dark:text-white">{{ exercise.sets }}</span>
                  <BaseButton size="sm" @click="$emit('update-exercise-params', index, 'sets', exercise.sets + 1)">
                    <span class="i-mdi-plus"></span>
                  </BaseButton>
                </div>
              </div>

              <div class="w-1/2 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Повторения
                </label>
                <div class="flex items-center gap-2">
                  <BaseButton size="sm" @click="$emit('update-exercise-params', index, 'reps', Math.max(1, exercise.reps - 1))">
                    <span class="i-mdi-minus"></span>
                  </BaseButton>
                  <span class="w-8 text-center dark:text-white">{{ exercise.reps }}</span>
                  <BaseButton size="sm" @click="$emit('update-exercise-params', index, 'reps', exercise.reps + 1)">
                    <span class="i-mdi-plus"></span>
                  </BaseButton>
                </div>
              </div>
            </div>

            <!-- Отдых и вес в одной строке -->
            <div class="flex flex-row gap-4">
              <div class="w-1/2 space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Отдых
                </label>
                <div class="flex items-center gap-2">
                  <BaseButton size="sm"
                    @click="$emit('update-exercise-params', index, 'restTime', Math.max(0, exercise.restTime - 15))">
                    <span class="i-mdi-minus"></span>
                  </BaseButton>
                  <span class="w-16 text-center dark:text-white">{{ formatTime(exercise.restTime) }}</span>
                  <BaseButton size="sm" @click="$emit('update-exercise-params', index, 'restTime', exercise.restTime + 15)">
                    <span class="i-mdi-plus"></span>
                  </BaseButton>
                </div>
              </div>

              <div class="w-1/2 space-y-2">
                <div class="flex items-center justify-between">
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Вес
                  </label>
                  <BaseButton size="sm" @click="$emit('toggle-weight', index)"
                    :class="[exercise.useWeight ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300' : '']">
                    <span class="i-mdi-weight" :class="{ 'opacity-50': !exercise.useWeight }"></span>
                  </BaseButton>
                </div>
                <div v-if="exercise.useWeight" class="flex items-center gap-2">
                  <BaseButton size="sm"
                    @click="$emit('update-exercise-params', index, 'weight', Math.max(0, exercise.weight - 2.5))">
                    <span class="i-mdi-minus"></span>
                  </BaseButton>
                  <span class="w-16 text-center dark:text-white">{{ exercise.weight }} кг</span>
                  <BaseButton size="sm" @click="$emit('update-exercise-params', index, 'weight', exercise.weight + 2.5)">
                    <span class="i-mdi-plus"></span>
                  </BaseButton>
                </div>
                <div v-else class="h-8 flex items-center justify-center text-sm text-gray-500 dark:text-gray-400">
                  Без веса
                </div>
              </div>
            </div>
          </div>

          <!-- Группы мышц -->
          <div class="mt-4">
            <div class="flex flex-wrap gap-2">
              <span v-for="muscle in exercise.targetMuscles" :key="muscle"
                class="px-2 py-1 text-xs rounded-lg bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-300">
                {{ muscle }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-between mt-8">
      <BaseButton @click="$emit('prev')" variant="secondary">
        <template #icon-right>
          <span class="i-mdi-arrow-left"></span>
        </template>
        Назад
      </BaseButton>

      <BaseButton @click="$emit('save')" :disabled="selectedExercises.length === 0">
        Сохранить тренировку
        <template #icon-right>
          <span class="i-mdi-check"></span>
        </template>
      </BaseButton>
    </div>
  </div>
</template>

<style scoped>
.exercise-item {
  transition: all 0.2s ease;
}

.exercise-item.drag-over {
  transform: translateY(5px);
  box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.5);
}
</style>