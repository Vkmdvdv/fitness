<script setup>
import { useToastStore } from '@/stores/toast'
import BaseButton from './BaseButton.vue'

const toastStore = useToastStore()

const getToastClasses = (type) => {
    const classes = {
        success: 'bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-200 border-green-200 dark:border-green-800',
        error: 'bg-red-50 dark:bg-red-900/50 text-red-800 dark:text-red-200 border-red-200 dark:border-red-800',
        warning: 'bg-yellow-50 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-200 border-yellow-200 dark:border-yellow-800',
        info: 'bg-blue-50 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 border-blue-200 dark:border-blue-800'
    }
    return classes[type] || classes.info
}

const getToastIcon = (type) => {
    const icons = {
        success: 'i-mdi-check-circle',
        error: 'i-mdi-alert-circle',
        warning: 'i-mdi-alert',
        info: 'i-mdi-information'
    }
    return icons[type] || icons.info
}
</script>

<template>
    <div class="fixed bottom-0 right-0 p-4 z-50">
        <TransitionGroup name="toast" tag="div" class="flex flex-col gap-2">
            <div v-for="toast in toastStore.toasts" :key="toast.id" :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg border shadow-sm min-w-[320px] max-w-md dark:shadow-lg',
                getToastClasses(toast.type)
            ]">
                <span :class="[getToastIcon(toast.type), 'text-xl']"></span>
                <p class="flex-1">{{ toast.message }}</p>
                <BaseButton size="sm" @click="toastStore.removeToast(toast.id)">
                    <template #icon-right>
                        <span class="i-mdi-close"></span>
                    </template>
                </BaseButton>
            </div>
        </TransitionGroup>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(30px);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>