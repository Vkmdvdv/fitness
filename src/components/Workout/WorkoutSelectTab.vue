<script setup>
import { ref } from 'vue'
import ExerciseList from '@/components/Exercise/ExerciseList.vue'

defineProps({
  selectedExercises: {
    type: Array,
    required: true
  }
})

defineEmits(['add-to-workout', 'auto-generate'])

const exerciseListRef = ref(null)

defineExpose({
  exerciseListRef
})
</script>

<template>
  <div class="md:bg-white md:dark:bg-gray-800 rounded-2xl shadow-sm md:p-6">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xl font-semibold dark:text-white">Выбор упражнений</h3>
      <!-- <BaseButton variant="secondary" @click="$emit('auto-generate')" >
        Автоподбор
        <template #icon-right>
          <span class="i-mdi-auto-fix"></span>
        </template>
      </BaseButton> -->
    </div>
    <ExerciseList
      :workout-builder-mode="true"
      ref="exerciseListRef"
      :is-workout-mode="true"
      @add-to-workout="exercise => $emit('add-to-workout', exercise)"
    />

  </div>
</template>