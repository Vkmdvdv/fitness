import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useFiltersStore = defineStore('filters', () => {
  const difficulty = ref(5)
  const selectedMuscles = ref([])
  const selectedEquipment = ref([])
  const searchQuery = ref('')
  const sortByDifficulty = ref('asc')

  // function getEquipmentIcon(equipmentId) {
  //   const icons = {
  //     'no-equipment': 'i-mdi-human',
  //     bench: 'i-mdi-seat',
  //     'pull-up-bar': 'i-mdi-human',
  //     mat: 'i-mdi-yoga',
  //     'jump-rope': 'i-mdi-jump-rope',
  //     elevation: 'i-mdi-stairs',
  //   }
  //   return icons[equipmentId] || 'i-mdi-dumbbell'
  // }

  const setMuscles = (muscles) => {
    selectedMuscles.value = muscles
  }

  const setEquipment = (value) => {
    selectedEquipment.value = value
  }

  const setDifficulty = (value) => {
    difficulty.value = value
  }

  const setSearchQuery = (query) => {
    searchQuery.value = query
  }

  const toggleSort = () => {
    sortByDifficulty.value = sortByDifficulty.value === 'asc' ? 'desc' : 'asc'
  }

  const getActiveFilters = computed(() => ({
    difficulty_levels: difficulty.value,
    muscle_groups: selectedMuscles.value.includes('Все части тела') ? [] : selectedMuscles.value,
    equipment: selectedEquipment.value,
    search: searchQuery.value,
    sortByDifficulty: sortByDifficulty.value,
  }))

  return {
    difficulty,
    selectedMuscles,
    selectedEquipment,
    searchQuery,
    setMuscles,
    setEquipment,
    setDifficulty,
    setSearchQuery,
    getActiveFilters,
    sortByDifficulty,
    toggleSort,
  }
})