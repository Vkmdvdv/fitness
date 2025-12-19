<script setup>
import { computed } from 'vue'
// import { useUserStore } from '@/stores/user'
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

// const userStore = useUserStore()

const chartData = computed(() => ({
    // labels: userStore.workoutHistory.map(h => new Date(h.date).toLocaleDateString()),
    // datasets: [
    //     {
    //         label: 'Очки',
    //         data: userStore.workoutHistory.map(h => h.points),
    //         borderColor: '#8b5cf6',
    //         backgroundColor: '#8b5cf6',
    //         tension: 0.4
    //     },
    //     {
    //         label: 'Повторения',
    //         data: userStore.workoutHistory.map(h => h.totalReps),
    //         borderColor: '#ec4899',
    //         backgroundColor: '#ec4899',
    //         tension: 0.4
    //     }
    // ]
}))

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true
        }
    },
    plugins: {
        legend: {
            position: 'top'
        }
    }
}
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-6">Прогресс тренировок</h3>

        <div v-if="userStore.workoutHistory.length > 0" class="h-[300px]">
            <Line :data="chartData" :options="chartOptions" />
        </div>

        <div v-else class="text-center text-gray-500 py-8">
            Начните тренироваться, чтобы увидеть свой прогресс
        </div>
    </div>
</template>