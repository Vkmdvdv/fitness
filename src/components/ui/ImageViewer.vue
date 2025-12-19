<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue'
import BaseButton from '@/components/ui/BaseButton.vue'

const props = defineProps({
  src: {
    type: String,
    required: true
  },
  alt: {
    type: String,
    default: 'Изображение'
  },
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close'])

const scale = ref(1)
const position = ref({ x: 0, y: 0 })
const isDragging = ref(false)
const startPosition = ref({ x: 0, y: 0 })
const imageLoaded = ref(false)
const imageError = ref(false)
const lastTouchDistance = ref(0)
const imageContainer = ref(null)

// Сброс масштаба и позиции при открытии/закрытии
watch(() => props.show, (newValue) => {
  if (newValue) {
    resetView()
    // Блокируем прокрутку страницы при открытии просмотрщика
    document.body.style.overflow = 'hidden'
  } else {
    // Разблокируем прокрутку страницы при закрытии
    document.body.style.overflow = ''
  }
})

// Обработчики событий изображения
const handleImageLoad = () => {
  imageLoaded.value = true
  imageError.value = false
}

const handleImageError = () => {
  imageError.value = true
  imageLoaded.value = false
}

// Увеличение масштаба
const zoomIn = () => {
  if (scale.value < 3) {
    scale.value += 0.5
  }
}

// Уменьшение масштаба
const zoomOut = () => {
  if (scale.value > 0.5) {
    scale.value -= 0.5
  }
}

// Сброс масштаба и позиции
const resetView = () => {
  scale.value = 1
  position.value = { x: 0, y: 0 }
}

// Обработчики перетаскивания мышью
const startDrag = (event) => {
  if (scale.value > 1) {
    isDragging.value = true
    startPosition.value = {
      x: event.clientX - position.value.x,
      y: event.clientY - position.value.y
    }
  }
}

const drag = (event) => {
  if (isDragging.value) {
    position.value = {
      x: event.clientX - startPosition.value.x,
      y: event.clientY - startPosition.value.y
    }
  }
}

const endDrag = () => {
  isDragging.value = false
}

// Обработка колесика мыши для масштабирования
const handleWheel = (event) => {
  event.preventDefault()
  if (event.deltaY < 0) {
    zoomIn()
  } else {
    zoomOut()
  }
}

// Обработчики сенсорных событий
const handleTouchStart = (event) => {
  if (event.touches.length === 1) {
    // Одиночное касание - перетаскивание
    if (scale.value > 1) {
      isDragging.value = true
      startPosition.value = {
        x: event.touches[0].clientX - position.value.x,
        y: event.touches[0].clientY - position.value.y
      }
    }
  } else if (event.touches.length === 2) {
    // Двойное касание - масштабирование
    isDragging.value = false
    const dx = event.touches[0].clientX - event.touches[1].clientX
    const dy = event.touches[0].clientY - event.touches[1].clientY
    lastTouchDistance.value = Math.sqrt(dx * dx + dy * dy)
  }
}

const handleTouchMove = (event) => {
  event.preventDefault() // Предотвращаем прокрутку страницы

  if (event.touches.length === 1 && isDragging.value) {
    // Одиночное касание - перетаскивание
    position.value = {
      x: event.touches[0].clientX - startPosition.value.x,
      y: event.touches[0].clientY - startPosition.value.y
    }
  } else if (event.touches.length === 2) {
    // Двойное касание - масштабирование
    const dx = event.touches[0].clientX - event.touches[1].clientX
    const dy = event.touches[0].clientY - event.touches[1].clientY
    const distance = Math.sqrt(dx * dx + dy * dy)

    // Определяем направление жеста (увеличение или уменьшение)
    if (Math.abs(distance - lastTouchDistance.value) > 10) {
      if (distance > lastTouchDistance.value) {
        // Разведение пальцев - увеличение
        if (scale.value < 3) scale.value += 0.1
      } else {
        // Сведение пальцев - уменьшение
        if (scale.value > 0.5) scale.value -= 0.1
      }
      lastTouchDistance.value = distance
    }
  }
}

const handleTouchEnd = () => {
  isDragging.value = false
}

// Двойной тап для увеличения/уменьшения
const handleDoubleTap = () => {
  if (scale.value > 1) {
    resetView()
  } else {
    scale.value = 2
  }
}

// Закрытие по клавише Escape
const handleKeyDown = (event) => {
  if (event.key === 'Escape') {
    emit('close')
  }
}

// Добавляем обработчик двойного тапа
let lastTap = 0
const setupDoubleTapDetection = () => {
  if (!imageContainer.value) return

  const handleTap = (event) => {
    const currentTime = new Date().getTime()
    const tapLength = currentTime - lastTap

    if (tapLength < 300 && tapLength > 0) {
      // Двойной тап
      handleDoubleTap()
      event.preventDefault()
    }

    lastTap = currentTime
  }

  imageContainer.value.addEventListener('touchend', handleTap)

  return () => {
    if (imageContainer.value) {
      imageContainer.value.removeEventListener('touchend', handleTap)
    }
  }
}

onMounted(() => {
  const cleanup = setupDoubleTapDetection()

  onUnmounted(() => {
    cleanup && cleanup()
    // Убедимся, что прокрутка страницы разблокирована при размонтировании
    document.body.style.overflow = ''
  })
})
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center"
    @mousedown.self="emit('close')" @keydown="handleKeyDown" tabindex="0">

    <!-- Панель управления -->
    <div class="absolute top-4 right-4 flex gap-2 z-10">
      <BaseButton variant="primary" size="sm" @click="zoomIn" title="Увеличить" class="hidden sm:flex">
        <span class="i-mdi-plus"></span>
      </BaseButton>
      <BaseButton variant="primary" size="sm" @click="zoomOut" title="Уменьшить" class="hidden sm:flex">
        <span class="i-mdi-minus"></span>
      </BaseButton>
      <BaseButton variant="secondary" size="sm" @click="resetView" title="Сбросить масштаб">
        <span class="i-mdi-refresh"></span>
      </BaseButton>
      <BaseButton variant="secondary" size="sm" @click="emit('close')" title="Закрыть">
        <span class="i-mdi-close"></span>
      </BaseButton>
    </div>

    <!-- Контейнер изображения -->
    <div ref="imageContainer" class="w-full h-full overflow-hidden flex items-center justify-center"
      @wheel="handleWheel"
      @touchstart="handleTouchStart"
      @touchmove="handleTouchMove"
      @touchend="handleTouchEnd">

      <!-- Изображение -->
      <img v-if="!imageError" :src="src" :alt="alt"
        :style="{
          transform: `scale(${scale}) translate(${position.x / scale}px, ${position.y / scale}px)`,
          cursor: scale > 1 ? 'grab' : 'default',
          transition: isDragging ? 'none' : 'transform 0.2s ease'
        }"
        class="max-w-full max-h-full object-contain prevent-select"
        @load="handleImageLoad"
        @error="handleImageError"
        @mousedown="startDrag"
        @mousemove="drag"
        @mouseup="endDrag"
        @mouseleave="endDrag"
        draggable="false"
      >

      <!-- Ошибка загрузки изображения -->
      <div v-if="imageError" class="text-center p-8 bg-gray-800 rounded-xl">
        <span class="i-mdi-image-off text-6xl text-gray-400 block mb-4"></span>
        <p class="text-gray-300 text-lg">Не удалось загрузить изображение</p>
      </div>

      <!-- Индикатор загрузки -->
      <div v-if="!imageLoaded && !imageError" class="text-center">
        <span class="i-mdi-loading animate-spin text-5xl text-gray-300 block mb-4"></span>
        <p class="text-gray-300">Загрузка изображения...</p>
      </div>
    </div>

    <!-- Подсказка - разная для мобильных и десктопа -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-gray-400 text-sm bg-black/50 px-4 py-2 rounded-full text-center max-w-[90%]">
      <span class="hidden sm:inline">Используйте колесико мыши для масштабирования, перетаскивайте изображение для просмотра</span>
      <span class="sm:hidden">Двойной тап для увеличения, используйте два пальца для масштабирования</span>
    </div>
  </div>
</template>

<style scoped>
/* Предотвращение выделения текста при перетаскивании */
.prevent-select {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  -webkit-touch-callout: none;
}
</style>