<script setup>
import { ref, onMounted } from 'vue';
import { filtersApi } from '@/api/filters';
import { watch } from 'vue'
import { useTagsStore } from '@/stores/tags'
import { useFiltersStore } from '@/stores/filters'
// import AddictionalTags from '@/components/Exercise/Filters/AddictionalTags.vue'
import BodyParts from '@/components/Exercise/Filters/BodyParts.vue'
import DifficultyLevel from '@/components/Exercise/Filters/DifficultyLevel.vue'
import Equipment from '@/components/Exercise/Filters/EquipmentBlock.vue'
import SearchInput from '@/components/Exercise/Filters/SearchInput.vue'

const emit = defineEmits(['update:filters'])
const tagsStore = useTagsStore()
const filtersStore = useFiltersStore()

const muscle_groups = ref([]);
const difficulty_levels = ref([]);
const equipment = ref([]);
// const tags = ref([]);
const isLoading = ref(true);

onMounted(async () => {
  isLoading.value = true;

  const response = await filtersApi.getExerciseFilters();

  if (response.success) {
    difficulty_levels.value = response.filters.difficulty_levels;
    muscle_groups.value = response.filters.muscle_groups;
    equipment.value = response.filters.equipment;
    // tags.value = response.filters.tags;
  }
  isLoading.value = false;
});

watch(
  [
    () => filtersStore.difficulty,
    () => filtersStore.selectedMuscles,
    () => filtersStore.selectedEquipment,
    () => filtersStore.searchQuery,
    () => filtersStore.sortByDifficulty,
    () => tagsStore.selectedTags
  ],
  () => {
    emit('update:filters', {
      ...filtersStore.getActiveFilters,
      tags: tagsStore.selectedTags.includes('Все') ? [] : tagsStore.selectedTags
    })
  },
  { deep: true, immediate: true }
)
</script>

<template>
  <div>
    <div class="bg-white dark:bg-gray-800 dark:border dark:border-gray-700 w-full rounded-2xl p-6 space-y-6 shadow-sm">
      <SearchInput placeholder="Найти упражнение..." icon="i-mdi-magnify" title="Поиск" />

      <DifficultyLevel :options="difficulty_levels" />

      <BodyParts :options="muscle_groups" />

      <Equipment :options="equipment" />

      <!-- <AddictionalTags /> -->
    </div>
  </div>
</template>