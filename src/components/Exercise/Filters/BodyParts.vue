<script setup>
import BaseButton from '@/components/ui/BaseButton.vue';
import { computed, ref } from 'vue';
import { useFiltersStore } from '@/stores/filters';
const filtersStore = useFiltersStore();
const props = defineProps({
  options: {
    type: Array,
    default: () => [],
    required: true
  }
})

const muscleGroups = computed(() => [
  ...props.options
])

const selectedMuscleGroups = ref([]);

const toggleFilter = (muscle) => {
  if (selectedMuscleGroups.value.includes(muscle.id)) {
    selectedMuscleGroups.value = selectedMuscleGroups.value.filter(id => id !== muscle.id)
  } else {
    selectedMuscleGroups.value.push(muscle.id)
  }
  filtersStore.setMuscles(selectedMuscleGroups.value);
}


</script>

<template>
  <div>
    <h3 class="text-base font-semibold mb-4 flex items-center gap-1.5">
      <span class="i-mdi-arm-flex text-xl text-violet-600"></span>
      Целевые мышцы
    </h3>

    <div class="flex flex-wrap gap-1.5">
      <BaseButton v-for="muscle in muscleGroups" :key="muscle.id" variant="primary" size="sm"
        :selected="selectedMuscleGroups.includes(muscle.id)" @click="toggleFilter(muscle)">
        <span class="text-xs font-medium whitespace-nowrap">{{ muscle.name }}</span>
      </BaseButton>
    </div>
  </div>
</template>