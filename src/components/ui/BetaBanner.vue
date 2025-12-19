<script setup>
import { ref } from 'vue';

defineProps({
  message: {
    type: String,
    default: 'Сайт находится в бета-версии. Некоторые функции могут работать некорректно.'
  },
  showCloseButton: {
    type: Boolean,
    default: true
  },
  variant: {
    type: String,
    default: 'info',
    validator: (value) => ['info', 'warning', 'success'].includes(value)
  }
});

const isVisible = ref(true);

const close = () => {
  isVisible.value = false;
};

const variantClasses = {
  info: 'bg-blue-50 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border-blue-200 dark:border-blue-800',
  warning: 'bg-amber-50 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800',
  success: 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800'
};

const variantIcons = {
  info: 'i-mdi-information',
  warning: 'i-mdi-alert',
  success: 'i-mdi-check-circle'
};
</script>

<template>
  <div v-if="isVisible" :class="[
    'w-full py-2 px-4 border-b flex items-center justify-center text-sm transition-all',
    variantClasses[variant]
  ]">
    <span :class="[variantIcons[variant], 'mr-2 text-lg']"></span>
    <p>{{ message }}</p>
    <button v-if="showCloseButton" @click="close"
      class="ml-3 p-1 rounded-full hover:bg-black/10 dark:hover:bg-white/10 transition-colors"
      aria-label="Закрыть">
      <span class="i-mdi-close text-lg"></span>
    </button>
  </div>
</template>