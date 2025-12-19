<script setup>
import { useThemeStore } from '@/stores/theme'
import BaseButton from '@/components/ui/BaseButton.vue'

const themeStore = useThemeStore()

const toggleTheme = () => {
  themeStore.toggleDarkMode()
}
</script>

<template>
  <BaseButton
    @click="toggleTheme"
    variant="text"
    class="rounded-full p-2"
    aria-label="Переключить тему"
  >
    <span v-if="themeStore.darkMode" class="i-mdi-moon-waning-crescent text-xl text-yellow-400"></span>
    <span v-else class="i-mdi-white-balance-sunny text-xl text-orange-500"></span>
  </BaseButton>
</template>