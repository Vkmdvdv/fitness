import { defineStore } from 'pinia'
import { ref, computed, watch } from 'vue'
import defaultExercisesData from '@/data/defaultExercises.json'

export const useExercisesStore = defineStore('exercises', () => {
  const savedExercises =
    JSON.parse(localStorage.getItem('exercises')) || defaultExercisesData.exercises
  const exercises = ref(savedExercises)

  watch(
    exercises,
    (newExercises) => {
      localStorage.setItem('exercises', JSON.stringify(newExercises))
    },
    { deep: true },
  )

  const getAllExercises = computed(() => exercises.value)

  const setExercises = (value) => {
    exercises.value = value
  }

  const addExercise = (exercise) => {
    const newExercise = {
      ...exercise,
      id: Math.max(0, ...exercises.value.map((e) => e.id)) + 1,
    }
    exercises.value.push(newExercise)
  }

  const removeExercise = (id) => {
    const exercise = exercises.value.find((e) => e.id === id)
    if (exercise && !exercise.isDefault) {
      exercises.value = exercises.value.filter((e) => e.id !== id)
    } else {
      console.warn('Нельзя удалить предустановленное упражнение')
    }
  }

  const updateExercise = (id, updatedData) => {
    const index = exercises.value.findIndex((e) => e.id === id)
    if (index !== -1) {
      const exercise = exercises.value[index]
      if (exercise.isDefault) {
        console.warn('Нельзя изменить предустановленное упражнение')
        return
      }
      exercises.value[index] = { ...exercise, ...updatedData }
    }
  }

  const filterExercises = ({
    difficulty = 5,
    targetMuscles = [],
    equipment = [],
    tags = [],
    searchQuery = '',
    sortByDifficulty = 'asc',
  } = {}) => {
    let filtered = exercises.value.filter((exercise) => {
      const matchesDifficulty = exercise.difficultyValue <= difficulty

      const matchesMuscles =
        targetMuscles.length === 0 ||
        targetMuscles.some((muscle) => exercise.targetMuscles.includes(muscle))

      const matchesEquipment =
        equipment.length === 0 ||
        (exercise.equipment && equipment.some((eq) => exercise.equipment.includes(eq)))

      const matchesTags =
        tags.length === 0 || (exercise.tags && tags.some((tag) => exercise.tags.includes(tag)))

      const matchesSearch =
        !searchQuery || exercise.name.toLowerCase().includes(searchQuery.toLowerCase())

      return matchesDifficulty && matchesMuscles && matchesEquipment && matchesTags && matchesSearch
    })

    filtered.sort((a, b) => {
      if (sortByDifficulty === 'asc') {
        return a.difficultyValue - b.difficultyValue
      } else {
        return b.difficultyValue - a.difficultyValue
      }
    })

    return filtered
  }

  return {
    exercises,
    getAllExercises,
    addExercise,
    removeExercise,
    updateExercise,
    filterExercises,
    setExercises,
  }
})