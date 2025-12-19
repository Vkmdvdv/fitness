<script setup>
import { ref } from 'vue'
import ImageViewer from './ImageViewer.vue'
import LightningLoader from './LightningLoader.vue'

const props = defineProps({
  src: {
    type: String,
    default: ''
  },
  alt: {
    type: String,
    default: 'Изображение'
  },
  height: {
    type: String,
    default: 'h-64'
  },
  aspectRatio: {
    type: String,
    default: ''
  },
  objectFit: {
    type: String,
    default: 'object-cover'
  },
  rounded: {
    type: String,
    default: 'rounded-xl'
  },
  errorMessage: {
    type: String,
    default: 'Изображение не удалось загрузить'
  },
  loadingMessage: {
    type: String,
    default: 'Загрузка изображения...'
  },
  zoomable: {
    type: Boolean,
    default: true
  }
})

const imageLoaded = ref(false)
const imageError = ref(false)
const showViewer = ref(false)

const handleImageLoad = () => {
  imageLoaded.value = true
  imageError.value = false
}

const handleImageError = () => {
  imageError.value = true
  imageLoaded.value = false
}

const openViewer = () => {
  if (props.zoomable && props.src && !imageError.value) {
    showViewer.value = true
  }
}
</script>

<template>
  <div>
    <img
      v-if="src && !imageError"
      :src="src"
      :alt="alt"
      :class="[
        height,
        aspectRatio,
        objectFit,
        rounded,
        'w-full',
        zoomable && !imageError ? 'cursor-zoom-in hover:opacity-90 transition-opacity' : ''
      ]"
      @load="handleImageLoad"
      @error="handleImageError"
      @click="openViewer"
    >

    <!-- Заглушка при ошибке загрузки -->
    <div v-if="imageError || !src"
      :class="[height, aspectRatio, rounded, 'w-full bg-gray-200 dark:bg-gray-700 flex flex-col items-center justify-center']">
      <div class="text-center p-4 flex flex-col items-center">
        <span class="i-mdi-image-off text-4xl text-gray-400 dark:text-gray-500 mb-2"></span>
        <span class="text-gray-500 dark:text-gray-400 text-center">{{ errorMessage }}</span>
      </div>
    </div>

    <div v-if="src && !imageLoaded && !imageError"
      :class="[
        'absolute inset-0 bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center',
        rounded
      ]">
      <div class="text-center flex flex-col items-center">
        <LightningLoader :size="40" class="mb-3" />
        <span class="text-gray-500 dark:text-gray-400 text-center">{{ loadingMessage }}</span>
      </div>
    </div>

    <!-- Иконка увеличения для подсказки -->
    <div v-if="zoomable && src && !imageError && imageLoaded"
      class="absolute bottom-2 right-2 bg-black/50 rounded-full p-1 text-white opacity-70">
      <span class="i-mdi-magnify-plus text-lg"></span>
    </div>

    <!-- Полноэкранный просмотр -->
    <ImageViewer
      :src="src"
      :alt="alt"
      :show="showViewer"
      @close="showViewer = false"
    />
  </div>
</template>