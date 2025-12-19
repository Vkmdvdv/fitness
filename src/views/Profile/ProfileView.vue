<script setup>
import { useRouter } from 'vue-router'
import BaseButton from '@/components/ui/BaseButton.vue'
// import WorkoutStats from '@/components/Profile/WorkoutStats.vue'
// import ProgressChart from '@/components/Profile/ProgressChart.vue'
import UserCharacter from '@/components/Profile/UserCharacter.vue'
import { authApi } from '@/api/auth'

const router = useRouter()

const logout = async () => {
  await authApi.logout()
  router.push({ name: 'auth' })
}
</script>

<template>
  <main class="container mx-auto py-8">
    <div class="max-w-6xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Профиль</h1>
        <BaseButton variant="danger" @click="logout" class="flex items-center gap-2">
          <span class="i-mdi-logout"></span>
          Выйти
        </BaseButton>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        <UserCharacter />
        <!-- <WorkoutStats /> -->
      </div>
      <!-- <div class="mt-8">
        <ProgressChart />
      </div> -->
    </div>
  </main>
</template>