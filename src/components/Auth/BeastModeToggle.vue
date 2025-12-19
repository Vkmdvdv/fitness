<script setup>
import { defineProps, defineEmits } from 'vue'
import BaseButton from '@/components/ui/BaseButton.vue'

defineProps({
  beastMode: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['toggle'])

const toggleBeastMode = () => {
  emit('toggle')
}
</script>

<template>
  <div class="flex flex-col items-center">
    <div class="relative w-64 h-64 mb-4 overflow-hidden rounded-lg">
      <!-- Изображения -->
      <img
        src="/Jj7C1aNl.jpg"
        alt="Cute cat" class="absolute w-full h-full object-cover transition-all duration-100 transform"
        :class="beastMode ? 'opacity-0 scale-110' : 'opacity-100 scale-100'" />
      <img
        src="/rIsT9t2-.jpg"
        alt="Monster cat" class="absolute w-full h-full object-cover transition-all duration-100 transform"
        :class="beastMode ? 'opacity-100 scale-100' : 'opacity-0 scale-90'" />
    </div>

    <!-- Кнопка переключения режима -->
    <BaseButton
      @click="toggleBeastMode"
      :variant="beastMode ? 'danger' : 'primary'"
      class="flex items-center gap-2"
    >
      <span v-if="beastMode" class="i-mdi-arm-flex text-xl"></span>
      <span v-else class="i-mdi-cat text-xl"></span>
      {{ beastMode ? 'Успокоиться' : 'Режим зверя' }}
    </BaseButton>
  </div>
</template>

<style scoped>
.transform {
  will-change: transform, opacity;
}
</style>