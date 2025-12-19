<script setup>
import BaseButton from '@/components/ui/BaseButton.vue'
defineProps({
  selectedExercises: {
    type: Array,
    required: true
  }
})

defineEmits(['auto-generate', 'save-workout'])
</script>

<template>
  <div class="fixed bottom-6 right-20 left-20 z-50 flex justify-center">
    <div class="inline-flex gap-4 px-4">
      <BaseButton @click="$emit('save-workout')" :disabled="selectedExercises.length === 0"
        class="dark:text-white dark:bg-indigo-600">
        <span class="inline md:hidden">Сохранить</span>
        <span class="hidden md:inline">Сохранить тренировку</span>
        <template #icon-right>
          <span class="i-mdi-check"></span>
        </template>
      </BaseButton>
    </div>
  </div>
</template>