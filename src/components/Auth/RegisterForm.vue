<script setup>
import { ref, computed, watch } from 'vue'
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseInput from '@/components/ui/BaseInput.vue'

const userStore = useUserStore()
const router = useRouter()

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirm = ref('')
const isLoading = computed(() => userStore.isLoading)
const error = computed(() => userStore.error)
const validationError = ref('')
const passwordError = ref('')

// Проверка совпадения паролей
watch([password, passwordConfirm], ([newPassword, newConfirm]) => {
  if (newConfirm && newPassword !== newConfirm) {
    passwordError.value = 'Пароли не совпадают'
  } else {
    passwordError.value = ''
  }
})

// Проверка валидности формы без побочных эффектов
const formValidation = computed(() => {
  if (name.value.trim() === '') {
    return { valid: false, message: 'Имя обязательно для заполнения' }
  }

  if (email.value.trim() === '') {
    return { valid: false, message: 'Email обязателен для заполнения' }
  }

  if (password.value.trim() === '') {
    return { valid: false, message: 'Пароль обязателен для заполнения' }
  }

  if (password.value.length < 6) {
    return { valid: false, message: 'Пароль должен содержать не менее 6 символов' }
  }

  if (password.value !== passwordConfirm.value) {
    return { valid: false, message: 'Пароли не совпадают' }
  }

  return { valid: true, message: '' }
})

const isFormValid = computed(() => formValidation.value.valid)

// Обновляем сообщение об ошибке при изменении валидации
watch(formValidation, (newVal) => {
  validationError.value = newVal.message
})

const handleSubmit = async () => {
  if (!isFormValid.value) return

  const userData = {
    name: name.value,
    email: email.value,
    password: password.value,
    password_confirmation: passwordConfirm.value
  }

  const success = await userStore.register(userData)
  if (success) {
    router.push({ name: 'home' })
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="w-full max-w-md">
    <div v-if="error || validationError" class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded mb-4">
      {{ error || validationError }}
    </div>

    <div class="mb-4">
      <BaseInput
        v-model="name"
        type="text"
        label="Имя"
        placeholder="Введите ваше имя"
        required
      />
    </div>

    <div class="mb-4">
      <BaseInput
        v-model="email"
        type="email"
        label="Email"
        placeholder="Введите email"
        required
      />
    </div>

    <div class="mb-4">
      <BaseInput
        v-model="password"
        type="password"
        label="Пароль"
        placeholder="Введите пароль"
        required
      />
    </div>

    <div class="mb-6">
      <BaseInput
        v-model="passwordConfirm"
        type="password"
        label="Подтверждение пароля"
        placeholder="Повторите пароль"
        required
        :error="passwordError"
      />
    </div>

    <div class="flex items-center justify-between">
      <BaseButton
        type="submit"
        variant="primary"
        :disabled="!isFormValid || isLoading"
        block
      >
        <span v-if="isLoading">Загрузка...</span>
        <span v-else>Зарегистрироваться</span>
      </BaseButton>
    </div>
  </form>
</template>