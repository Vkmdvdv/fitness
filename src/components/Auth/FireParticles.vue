<script setup>
import { ref, onMounted, watch, defineProps } from 'vue'

const props = defineProps({
  beastMode: {
    type: Boolean,
    required: true
  },
  container: {
    type: Object,
    required: true
  }
})

const particles = ref([])
const particlesContainer = ref(null)

// Создание эффекта огненных частиц
const createFireParticles = () => {
  if (!props.container) return

  particles.value = []
  const containerRect = props.container.getBoundingClientRect()

  // Создаем 100 частиц
  for (let i = 0; i < 100; i++) {
    // Определяем, с какой стороны карточки будет появляться частица
    const side = Math.floor(Math.random() * 4); // 0: верх, 1: право, 2: низ, 3: лево

    let x, y;

    switch(side) {
      case 0: // верх
        x = Math.random() * containerRect.width;
        y = 0;
        break;
      case 1: // право
        x = containerRect.width;
        y = Math.random() * containerRect.height;
        break;
      case 2: // низ
        x = Math.random() * containerRect.width;
        y = containerRect.height;
        break;
      case 3: // лево
        x = 0;
        y = Math.random() * containerRect.height;
        break;
    }

    particles.value.push({
      id: i,
      x,
      y,
      size: Math.random() * 15 + 5,
      speedX: (Math.random() - 0.5) * 3,
      speedY: side === 2 ? -(Math.random() * 5 + 3) : (Math.random() - 0.5) * 3,
      opacity: Math.random() * 0.7 + 0.3,
      hue: Math.floor(Math.random() * 30) + 10 // От оранжевого до красного
    })
  }

  animateParticles()
}

const animateParticles = () => {
  if (!props.beastMode || !props.container) return

  const containerRect = props.container.getBoundingClientRect()

  // Обновляем позиции частиц
  particles.value = particles.value.map(particle => {
    particle.x += particle.speedX
    particle.y += particle.speedY
    particle.opacity -= 0.01
    particle.size -= 0.1

    // Если частица исчезла или вышла за пределы, создаем новую
    if (particle.opacity <= 0 || particle.size <= 0 ||
        particle.x < -20 || particle.x > containerRect.width + 20 ||
        particle.y < -20 || particle.y > containerRect.height + 20) {

      // Определяем, с какой стороны карточки будет появляться новая частица
      const side = Math.floor(Math.random() * 4);

      let x, y;

      switch(side) {
        case 0: // верх
          x = Math.random() * containerRect.width;
          y = 0;
          break;
        case 1: // право
          x = containerRect.width;
          y = Math.random() * containerRect.height;
          break;
        case 2: // низ
          x = Math.random() * containerRect.width;
          y = containerRect.height;
          break;
        case 3: // лево
          x = 0;
          y = Math.random() * containerRect.height;
          break;
      }

      return {
        ...particle,
        x,
        y,
        size: Math.random() * 15 + 5,
        speedX: (Math.random() - 0.5) * 3,
        speedY: side === 2 ? -(Math.random() * 5 + 3) : (Math.random() - 0.5) * 3,
        opacity: Math.random() * 0.7 + 0.3,
        hue: Math.floor(Math.random() * 30) + 10
      }
    }

    return particle
  })

  // Продолжаем анимацию
  if (props.beastMode) {
    requestAnimationFrame(animateParticles)
  }
}

// Следим за изменением режима
watch(() => props.beastMode, (newValue) => {
  if (newValue) {
    createFireParticles()
  } else {
    particles.value = []
  }
}, { immediate: true })

// Инициализация при монтировании компонента
onMounted(() => {
  if (props.beastMode) {
    createFireParticles()
  }
})
</script>

<template>
  <div
    ref="particlesContainer"
    class="absolute inset-0 overflow-hidden pointer-events-none"
    :style="{ zIndex: beastMode ? 1 : -1 }"
  >
    <div
      v-for="particle in particles"
      :key="particle.id"
      class="absolute rounded-full"
      :style="{
        left: `${particle.x}px`,
        top: `${particle.y}px`,
        width: `${particle.size}px`,
        height: `${particle.size}px`,
        opacity: particle.opacity,
        backgroundColor: `hsl(${particle.hue}, 100%, 50%)`,
        boxShadow: `0 0 ${particle.size * 2}px ${particle.size}px hsla(${particle.hue}, 100%, 50%, 0.3)`,
        transform: `translate(-50%, -50%)`
      }"
    ></div>
  </div>
</template>