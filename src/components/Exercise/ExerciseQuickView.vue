<script setup>
import { useRouter } from 'vue-router'
import BaseButton from '@/components/ui/BaseButton.vue'
import ImageWithFallback from '@/components/ui/ImageWithFallback.vue'

const props = defineProps({
  exercise: {
    type: Object,
    required: true
  },
  show: {
    type: Boolean,
    required: false,
    default: false
  }
})

const router = useRouter();
const emit = defineEmits(['close'])

const goToDetail = () => {
  router.push(`/exercise/${props.exercise.id}`)
  emit('close')
}

// Функция для определения, является ли группа мышц основной
const isPrimaryMuscle = (muscle) => {
  return muscle.pivot?.type === 'primary'
}
</script>

<template>
  <div v-if="show" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="$emit('close')">
    <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
      <div class="p-6 flex items-center justify-between border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-xl font-bold dark:text-white">{{ exercise.translated_name }}</h3>
        <div class="flex items-center gap-4">
          <BaseButton variant="primary" @click="goToDetail">
            Подробнее
            <template #icon-right>
              <span class="i-mdi-arrow-right"></span>
            </template>
          </BaseButton>
          <BaseButton variant="secondary" @click="$emit('close')">
            <template #icon-right>
              <span class="i-mdi-close"></span>
            </template>
          </BaseButton>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <div class="relative">
          <ImageWithFallback
            :src="exercise.image_url"
            :alt="exercise.translated_name"
            height="h-64"
            :zoomable="true"
          />
        </div>

        <div class="flex flex-wrap gap-2">
          <span
            class="px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-sm">
            {{ exercise.difficulty_level.translated_name }}
          </span>
        </div>

        <p class="text-gray-600 dark:text-gray-300">{{ exercise.translated_description }}</p>

        <div class="space-y-2">
          <div class="text-gray-600 dark:text-gray-400">Целевые мышцы:</div>
          <div class="flex flex-wrap gap-2">
            <span v-for="muscle in exercise.muscle_groups" :key="muscle.id"
              :class="[
                'px-3 py-1 rounded-lg text-sm flex items-center',
                isPrimaryMuscle(muscle)
                  ? 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400'
                  : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
              ]">
              <span v-if="isPrimaryMuscle(muscle)" class="i-mdi-star-outline mr-1.5 text-sm flex-shrink-0"></span>
              {{ muscle.translated_name }}
            </span>
          </div>
        </div>

        <div class="space-y-2">
          <div class="text-gray-600 dark:text-gray-400 font-medium">Оборудование:</div>
          <div class="flex flex-wrap gap-2">
            <span v-for="equipment in exercise.equipment" :key="equipment.id"
              class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm flex items-center">
              {{ equipment.translated_name }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>