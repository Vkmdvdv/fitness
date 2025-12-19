<script setup>
// import { useRouter } from 'vue-router'
import ExerciseList from '@/components/Exercise/ExerciseList.vue'
// import BaseButton from '@/components/ui/BaseButton.vue'

// const router = useRouter()
</script>

<template>
  <main class="container mx-auto py-8">
    <div class="flex flex-col gap-6 mb-8" >
      <div class="flex justify-between items-center">
        <div>
          <h2
            class="text-3xl sm:text-4xl font-bold mb-2 bg-gradient-to-r from-violet-600 via-fuchsia-500 to-pink-500 bg-clip-text text-transparent">
            Каталог упражнений
          </h2>
          <p class="text-gray-600">Выбери упражнения для своей тренировки</p>
        </div>

        <div class="flex gap-3">
          <!-- <BaseButton @click="router.push('/add-exercise')">
            <span class="hidden sm:inline">Добавить упражнение</span>
            <template #icon-right>
              <span class="i-mdi-plus text-xl"></span>
            </template>
          </BaseButton> -->
        </div>
      </div>

      <ExerciseList />
    </div>
  </main>
</template>