<script setup>
import { ref } from 'vue'
import LoginForm from '@/components/Auth/LoginForm.vue'
import RegisterForm from '@/components/Auth/RegisterForm.vue'

// Режим авторизации: 'login' или 'register'
const authMode = ref('login')
</script>

<template>
  <div class="flex flex-col items-center justify-center p-6">
    <h1 class="text-2xl font-bold mb-6">
      {{ authMode === 'login' ? 'Авторизация' : 'Регистрация' }}
    </h1>

    <!-- Toggler для переключения между формами -->
    <div class="mb-6 bg-gray-100 rounded-full p-1 flex w-full max-w-xs">
      <div class="relative w-full h-10">
        <!-- Фон переключателя -->
        <div class="absolute top-0 left-0 h-full w-1/2 bg-indigo-600 rounded-full transition-all duration-300 transform"
          :style="{ transform: `translateX(${authMode === 'register' ? '100%' : '0'})` }"></div>

        <!-- Кнопки -->
        <div class="absolute top-0 left-0 w-full h-full flex">
          <button @click="authMode = 'login'"
            class="flex-1 h-full flex items-center justify-center font-medium z-10 transition-colors duration-300"
            :class="authMode === 'login' ? 'text-white' : 'text-gray-700'">
            Вход
          </button>
          <button @click="authMode = 'register'"
            class="flex-1 h-full flex items-center justify-center font-medium z-10 transition-colors duration-300"
            :class="authMode === 'register' ? 'text-white' : 'text-gray-700'">
            Регистрация
          </button>
        </div>
      </div>
    </div>

    <!-- Форма авторизации -->
    <LoginForm v-if="authMode === 'login'" />

    <!-- Форма регистрации -->
    <RegisterForm v-if="authMode === 'register'" />
  </div>
</template>