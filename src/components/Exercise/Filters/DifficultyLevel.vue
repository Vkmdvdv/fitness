<script setup>
import { ref, watch } from 'vue'
import { useFiltersStore } from '@/stores/filters';

const filtersStore = useFiltersStore();

const props = defineProps({
  options: {
    type: Array,
    default: () => [],
    required: true
  }
})

const selectedDifficultyLevel = ref(0);

const getDifficultyLabel = (value) => {
  if (value === 0) return 'Все уровни';
  return props.options[value - 1].name;
}

const getDifficultyColor = (value, currentValue) => {
  if (value > currentValue) return 'text-gray-200'
  if (value <= props.options.length / 3) return 'text-emerald-500'
  if (value <= props.options.length / 3 * 2) return 'text-amber-500'
  return 'text-rose-500'
}

watch(
  selectedDifficultyLevel,
  (newDifficultyLevel) => {
    filtersStore.setDifficulty(newDifficultyLevel);
  },
  { immediate: true }
);
</script>

<template>
  <div v-if="props.options.length > 0">
    <h3 class="text-base font-semibold mb-4 flex items-center gap-1.5">
      <span class="i-game-icons-strong-man text-xl text-violet-600"></span>
      Сложность
    </h3>
    <div class="flex flex-col gap-3">
      <input v-model="selectedDifficultyLevel" type="range" min="1" :max="props.options.length || 5" step="1"
        class="w-full accent-violet-600">
      <div class="flex justify-between items-center">
        <span class="text-sm font-medium">{{ getDifficultyLabel(selectedDifficultyLevel) }}</span>
        <div class="flex gap-0.5">
          <span v-for="n in (props.options.length || 5)" :key="n" :class="[
            'i-mdi-dumbbell text-sm',
            getDifficultyColor(n, selectedDifficultyLevel)
          ]"></span>
        </div>
      </div>
    </div>
  </div>
</template>