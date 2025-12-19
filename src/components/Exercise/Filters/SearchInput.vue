<script setup>
import { useFiltersStore } from '@/stores/filters';
import BaseInput from '@/components/ui/BaseInput.vue';
// import BaseButton from '@/components/ui/BaseButton.vue';

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Найти упражнение...'
  },
  icon: {
    type: String,
    default: 'i-mdi-magnify'
  },
  iconColor: {
    type: String,
    default: 'text-violet-600'
  },
  title: {
    type: String,
    default: 'Поиск'
  },
  showSortButton: {
    type: Boolean,
    default: true
  }
});

const filtersStore = useFiltersStore();
</script>

<template>
  <div>
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-base font-semibold flex items-center gap-1.5">
        <span :class="[props.icon, 'text-xl', props.iconColor]"></span>
        {{ props.title }}
      </h3>
      <!-- <BaseButton v-if="showSortButton" variant="secondary" size="sm" @click="filtersStore.toggleSort">
        <span class="text-xs font-medium">Сложность</span>
        <template #icon-right>
          <span :class="[
            'text-base',
            filtersStore.sortByDifficulty === 'asc'
              ? 'i-mdi-sort-ascending'
              : 'i-mdi-sort-descending'
          ]"></span>
        </template>
      </BaseButton> -->
    </div>

    <BaseInput
      v-model="filtersStore.searchQuery"
      :placeholder="props.placeholder"
      size="md"
    />
  </div>
</template>