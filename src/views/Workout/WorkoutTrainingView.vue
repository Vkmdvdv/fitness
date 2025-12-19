<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useWorkoutsStore } from '@/stores/workouts'
// import { useUserStore } from '@/stores/user'
import BaseButton from '@/components/ui/BaseButton.vue'

const route = useRoute()
const router = useRouter()
const workoutsStore = useWorkoutsStore()
// const userStore = useUserStore()

const workout = ref(null)
const currentExerciseIndex = ref(0)
const timer = ref(0)
const restTimer = ref(0)
const isResting = ref(false)
const startTime = ref(Date.now())
const totalReps = ref(0)
const skippedExercises = ref(new Set())
const restTimeLeft = ref(0)
const isRestEnded = ref(false)
const currentSet = ref(1)
const isLastSet = computed(() => currentSet.value === currentExercise.value?.sets)

const currentExercise = computed(() =>
    workout.value?.exercises[currentExerciseIndex.value]
)

const progress = computed(() => ({
    current: currentExerciseIndex.value + 1,
    total: workout.value?.exercises.length
}))

const formatTime = (seconds) => {
    const minutes = Math.floor(seconds / 60)
    const remainingSeconds = seconds % 60
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`
}

onMounted(async () => {
    const workoutId = route.params.id
    workout.value = workoutsStore.getWorkoutById(workoutId)
    if (!workout.value) {
        router.push('/workouts')
        return
    }
    startTime.value = Date.now()
})

onBeforeUnmount(() => {
    clearInterval(timer.value)
    clearInterval(restTimer.value)
})

const startExerciseTimer = () => {
    if (currentExercise.value.duration) {
        let timeLeft = currentExercise.value.duration
        timer.value = setInterval(() => {
            timeLeft--
            if (timeLeft <= 0) {
                clearInterval(timer.value)
                startRest()
            }
        }, 1000)
    }
}

const startRest = () => {
    isResting.value = true
    isRestEnded.value = false

    if (restTimeLeft.value === 0) {
        restTimeLeft.value = currentExercise.value.restTime
    }

    restTimer.value = setInterval(() => {
        restTimeLeft.value--
        if (restTimeLeft.value <= 0) {
            clearInterval(restTimer.value)
            restTimeLeft.value = 0
            isRestEnded.value = true
        }
    }, 1000)
}

const adjustRestTime = (seconds) => {
    clearInterval(restTimer.value)
    const newTime = restTimeLeft.value + seconds

    if (newTime <= 0) {
        restTimeLeft.value = 0
        isRestEnded.value = true
        return
    }

    isRestEnded.value = false
    restTimeLeft.value = newTime
    startRest()
}

const completeSet = () => {
    clearInterval(timer.value)
    if (currentExercise.value.reps) {
        totalReps.value += currentExercise.value.reps
    }

    if (isLastSet.value) {
        isResting.value = false
        currentSet.value = 1
    } else {
        startRest()
    }
}

const skipExercise = () => {
    skippedExercises.value.add(currentExercise.value.id)
    nextExercise()
}

const nextExercise = () => {
    isResting.value = false
    isRestEnded.value = false
    restTimeLeft.value = 0
    currentSet.value = 1
    if (currentExerciseIndex.value < workout.value.exercises.length - 1) {
        currentExerciseIndex.value++
        startExerciseTimer()
    } else {
        finishWorkout()
    }
}

const finishWorkout = () => {
    const endTime = Date.now()
    const duration = Math.floor((endTime - startTime.value) / 1000)
    const points = calculatePoints({
        duration,
        totalReps: totalReps.value,
        skippedExercises: skippedExercises.value.size,
        totalExercises: workout.value.exercises.length
    })

    const workoutStats = {
        duration,
        totalReps: totalReps.value,
        skippedCount: skippedExercises.value.size,
        points,
        completedExercises: workout.value.exercises.length - skippedExercises.value.size
    }

    // userStore.addWorkoutStats(workoutStats)

    router.push({
        name: 'workout-summary',
        query: workoutStats
    })
}

const calculatePoints = ({ duration, totalReps, skippedExercises, totalExercises }) => {
    let points = 0

    points += totalReps * 2

    if (skippedExercises === 0) {
        points += 100
    }

    if (duration < 3600) {
        points += 50
    }

    points -= skippedExercises * 20

    return Math.max(points, 0)
}

const skipRest = () => {
    clearInterval(restTimer.value)
    isResting.value = false
    isRestEnded.value = false
    restTimeLeft.value = 0
}

const startNextSet = () => {
    skipRest()
    currentSet.value++
}
</script>

<template>
    <main class="container mx-auto py-8">
        <div v-if="workout && currentExercise" class="max-w-2xl mx-auto space-y-8">
            <div class="flex items-center justify-between">
                <div class="text-lg font-semibold">
                    Упражнение {{ progress.current }}/{{ progress.total }}
                    <span class="text-sm text-gray-500 ml-2">
                        Подход {{ currentSet }}/{{ currentExercise.sets }}
                    </span>
                </div>
                <div class="flex gap-2">
                    <BaseButton v-if="isResting" @click="startNextSet">
                        Следующий подход
                        <template #icon-right>
                            <span class="i-mdi-arrow-right"></span>
                        </template>
                    </BaseButton>
                    <BaseButton variant="text" @click="skipExercise">
                        Пропустить
                        <template #icon-right>
                            <span class="i-mdi-skip-next"></span>
                        </template>
                    </BaseButton>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 space-y-6">
                <h2 class="text-2xl font-bold">{{ currentExercise.name }}</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div v-if="currentExercise.reps" class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-500 mb-1">Повторения</div>
                        <div class="font-semibold text-xl">{{ currentExercise.reps }}</div>
                    </div>

                    <div v-if="currentExercise.sets" class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-500 mb-1">Текущий подход</div>
                        <div class="font-semibold text-xl">{{ currentSet }} из {{ currentExercise.sets }}</div>
                    </div>

                    <div v-if="currentExercise.weight" class="bg-gray-50 rounded-xl p-4">
                        <div class="text-sm text-gray-500 mb-1">Вес</div>
                        <div class="font-semibold text-xl">{{ currentExercise.weight }} кг</div>
                    </div>
                </div>

                <div v-if="isResting" class="text-center space-y-4">
                    <div class="text-3xl font-bold" :class="{
                        'text-violet-600': !isRestEnded,
                        'text-red-500': isRestEnded
                    }">
                        {{ formatTime(restTimeLeft) }}
                    </div>
                    <div class="text-gray-500">
                        {{ isRestEnded ? 'Перерыв окончен!' : 'Отдых' }}
                    </div>
                    <div class="flex justify-center gap-4">
                        <BaseButton @click="adjustRestTime(-30)" class="bg-gray-100">-30с</BaseButton>
                        <BaseButton @click="adjustRestTime(-10)" class="bg-gray-100">-10с</BaseButton>
                        <BaseButton @click="adjustRestTime(10)" class="bg-gray-100">+10с</BaseButton>
                        <BaseButton @click="adjustRestTime(30)" class="bg-gray-100">+30с</BaseButton>
                    </div>
                </div>

                <div v-if="!isResting" class="flex justify-center">
                    <BaseButton size="lg" @click="isLastSet ? nextExercise() : completeSet()">
                        {{ isLastSet ? 'Завершить упражнение' : `Завершить подход ${currentSet}` }}
                        <template #icon-right>
                            <span class="i-mdi-check"></span>
                        </template>
                    </BaseButton>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
                <h3 class="font-semibold text-lg">Как выполнять:</h3>
                <ol class="list-decimal list-inside space-y-3">
                    <li v-for="(step, index) in currentExercise.technique" :key="index" class="text-gray-600 pl-2">
                        {{ step }}
                    </li>
                </ol>
            </div>
        </div>
    </main>
</template>