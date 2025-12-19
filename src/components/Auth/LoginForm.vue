<script setup>
import { ref, computed } from 'vue'
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'
import BaseButton from '@/components/ui/BaseButton.vue'
import BaseInput from '@/components/ui/BaseInput.vue'

const userStore = useUserStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const isLoading = computed(() => userStore.isLoading)
const error = computed(() => userStore.error)

const isFormValid = computed(() => {
  return email.value.trim() !== '' && password.value.trim() !== ''
})

const handleSubmit = async () => {
  if (!isFormValid.value) return

  const credentials = {
    email: email.value,
    password: password.value
  }

  const success = await userStore.login(credentials)
  if (success) {
    router.push({ name: 'home' })
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="w-full max-w-md">
    <div v-if="error" class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded mb-4">
      {{ error }}
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

    <div class="mb-6">
      <BaseInput
        v-model="password"
        type="password"
        label="Пароль"
        placeholder="Введите пароль"
        required
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
        <span v-else>Войти</span>
      </BaseButton>
    </div>
  </form>
</template>