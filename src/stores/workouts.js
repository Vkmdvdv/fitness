import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'

export const useWorkoutsStore = defineStore('workouts', () => {
  const workouts = ref(JSON.parse(localStorage.getItem('workouts') || '[]'))

  watch(
    workouts,
    (newWorkouts) => {
      localStorage.setItem('workouts', JSON.stringify(newWorkouts))
    },
    { deep: true },
  )

  const allWorkouts = computed(() => workouts.value)

  const addWorkout = (workout) => {
    const newWorkout = {
      id: Date.now(),
      createdAt: new Date().toISOString(),
      ...workout,
    }
    workouts.value.push(newWorkout)
    return newWorkout
  }

  const removeWorkout = (workoutId) => {
    const index = workouts.value.findIndex((w) => w.id === workoutId)
    if (index > -1) {
      workouts.value.splice(index, 1)
      return true
    }
    return false
  }

  const updateWorkout = (workoutId, updates) => {
    const workout = workouts.value.find((w) => w.id === workoutId)
    if (workout) {
      Object.assign(workout, {
        ...updates,
        updatedAt: new Date().toISOString(),
      })
      return true
    }
    return false
  }

  const getWorkoutById = (workoutId) => {
    return workouts.value.find((w) => w.id == workoutId)
  }

  return {
    workouts,
    allWorkouts,
    addWorkout,
    removeWorkout,
    updateWorkout,
    getWorkoutById,
  }
})