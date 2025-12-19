<script setup>
import { useRoute, useRouter } from 'vue-router'
import BaseButton from '@/components/ui/BaseButton.vue'

const route = useRoute()
const router = useRouter()

const workoutStats = {
    duration: parseInt(route.query.duration || '0'),
    totalReps: parseInt(route.query.totalReps || '0'),
    skippedCount: parseInt(route.query.skippedCount || '0'),
    points: parseInt(route.query.points || '0')
}

const formatDuration = (seconds) => {
    const minutes = Math.floor(seconds / 60)
    const hours = Math.floor(minutes / 60)
    const remainingMinutes = minutes % 60
    const remainingSeconds = seconds % 60

    if (hours > 0) {
        return `${hours}ч ${remainingMinutes}м ${remainingSeconds}с`
    }
    return `${minutes}м ${remainingSeconds}с`
}

const goToWorkouts = () => {
    router.push('/workouts')
}
</script>

<template>
    <main class="container mx-auto py-8">
        <div class="max-w-2xl mx-auto">
            <h2
                class="text-3xl font-bold mb-8 bg-gradient-to-r from-violet-600 via-fuchsia-500 to-pink-500 bg-clip-text text-transparent">
                Тренировка завершена!
            </h2>

            <div class="bg-white rounded-2xl shadow-sm p-6 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <div class="text-sm text-gray-500">Длительность</div>
                        <div class="text-2xl font-semibold">
                            {{ formatDuration(workoutStats.duration) }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-sm text-gray-500">Всего повторений</div>
                        <div class="text-2xl font-semibold">{{ workoutStats.totalReps }}</div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-sm text-gray-500">Пропущено упражнений</div>
                        <div class="text-2xl font-semibold">
                            {{ workoutStats.skippedCount }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-sm text-gray-500">Заработано очков</div>
                        <div class="text-2xl font-semibold text-violet-600">
                            +{{ workoutStats.points }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-center pt-6">
                    <BaseButton size="lg" @click="goToWorkouts">
                        Вернуться к тренировкам
                        <template #icon-right>
                            <span class="i-mdi-arrow-right"></span>
                        </template>
                    </BaseButton>
                </div>
            </div>
        </div>
    </main>
</template>