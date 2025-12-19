<script setup>
import BaseButton from '@/components/ui/BaseButton.vue'
defineProps({
  workoutName: {
    type: String,
    required: true
  }
})

defineEmits(['update:workoutName', 'next'])
</script>

<template>
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
    <h3 class="text-xl font-semibold mb-6 dark:text-white">Название тренировки</h3>
    <div class="max-w-md mx-auto">
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Введите название тренировки
        </label>
        <input
          :value="workoutName"
          @input="$emit('update:workoutName', $event.target.value)"
          type="text"
          class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:border-violet-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-lg"
          placeholder="Например: Тренировка верхней части тела">
      </div>

      <div class="flex justify-end mt-8">
        <BaseButton @click="$emit('next')">
          Далее
          <template #icon-right>
            <span class="i-mdi-arrow-right"></span>
          </template>
        </BaseButton>
      </div>
    </div>
  </div>
</template>