<script setup>
import { onMounted, ref } from 'vue'
import BeastModeToggle from '@/components/Auth/BeastModeToggle.vue'
import FireParticles from '@/components/Auth/FireParticles.vue'
import AppFeatures from '@/components/Auth/AppFeatures.vue'

const logoVisible = ref(false)
const titleVisible = ref(false)
const subtitleVisible = ref(false)
const contentVisible = ref(false)
const beastMode = ref(false)
const cardContainer = ref(null)

onMounted(() => {
  // Анимация появления элементов с задержкой
  setTimeout(() => {
    logoVisible.value = true

    setTimeout(() => {
      titleVisible.value = true

      setTimeout(() => {
        subtitleVisible.value = true

        setTimeout(() => {
          contentVisible.value = true
        }, 300)
      }, 300)
    }, 300)
  }, 100)
})

const toggleBeastMode = () => {
  beastMode.value = !beastMode.value
}
</script>

<template>
  <div
    class="min-h-screen flex items-center justify-center transition-all duration-700 bg-gray-100 dark:bg-gray-900"
  >
    <!-- Двухколоночный макет -->
    <div
      ref="cardContainer"
      class="w-full max-w-6xl flex flex-col md:flex-row rounded-xl shadow-lg overflow-hidden transition-all duration-500 relative bg-white dark:bg-gray-800 dark:border dark:border-gray-700"
    >
      <!-- Огненные частицы -->
      <FireParticles :beast-mode="beastMode" :container="cardContainer" />

      <!-- Левая колонка с информацией о приложении -->
      <div class="w-full md:w-1/2 p-8 relative z-10">
        <div class="text-center mb-8">
          <div class="flex justify-center items-center gap-2 mb-2">
            <div
              :class="[
                'i-mdi-thunder text-4xl transition-all duration-500 transform',
                beastMode ? 'text-orange-600' : 'text-violet-600 dark:text-violet-400',
                logoVisible ? 'opacity-100 scale-100' : 'opacity-0 scale-0 rotate-180'
              ]"
            ></div>
            <h1
              :class="[
                'text-3xl font-bold bg-clip-text text-transparent transition-all duration-500',
                beastMode
                  ? 'bg-gradient-to-r from-orange-600 to-red-600'
                  : 'bg-gradient-to-r from-indigo-600 to-blue-500',
                titleVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'
              ]"
            >
              Boost Mode
            </h1>
          </div>
          <p
            :class="[
              'mt-2 transition-all duration-500',
              beastMode ? 'text-orange-600' : 'text-gray-600 dark:text-gray-300',
              subtitleVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'
            ]"
          >
            {{ beastMode ? 'Режим зверя активирован!' : 'Переключайся в режим зверя' }}
          </p>

          <!-- Переключатель режима зверя -->
          <div
            v-if="subtitleVisible"
            class="transition-all duration-500"
            :class="contentVisible ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
          >
            <BeastModeToggle :beast-mode="beastMode" @toggle="toggleBeastMode" />
          </div>

          <!-- Преимущества приложения - скрыты на мобильных устройствах -->
          <div class="hidden md:block">
            <AppFeatures :beast-mode="beastMode" :visible="contentVisible" />
          </div>
        </div>
      </div>

      <!-- Правая колонка с формой авторизации -->
      <div
        class="w-full md:w-1/2 p-8 relative z-10 flex items-center justify-center bg-gray-50 dark:bg-gray-800/50"
      >
        <div
          :class="[
            'w-full max-w-md transition-all duration-500',
            beastMode ? 'text-orange-600' : 'text-gray-800 dark:text-gray-200',
            contentVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'
          ]"
        >
          <router-view />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.transform {
  will-change: transform, opacity;
}
</style>