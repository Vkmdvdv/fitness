<script setup>
import BaseButton from '@/components/ui/BaseButton.vue'
import { computed, ref } from 'vue'
import { useFiltersStore } from '@/stores/filters';

const filtersStore = useFiltersStore();
const props = defineProps({
  options: {
    type: Array,
    required: true
  }
})

const selectedEquipment = ref([])

const toggleFilter = (item) => {
  if (selectedEquipment.value.includes(item.id)) {
    selectedEquipment.value = selectedEquipment.value.filter(id => id !== item.id)
  } else {
    selectedEquipment.value.push(item.id)
  }
  filtersStore.setEquipment(selectedEquipment);
}
const equipment = computed(() => [
  ...props.options
])

</script>

<template>
  <div>
    <h3 class="text-base font-semibold mb-4 flex items-center gap-1.5">
      <span class="i-mdi-dumbbell text-xl text-violet-600"></span>
      Оборудование
    </h3>

    <div class="flex flex-wrap gap-1.5">
      <BaseButton v-for="item in equipment" :key="item.id" variant="primary" size="sm"
        :selected="selectedEquipment.includes(item.id)" @click="toggleFilter(item)">
        <!-- <span :class="[item.icon, 'text-base']"></span> -->
        <span class="text-xs font-medium whitespace-nowrap">{{ item.name }}</span>
      </BaseButton>
    </div>
  </div>
</template>