<script setup>
import { useRouter } from 'vue-router'
import ExerciseForm from '@/components/Exercise/ExerciseForm.vue'
import BaseButton from '@/components/ui/BaseButton.vue'

const router = useRouter()

const handleSuccess = () => {
    router.push('/')
}
</script>

<template>
    <main class="container mx-auto py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Добавление нового упражнения</h1>
                <BaseButton variant="text" @click="router.push('/')">
                    <template #icon-right>
                        <span class="i-mdi-close"></span>
                    </template>
                </BaseButton>
            </div>

            <ExerciseForm @success="handleSuccess" />
        </div>
    </main>
</template>