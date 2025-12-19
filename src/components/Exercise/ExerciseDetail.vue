<script setup>
import BaseButton from '@/components/ui/BaseButton.vue'
import ImageWithFallback from '@/components/ui/ImageWithFallback.vue'

defineProps({
  exercise: {
    type: Object,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: null
  },
  isUpdating: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['retry', 'update-main-image'])

const isPrimaryMuscle = (muscle) => {
  return muscle.pivot.type === 'primary'
}

const handleImageSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    emit('update-main-image', file)
  }
}
</script>

<template>
  <!-- Отображение загрузки -->
  <div v-if="loading" class="flex flex-col items-center justify-center py-16 space-y-4">
    <div class="relative w-16 h-16">
      <span class="absolute inset-0 i-mdi-loading animate-spin text-4xl text-violet-500"></span>
    </div>
    <p class="text-gray-500 dark:text-gray-400 text-sm">Загрузка упражнения...</p>
  </div>

  <!-- Отображение ошибки -->
  <div v-else-if="error"
    class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 p-6 rounded-xl text-center my-8">
    <p>{{ error }}</p>
    <BaseButton variant="secondary" size="sm" class="mt-4" @click="$emit('retry')">
      Попробовать снова
    </BaseButton>
  </div>

  <!-- Отображение данных упражнения -->
  <div v-else class="space-y-8">
    <h1 class="text-3xl font-bold mt-4 dark:text-white">{{ exercise.translated_name }}</h1>

    <!-- Основное изображение упражнения -->
    <div class="relative">
      <ImageWithFallback :src="exercise.image_url" :alt="exercise.translated_name" height="h-96" />
    </div>
    <div class="mt-2 flex justify-center">
      <input type="file" accept="image/*" class="hidden" ref="imageInput" @change="handleImageSelect" />
      <BaseButton variant="secondary" size="sm" :disabled="isUpdating" @click="$refs.imageInput.click()">
        <span class="i-mdi-image-edit mr-1"></span>
        {{ isUpdating ? 'Обновление...' : 'Изменить изображение' }}
      </BaseButton>
    </div>

    <div class="flex flex-wrap gap-3">
      <span class="px-4 py-2 rounded-xl bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">
        {{ exercise?.difficulty_level?.translated_name }}
      </span>
      <span v-for="category in exercise.categories" :key="category.id"
        class="px-4 py-2 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
        {{ category.translated_name }}
      </span>
    </div>

    <p class="text-gray-600 dark:text-gray-300 text-lg">{{ exercise.translated_description }}</p>

    <section>
      <h2 class="text-xl font-bold mb-4 dark:text-white">Целевые мышцы</h2>
      <div class="flex flex-wrap gap-2">
        <span v-for="muscle in exercise.muscle_groups" :key="muscle.id" :class="[
          'px-3 py-1.5 rounded-xl flex items-center relative group cursor-default',
          isPrimaryMuscle(muscle)
            ? 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 font-medium'
            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
        ]" :title="isPrimaryMuscle(muscle) ? 'Основная целевая мышца' : ''">
          <span v-if="isPrimaryMuscle(muscle)" class="i-mdi-star-outline mr-1.5 text-sm flex-shrink-0"></span>
          <span>{{ muscle.translated_name }}</span>

          <!-- Тултип -->
          <div v-if="isPrimaryMuscle(muscle)"
            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-black/80 text-white text-xs rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
            Основная целевая мышца
            <div
              class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-black/80">
            </div>
          </div>
        </span>
      </div>
    </section>

    <section>
      <h2 class="text-xl font-bold mb-4 dark:text-white">Оборудование</h2>
      <div class="flex flex-wrap gap-2">
        <span v-for="equipment in exercise.equipment" :key="equipment.id"
          class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-xl text-gray-700 dark:text-gray-300 flex items-center">
          {{ equipment.translated_name }}
        </span>
      </div>
    </section>

    <section v-if="exercise.technique && exercise.technique.length > 0">
      <h2 class="text-xl font-bold mb-4 dark:text-white">Техника выполнения</h2>
      <ol class="list-decimal list-inside space-y-2">
        <li v-for="(step, index) in exercise.technique" :key="index" class="text-gray-600 dark:text-gray-300">
          {{ step.translated_text }}
        </li>
      </ol>
    </section>

    <section v-if="exercise.tips && exercise.tips.length > 0">
      <h2 class="text-xl font-bold mb-4 dark:text-white">Советы</h2>
      <ul class="space-y-2">
        <li v-for="(tip, index) in exercise.tips" :key="index"
          class="flex items-start gap-2 text-gray-600 dark:text-gray-300">
          <span class="i-mdi-check text-emerald-500 text-xl shrink-0"></span>
          {{ tip.translated_text }}
        </li>
      </ul>
    </section>

    <section v-if="exercise.common_mistakes && exercise.common_mistakes.length > 0">
      <h2 class="text-xl font-bold mb-4 dark:text-white">Распространенные ошибки</h2>
      <ul class="space-y-2">
        <li v-for="(mistake, index) in exercise.common_mistakes" :key="index"
          class="flex items-start gap-2 text-gray-600 dark:text-gray-300">
          <span class="i-mdi-alert text-rose-500 text-xl shrink-0"></span>
          {{ mistake.translated_text }}
        </li>
      </ul>
    </section>

    <section v-if="exercise.images && exercise.images.length > 0">
      <h2 class="text-xl font-bold mb-4 dark:text-white">Галерея</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div v-for="(image, index) in exercise.images" :key="index" class="space-y-2">
          <ImageWithFallback :src="image.url" :alt="image.description" height="h-48" aspect-ratio="aspect-video" />
          <p class="text-sm text-gray-500 dark:text-gray-400">{{ image.description }}</p>
        </div>
      </div>
    </section>

    <!-- <section v-if="exercise.video_url">
      <h2 class="text-xl font-bold mb-4 dark:text-white">Видео</h2>
      <div class="aspect-video rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700">
        <iframe :src="exercise.video_url" class="w-full h-full" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen></iframe>
      </div>
    </section> -->
  </div>
</template>

<style scoped>
.image-error img {
  display: none;
}

.image-error .image-error-overlay {
  display: flex;
}
</style>