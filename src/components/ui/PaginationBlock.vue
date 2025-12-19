<script setup>
import BaseButton from '@/components/ui/BaseButton.vue';

defineProps({
  meta: {
    type: Object,
    required: true
  },
  links: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['page-change']);

const changePage = (page) => {
  if (page && !isNaN(page)) {
    emit('page-change', page);
  }
};
</script>

<template>
  <div v-if="meta.last_page > 1" class="flex justify-center mt-8">
    <nav class="flex items-center gap-1">
      <!-- Кнопка "Предыдущая" -->
      <BaseButton
        @click="changePage(meta.current_page - 1)"
        :disabled="meta.current_page === 1"
        variant="ghost"
        size="sm"
        class="!px-2"
      >
        <span class="i-mdi-chevron-left text-lg"></span>
      </BaseButton>

      <!-- Первая страница -->
      <BaseButton
        v-if="meta.current_page > 3"
        @click="changePage(1)"
        variant="ghost"
        size="sm"
      >
        1
      </BaseButton>

      <!-- Многоточие в начале -->
      <span v-if="meta.current_page > 4" class="px-2 py-1.5 text-gray-500">...</span>

      <!-- Страницы вокруг текущей -->
      <BaseButton
        v-for="page in meta.last_page"
        :key="page"
        v-show="page >= meta.current_page - 1 && page <= meta.current_page + 1 && page > 0 && page <= meta.last_page"
        @click="changePage(page)"
        :variant="meta.current_page === page ? 'primary' : 'ghost'"
        size="sm"
      >
        {{ page }}
      </BaseButton>

      <!-- Многоточие в конце -->
      <span v-if="meta.current_page < meta.last_page - 3" class="px-2 py-1.5 text-gray-500">...</span>

      <!-- Последняя страница -->
      <BaseButton
        v-if="meta.current_page < meta.last_page - 2"
        @click="changePage(meta.last_page)"
        variant="ghost"
        size="sm"
      >
        {{ meta.last_page }}
      </BaseButton>

      <!-- Кнопка "Следующая" -->
      <BaseButton
        @click="changePage(meta.current_page + 1)"
        :disabled="meta.current_page === meta.last_page"
        variant="ghost"
        size="sm"
        class="!px-2"
      >
        <span class="i-mdi-chevron-right text-lg"></span>
      </BaseButton>
    </nav>
  </div>
</template>