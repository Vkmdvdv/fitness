<script setup>
import { ref, onMounted, watch, computed } from 'vue'
import ExerciseFilters from '@/components/Exercise/ExerciseFilters.vue'
import ExerciseQuickView from '@/components/Exercise/ExerciseQuickView.vue'
import Pagination from '@/components/ui/PaginationBlock.vue'
import { exerciseApi } from '@/api/exercise'
import { useFiltersStore } from '@/stores/filters'
import LightningLoader from '@/components/ui/LightningLoader.vue'
import ImageWithFallback from '@/components/ui/ImageWithFallback.vue'

const emit = defineEmits(['add-to-workout'])

defineProps({
  workoutBuilderMode: {
    type: Boolean,
    default: false
  }
})

const filtersStore = useFiltersStore()

const exercises = ref([]);
const pagination = ref({
  meta: {
    current_page: 1,
    from: 1,
    last_page: 1,
    per_page: 12,
    to: 1,
    total: 0
  },
  links: {}
});
const isLoading = ref(false);
const isFiltersVisible = ref(true);
const currentPage = ref(1);
const activeFilters = ref({});
const selectedExercise = ref(null)
const showQuickView = ref(false)


const searchedMuscleIds = computed(() => {
  if (activeFilters.value.muscle_groups && activeFilters.value.muscle_groups.length > 0) {
    return activeFilters.value.muscle_groups.map(id => parseInt(id))
  }
  return []
})

const searchedEquipmentIds = computed(() => {
  if (activeFilters.value.equipment && activeFilters.value.equipment.length > 0) {
    return activeFilters.value.equipment.map(id => parseInt(id))
  }
  return []
})

// Функция для сортировки мышц: сначала искомые, затем основные, затем остальные
const getSortedMuscleGroups = (exercise) => {
  if (!exercise.muscle_groups || exercise.muscle_groups.length === 0) {
    return []
  }

  return [...exercise.muscle_groups].sort((a, b) => {
    // Если мышца в поиске, она имеет наивысший приоритет
    const aInSearch = searchedMuscleIds.value.includes(a.id)
    const bInSearch = searchedMuscleIds.value.includes(b.id)

    if (aInSearch && !bInSearch) return -1
    if (!aInSearch && bInSearch) return 1

    // Если обе мышцы в поиске или обе не в поиске, сортируем по типу (основная/вторичная)
    const aIsPrimary = isPrimaryMuscle(a)
    const bIsPrimary = isPrimaryMuscle(b)

    if (aIsPrimary && !bIsPrimary) return -1
    if (!aIsPrimary && bIsPrimary) return 1

    // Если всё равно, сортируем по имени
    return a.translated_name.localeCompare(b.translated_name)
  })
}

// Функция для сортировки оборудования: сначала искомое, затем остальное
const getSortedEquipment = (exercise) => {
  if (!exercise.equipment || exercise.equipment.length === 0) {
    return []
  }

  return [...exercise.equipment].sort((a, b) => {
    // Если оборудование в поиске, оно имеет наивысший приоритет
    const aInSearch = searchedEquipmentIds.value.includes(a.id)
    const bInSearch = searchedEquipmentIds.value.includes(b.id)

    if (aInSearch && !bInSearch) return -1
    if (!aInSearch && bInSearch) return 1

    // Если всё равно, сортируем по имени
    return a.translated_name.localeCompare(b.translated_name)
  })
}

const toggleFilters = () => {
  isFiltersVisible.value = !isFiltersVisible.value;
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

const updateFilters = (filters) => {
  activeFilters.value = filters;
  currentPage.value = 1; // Сбрасываем на первую страницу при изменении фильтров
  loadExercises();
}

const changePage = (page) => {
  currentPage.value = page;
  loadExercises();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

const loadExercises = async () => {
  isLoading.value = true;
  try {
    const response = await exerciseApi.getAllExercises({
      page: currentPage.value,
      ...activeFilters.value
    });

    if (response.success) {
      exercises.value = response.exercises.data;
      pagination.value = {
        meta: response.exercises.meta,
        links: response.exercises.links
      };
    } else {
      console.error('Ошибка при загрузке упражнений:', response.message);
    }
  } catch (error) {
    console.error('Ошибка при загрузке упражнений:', error);
  } finally {
    isLoading.value = false;
  }
}

const openQuickView = (exercise) => {
  selectedExercise.value = exercise
  showQuickView.value = true
}

const closeQuickView = () => {
  showQuickView.value = false
}

// Функции для определения типов мышц и оборудования
const isPrimaryMuscle = (muscle) => muscle.pivot?.type === 'primary'
const isSearchedMuscle = (muscle) => searchedMuscleIds.value.includes(muscle.id)
const isSearchedEquipment = (equipment) => searchedEquipmentIds.value.includes(equipment.id)

// Функции для получения классов стилей
const getMuscleClass = (muscle) => {
  if (isSearchedMuscle(muscle)) {
    return 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-medium'
  } else if (isPrimaryMuscle(muscle)) {
    return 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400 font-medium'
  } else {
    return 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
  }
}

const getEquipmentClass = (equipment) => {
  if (isSearchedEquipment(equipment)) {
    return 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-medium'
  } else {
    return 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300'
  }
}

// Функция для перехода к детальному просмотру упражнения
const quickView = (exerciseId, event) => {
  if (!event.target.closest('a') && !event.target.closest('button')) {
    event.preventDefault();
    event.stopPropagation();
    const exercise = exercises.value.find(ex => ex.id === exerciseId);
    if (exercise) {
      openQuickView(exercise);
    }
  }
}

onMounted(() => {
  loadExercises();
})

watch(
  [() => filtersStore.sortByDifficulty],
  () => {
    loadExercises();
  }
)
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-[auto_1fr] gap-8 relative">
    <div :class="[isFiltersVisible ? 'md:w-[380px]' : 'md:w-0', 'transition-all duration-300 overflow-hidden']">
      <div v-show="isFiltersVisible">
        <ExerciseFilters @update:filters="updateFilters" />
      </div>
    </div>

    <div>
      <div v-if="isLoading" class="flex justify-center items-center h-64">
        <LightningLoader :size="80" />
      </div>

      <div v-else-if="exercises.length > 0">
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2">
          <div v-for="exercise in exercises" :key="exercise.id"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
            @click="quickView(exercise.id, $event)">
            <div class="relative h-40">
              <ImageWithFallback :src="exercise.image_url" :alt="exercise.translated_name" height="h-full"
                object-fit="object-cover" :zoomable="false" class="absolute inset-0" />
              <div class="absolute top-2 right-2">
                <span
                  class="px-2 py-1 rounded-full bg-amber-50 dark:bg-amber-900/60 text-amber-600 dark:text-amber-200 text-xs">
                  {{ exercise.difficulty_level.translated_name }}
                </span>
              </div>

              <div v-if="workoutBuilderMode" class="absolute bottom-2 right-2 z-10"
                @click.stop="emit('add-to-workout', exercise)">
                <button
                  class="w-10 h-10 rounded-full bg-violet-600 text-white shadow-md flex items-center justify-center hover:bg-violet-700 transition-colors">
                  <span class="i-mdi-plus text-xl"></span>
                </button>
              </div>
            </div>

            <div class="p-3 space-y-2">
              <h3 class="font-semibold text-base dark:text-white line-clamp-1">{{ exercise.translated_name }}</h3>

              <!-- Мышцы -->
              <div class="flex items-start gap-1.5">
                <span class="i-mdi-arm-flex text-sm text-gray-500 dark:text-gray-400 mt-0.5 flex-shrink-0"></span>
                <div class="flex flex-wrap gap-1.5 w-full max-h-[calc(1.5rem*2+0.375rem)] overflow-y-auto">
                  <span v-for="muscle in getSortedMuscleGroups(exercise)" :key="muscle.id" :class="[
                    'px-2 py-0.5 rounded-full text-xs flex items-center mb-1',
                    getMuscleClass(muscle)
                  ]">
                    <span v-if="isPrimaryMuscle(muscle)" class="i-mdi-star-outline mr-1 text-xs flex-shrink-0"></span>
                    <span v-if="isSearchedMuscle(muscle)" class="i-mdi-magnify mr-1 text-xs flex-shrink-0"></span>
                    {{ muscle.translated_name }}
                  </span>
                </div>
              </div>

              <!-- Оборудование -->
              <div v-if="exercise.equipment && exercise.equipment.length > 0" class="flex items-start gap-1.5">
                <span class="i-mdi-dumbbell text-sm text-gray-500 dark:text-gray-400 mt-0.5 flex-shrink-0"></span>
                <div class="flex flex-wrap gap-1.5 w-full max-h-[1.5rem] overflow-y-auto">
                  <span v-for="equipment in getSortedEquipment(exercise)" :key="equipment.id" :class="[
                    'px-2 py-0.5 rounded-full text-xs flex items-center',
                    getEquipmentClass(equipment)
                  ]">
                    <span v-if="isSearchedEquipment(equipment)" class="i-mdi-magnify mr-1 text-xs flex-shrink-0"></span>
                    {{ equipment.translated_name }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <Pagination :meta="pagination.meta" :links="pagination.links" @page-change="changePage" />
      </div>

      <div v-else class="flex flex-col items-center justify-center h-64 text-center">
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-2">Упражнения не найдены</p>
        <p class="text-gray-500 dark:text-gray-400">Попробуйте изменить параметры фильтрации</p>
        <span class="text-4xl mt-4">🔍</span>
      </div>
    </div>
  </div>

  <button @click="toggleFilters"
    class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-blue-600 text-white shadow-lg flex items-center justify-center hover:bg-blue-700 transition-colors duration-200"
    title="Переключить фильтры и прокрутить наверх">
    <span v-if="isFiltersVisible" class="i-mdi-filter-off text-xl"></span>
    <span v-else class="i-mdi-filter text-xl"></span>
  </button>

  <ExerciseQuickView v-if="selectedExercise" :exercise="selectedExercise" :show="showQuickView"
    @close="closeQuickView" />
</template>