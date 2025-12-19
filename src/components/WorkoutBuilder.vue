<script setup>
import { ref, defineEmits } from 'vue'
import { useWorkoutsStore } from '@/stores/workouts'
import { useToastStore } from '@/stores/toast'
import BaseButton from './ui/BaseButton.vue'

const emit = defineEmits(['close', 'save'])
const workoutsStore = useWorkoutsStore()
const toastStore = useToastStore()

const workoutName = ref('Новая тренировка')
const workoutExercises = ref([])

const addExerciseToWorkout = (exercise) => {
    workoutExercises.value.push({
        ...exercise,
        sets: 3,
        reps: 12
    })
}

const removeExercise = (index) => {
    workoutExercises.value.splice(index, 1)
}

const saveWorkout = () => {
    try {
        workoutsStore.addWorkout({
            name: workoutName.value,
            exercises: workoutExercises.value
        })

        toastStore.addToast({
            message: 'Тренировка сохранена',
            type: 'success'
        })

        emit('save')
    } catch (error) {
        toastStore.addToast({
            message: 'Ошибка при сохранении тренировки',
            type: 'error'
        })
    }
}

defineExpose({
    addExerciseToWorkout
})
</script>

<template>
    <div class="space-y-6">
        <div class="flex gap-4 items-start">
            <div class="flex-grow">
                <input v-model="workoutName" type="text"
                    class="w-full px-4 py-2 rounded-xl border focus:outline-none focus:border-violet-500"
                    placeholder="Название тренировки">
            </div>

            <BaseButton :disabled="workoutExercises.length === 0" @click="saveWorkout">
                Сохранить
                <template #icon-right>
                    <span class="i-mdi-check"></span>
                </template>
            </BaseButton>
        </div>

        <div v-if="workoutExercises.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div v-for="(exercise, index) in workoutExercises" :key="exercise.id"
                class="p-4 bg-gray-50 rounded-xl group">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-grow">
                        <p class="font-medium">{{ exercise.name }}</p>
                        <div class="flex items-center gap-3 mt-1 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <span class="i-mdi-repeat"></span>
                                {{ exercise.sets }} подходов
                            </span>
                            <span class="flex items-center gap-1">
                                <span class="i-mdi-dumbbell"></span>
                                {{ exercise.reps }} повторений
                            </span>
                        </div>
                    </div>
                    <BaseButton variant="text" @click="removeExercise(index)"
                        class="opacity-0 group-hover:opacity-100 transition-opacity">
                        <template #icon-right>
                            <span class="i-mdi-delete text-rose-500"></span>
                        </template>
                    </BaseButton>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-8 text-gray-500 bg-gray-50 rounded-xl">
            Добавьте упражнения в тренировку
        </div>
    </div>
</template>