<script setup>
import AppHeader from '@/components/AppHeader.vue'
import BetaBanner from '@/components/ui/BetaBanner.vue';
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <AppHeader />
    <BetaBanner
      message="🚀 Добро пожаловать в бета-версию ⚡BoostMode! Мы активно развиваемся и будем рады вашим отзывам."
      variant="info" />
    <BetaBanner variant="warning"
      message="Раздел тренировки переработан и находится в тестовом режиме. Некоторые функции могут работать некорректно." />
    <main class="px-4 sm:p-0">
      <router-view />
    </main>
  </div>
</template>