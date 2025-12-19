<script setup>
// import { useUserStore } from '@/stores/user'

// const userStore = useUserStore()

const formatTime = (totalSeconds) => {
    const hours = Math.floor(totalSeconds / 3600)
    const minutes = Math.floor((totalSeconds % 3600) / 60)

    if (hours > 0) {
        return `${hours}ч ${minutes}м`
    }
    return `${minutes}м`
}
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-6">Статистика тренировок</h3>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <div class="text-sm text-gray-500">Всего тренировок</div>
                <div class="text-2xl font-semibold">
                    {{ userStore.stats.totalWorkouts }}
                </div>
            </div>

            <div class="space-y-2">
                <div class="text-sm text-gray-500">Серия дней</div>
                <div class="text-2xl font-semibold flex items-center gap-2">
                    {{ userStore.stats.streakDays }}
                    <span class="i-mdi-fire text-orange-500"></span>
                </div>
            </div>

            <div class="space-y-2">
                <div class="text-sm text-gray-500">Всего повторений</div>
                <div class="text-2xl font-semibold">
                    {{ userStore.stats.totalReps }}
                </div>
            </div>

            <div class="space-y-2">
                <div class="text-sm text-gray-500">Общее время</div>
                <div class="text-2xl font-semibold">
                    {{ formatTime(userStore.stats.totalTime) }}
                </div>
            </div>

            <div class="space-y-2">
                <div class="text-sm text-gray-500">Выполнено упражнений</div>
                <div class="text-2xl font-semibold">
                    {{ userStore.stats.completedExercises }}
                </div>
            </div>

            <div class="space-y-2">
                <div class="text-sm text-gray-500">Пропущено упражнений</div>
                <div class="text-2xl font-semibold text-gray-600">
                    {{ userStore.stats.skippedExercises }}
                </div>
            </div>
        </div>
    </div>
</template>