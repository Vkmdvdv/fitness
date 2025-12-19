<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseButton from '@/components/ui/BaseButton.vue'
import ExerciseDetail from '@/components/Exercise/ExerciseDetail.vue'
import { exerciseApi } from '@/api/exercise'

const route = useRoute()
const router = useRouter()
const exercise = ref({
  translated_name: '',
  translated_description: '',
  difficulty_level: {
    translated_name: '',
  },
  categories: [],
  muscle_groups: [],
  technique: [],
  tips: [],
  common_mistakes: [],
  images: [],
  video_url: '',
  equipment: [],
})
const loading = ref(true)
const error = ref(null)
const isUpdating = ref(false)

// Получение данных об упражнении из API
const fetchExerciseDetails = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await exerciseApi.getExerciseById(route.params.id)

    if (response.success) {
      exercise.value = response.exercise
    } else {
      error.value = response.message || 'Не удалось загрузить упражнение'
      if (response.status === 404) {
        router.push('/')
      }
    }
  } catch (err) {
    console.error('Ошибка при загрузке упражнения:', err)
    error.value = 'Произошла ошибка при загрузке упражнения'
  } finally {
    loading.value = false
  }
}

const handleMainImageUpdate = async (file) => {
  isUpdating.value = true
  try {
    const formData = new FormData()
    formData.append('image', file)

    const response = await exerciseApi.updateMainImage(route.params.id, formData)
    if (response.success) {
      exercise.value.image_url = response.imageUrl
    } else {
      error.value = response.message
    }
  } catch (err) {
    console.error('Ошибка при обновлении изображения:', err)
    error.value = 'Произошла ошибка при обновлении изображения'
  } finally {
    isUpdating.value = false
  }
}

onMounted(() => {
  fetchExerciseDetails()
})

const goBack = () => {
  if (window.history.length > 2) {
    router.back()
  } else {
    router.push('/')
  }
}
</script>

<template>
  <main class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto">
      <BaseButton @click="goBack">
        <span class="i-mdi-arrow-left"></span> Назад
      </BaseButton>

      <ExerciseDetail
        :exercise="exercise"
        :loading="loading"
        :error="error"
        :is-updating="isUpdating"
        @update-main-image="handleMainImageUpdate"
        @retry="fetchExerciseDetails"
      />
    </div>
  </main>
</template>