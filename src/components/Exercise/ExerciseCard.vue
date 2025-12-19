<script setup>
import ExerciseQuickView from '@/components/Exercise/ExerciseQuickView.vue'
import { ref } from 'vue';

defineProps({
  exercise: {
    type: Object,
    required: true
  },
})

const emit = defineEmits(['add-to-workout'])
const showQuickView = ref(false);

const imageLoaded = ref(false);
const imageError = ref(false);

const handleImageLoad = () => {
  imageLoaded.value = true;
};

const handleImageError = () => {
  imageError.value = true;
};

function quickView() {
  showQuickView.value = true;
}
</script>

<template>
  <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer dark:border dark:border-gray-700">
    <div class="relative overflow-hidden rounded-t-2xl" @click="quickView">
      <div class="relative">
        <img
          :src="exercise.image_url"
          :alt="exercise.translated_name"
          class="w-full h-48 object-cover rounded-t-xl"
          @load="handleImageLoad"
          @error="handleImageError"
        >

        <div
          v-if="imageError"
          class="absolute inset-0 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-t-xl"
        >
          <span class="text-gray-500 dark:text-gray-400">Изображение недоступно</span>
        </div>

        <!-- Показываем спиннер во время загрузки -->
        <div
          v-if="!imageLoaded && !imageError"
          class="absolute inset-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center rounded-t-xl"
        >
          <span class="i-mdi-loading animate-spin text-2xl text-gray-400 dark:text-gray-500"></span>
        </div>
      </div>
      <div
        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
        <span class="i-mdi-eye text-2xl text-white"></span>
      </div>
    </div>

    <div class="p-4 space-y-3" @click.stop="emit('add-to-workout', exercise)">
      <h3 class="font-semibold text-lg dark:text-white">{{ exercise.translated_name }}</h3>

      <div class="flex flex-wrap gap-2">
        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 text-xs">
          {{ exercise.difficulty_level.translated_name }}
        </span>
      </div>

      <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
        <span class="i-mdi-arm-flex text-lg"></span>
        <span v-for="muscle in exercise.muscle_groups" :key="muscle.id">{{ muscle.translated_name }}</span>
      </div>

      <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400 text-sm">
        <span class="i-mdi-dumbbell text-lg"></span>
        <span v-for="equipment in exercise.equipment" :key="equipment.id">{{ equipment.translated_name }}</span>
      </div>
    </div>
  </div>
  <ExerciseQuickView :exercise="exercise" :show="showQuickView" @close="showQuickView = false" />
</template>