<script setup>
import { defineProps } from 'vue'

defineProps({
  beastMode: {
    type: Boolean,
    required: true
  },
  visible: {
    type: Boolean,
    required: true
  }
})
</script>

<template>
  <div class="mt-8 text-left" :class="[
    'transition-all duration-500',
    beastMode ? 'text-orange-400' : 'text-gray-600',
    visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'
  ]">
    <h3 class="font-bold text-xl mb-4">Преимущества:</h3>
    <ul class="space-y-3">
      <li class="flex items-start">
        <span :class="[beastMode ? 'text-orange-400' : 'text-indigo-600', 'i-mdi-check-circle text-xl mr-2']"></span>
        <span>Создавайте персонализированные тренировки</span>
      </li>
      <li class="flex items-start">
        <span :class="[beastMode ? 'text-orange-400' : 'text-indigo-600', 'i-mdi-check-circle text-xl mr-2']"></span>
        <span>Отслеживайте свой прогресс</span>
      </li>
      <li class="flex items-start">
        <span :class="[beastMode ? 'text-orange-400' : 'text-indigo-600', 'i-mdi-check-circle text-xl mr-2']"></span>
        <span>Анализируйте результаты и улучшайте показатели</span>
      </li>
    </ul>
  </div>
</template>