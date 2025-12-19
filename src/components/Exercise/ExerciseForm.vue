<script setup>
import { ref } from 'vue'
import { useFiltersStore } from '@/stores/filters'
import { useTagsStore } from '@/stores/tags'
import { useExercisesStore } from '@/stores/exercises'
import BaseButton from '@/components/ui/BaseButton.vue'

const filtersStore = useFiltersStore()
const tagsStore = useTagsStore()
const exercisesStore = useExercisesStore()

const emit = defineEmits(['success'])
const activeTab = ref('basic') // main, technique, media

const exercise = ref({
    name: '',
    difficulty: 'Начинающий',
    difficultyValue: 2,
    description: '',
    targetMuscles: [],
    equipment: [],
    tags: [],
    technique: [''],
    tips: [''],
    mistakes: [''],
    variations: [''],
    mainImage: '',
    images: [{ url: '', description: '' }],
    video: '',
    isDefault: false
})

const difficulties = [
    { label: 'Новичок', value: 1 },
    { label: 'Начинающий', value: 2 },
    { label: 'Средний', value: 3 },
    { label: 'Продвинутый', value: 4 },
    { label: 'Эксперт', value: 5 }
]

// Вспомогательные функции для работы с массивами
const addArrayItem = (array, defaultValue = '') => {
    array.push(defaultValue)
}

const removeArrayItem = (array, index) => {
    array.splice(index, 1)
}

const addImage = () => {
    exercise.value.images.push({ url: '', description: '' })
}

const removeImage = (index) => {
    exercise.value.images.splice(index, 1)
}

const toggleSelection = (array, item) => {
    const index = array.indexOf(item)
    if (index === -1) {
        array.push(item)
    } else {
        array.splice(index, 1)
    }
}

const handleSubmit = () => {
    if (validateForm()) {
        // Очищаем пустые элементы массивов
        exercise.value.technique = exercise.value.technique.filter(t => t.trim())
        exercise.value.tips = exercise.value.tips.filter(t => t.trim())
        exercise.value.mistakes = exercise.value.mistakes.filter(m => m.trim())
        exercise.value.variations = exercise.value.variations.filter(v => v.trim())
        exercise.value.images = exercise.value.images.filter(img => img.url.trim())

        exercisesStore.addExercise(exercise.value)
        emit('success')
    }
}

const validateForm = () => {
    if (!exercise.value.name.trim()) {
        alert('Введите название упражнения')
        return false
    }
    if (!exercise.value.description.trim()) {
        alert('Добавьте описание упражнения')
        return false
    }
    if (!exercise.value.mainImage.trim()) {
        alert('Добавьте главное изображение')
        return false
    }
    if (exercise.value.targetMuscles.length === 0) {
        alert('Выберите целевые мышцы')
        return false
    }
    if (exercise.value.technique.filter(t => t.trim()).length === 0) {
        alert('Добавьте хотя бы один шаг техники выполнения')
        return false
    }
    if (exercise.value.images.filter(img => img.url.trim()).length === 0) {
        alert('Добавьте хотя бы одно изображение')
        return false
    }
    return true
}

const tabs = [
    { id: 'basic', label: 'Основное', icon: 'i-mdi-information' },
    { id: 'technique', label: 'Техника', icon: 'i-mdi-run' },
    { id: 'media', label: 'Медиа', icon: 'i-mdi-image' }
]
</script>

<template>
    <div class="bg-white rounded-2xl p-6 shadow-sm">
        <div class="flex gap-2 mb-8 border-b border-gray-200">
            <BaseButton v-for="tab in tabs" :key="tab.id" variant="nav" :selected="activeTab === tab.id"
                @click="activeTab = tab.id">
                <template #icon-right>
                    <span :class="tab.icon"></span>
                </template>
                {{ tab.label }}
            </BaseButton>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-8">
            <div v-if="activeTab === 'basic'" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Название упражнения*
                    </label>
                    <input v-model="exercise.name" type="text" required
                        class="w-full  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Главное изображение*
                    </label>
                    <input v-model="exercise.mainImage" type="url" required placeholder="URL изображения"
                        class="w-full  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                    <div v-if="exercise.mainImage" class="mt-3">
                        <img :src="exercise.mainImage" alt="Главное изображение"
                            class="w-full h-64 object-cover rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Описание*
                    </label>
                    <textarea v-model="exercise.description" rows="4" required
                        class="w-full  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Сложность
                    </label>
                    <select v-model="exercise.difficultyValue"
                        class="w-full  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition"
                        @change="exercise.difficulty = difficulties.find(d => d.value === exercise.difficultyValue).label">
                        <option v-for="diff in difficulties" :key="diff.value" :value="diff.value">
                            {{ diff.label }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Целевые мышцы*
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <BaseButton v-for="muscle in filtersStore.muscleGroups.filter(m => m.name !== 'Все части тела')"
                            :key="muscle.name" variant="secondary" size="sm"
                            :selected="exercise.targetMuscles.includes(muscle.name)"
                            @click="toggleSelection(exercise.targetMuscles, muscle.name)">
                            <template #icon-right>
                                <span :class="muscle.icon"></span>
                            </template>
                            {{ muscle.name }}
                        </BaseButton>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Оборудование
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <BaseButton v-for="item in filtersStore.equipment" :key="item.name" variant="secondary"
                            size="sm" :selected="exercise.equipment.includes(item.name)"
                            @click="toggleSelection(exercise.equipment, item.name)">
                            <template #icon-right>
                                <span :class="item.icon"></span>
                            </template>
                            {{ item.name }}
                        </BaseButton>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Теги
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <BaseButton v-for="tag in tagsStore.getAllTags.filter(t => t.name !== 'Все')" :key="tag.name"
                            variant="secondary" size="sm" :selected="exercise.tags.includes(tag.name)"
                            @click="toggleSelection(exercise.tags, tag.name)">
                            <template #icon-right>
                                <span :class="tag.icon"></span>
                            </template>
                            {{ tag.name }}
                        </BaseButton>
                    </div>
                </div>
            </div>

            <div v-if="activeTab === 'technique'" class="space-y-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Техника выполнения*
                    </label>
                    <div class="space-y-3">
                        <div v-for="(step, index) in exercise.technique" :key="index" class="flex gap-3">
                            <input v-model="exercise.technique[index]" type="text" :placeholder="`Шаг ${index + 1}`"
                                class="flex-1  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                            <BaseButton variant="text" size="sm" @click="removeArrayItem(exercise.technique, index)">
                                <template #icon-right>
                                    <span class="i-mdi-delete text-xl"></span>
                                </template>
                            </BaseButton>
                        </div>
                        <BaseButton variant="text" size="sm" @click="addArrayItem(exercise.technique)">
                            <template #icon-right>
                                <span class="i-mdi-plus"></span>
                            </template>
                            Добавить шаг
                        </BaseButton>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Советы
                    </label>
                    <div class="space-y-3">
                        <div v-for="(tip, index) in exercise.tips" :key="index" class="flex gap-3">
                            <input v-model="exercise.tips[index]" type="text" :placeholder="`Совет ${index + 1}`"
                                class="flex-1  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                            <BaseButton variant="text" size="sm" @click="removeArrayItem(exercise.tips, index)">
                                <template #icon-right>
                                    <span class="i-mdi-delete text-xl"></span>
                                </template>
                            </BaseButton>
                        </div>
                        <BaseButton variant="text" size="sm" @click="addArrayItem(exercise.tips)">
                            <template #icon-right>
                                <span class="i-mdi-plus"></span>
                            </template>
                            Добавить совет
                        </BaseButton>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Распространенные ошибки
                    </label>
                    <div class="space-y-3">
                        <div v-for="(mistake, index) in exercise.mistakes" :key="index" class="flex gap-3">
                            <input v-model="exercise.mistakes[index]" type="text" :placeholder="`Ошибка ${index + 1}`"
                                class="flex-1  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                            <BaseButton variant="text" size="sm" @click="removeArrayItem(exercise.mistakes, index)">
                                <template #icon-right>
                                    <span class="i-mdi-delete text-xl"></span>
                                </template>
                            </BaseButton>
                        </div>
                        <BaseButton variant="text" size="sm" @click="addArrayItem(exercise.mistakes)">
                            <template #icon-right>
                                <span class="i-mdi-plus"></span>
                            </template>
                            Добавить ошибку
                        </BaseButton>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Вариации упражнения
                    </label>
                    <div class="space-y-3">
                        <div v-for="(variation, index) in exercise.variations" :key="index" class="flex gap-3">
                            <input v-model="exercise.variations[index]" type="text"
                                :placeholder="`Вариация ${index + 1}`"
                                class="flex-1  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                            <BaseButton variant="text" size="sm" @click="removeArrayItem(exercise.variations, index)">
                                <template #icon-right>
                                    <span class="i-mdi-delete text-xl"></span>
                                </template>
                            </BaseButton>
                        </div>
                        <BaseButton variant="text" size="sm" @click="addArrayItem(exercise.variations)">
                            <template #icon-right>
                                <span class="i-mdi-plus"></span>
                            </template>
                            Добавить вариацию
                        </BaseButton>
                    </div>
                </div>
            </div>

            <div v-if="activeTab === 'media'" class="space-y-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        Изображения*
                    </label>
                    <div class="space-y-4">
                        <div v-for="(image, index) in exercise.images" :key="index"
                            class="p-4 bg-gray-50 rounded-xl space-y-3">
                            <div class="flex gap-3">
                                <input v-model="image.url" type="url" placeholder="URL изображения"
                                    class="flex-1  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                                <BaseButton variant="text" size="sm" @click="removeImage(index)">
                                    <template #icon-right>
                                        <span class="i-mdi-delete text-xl"></span>
                                    </template>
                                </BaseButton>
                            </div>
                            <input v-model="image.description" type="text" placeholder="Описание изображения"
                                class="w-full  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                            <img v-if="image.url" :src="image.url" :alt="image.description"
                                class="w-full h-64 object-cover rounded-xl">
                        </div>
                        <BaseButton variant="text" size="sm" @click="addImage">
                            <template #icon-right>
                                <span class="i-mdi-plus"></span>
                            </template>
                            Добавить изображение
                        </BaseButton>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ссылка на видео (YouTube)
                    </label>
                    <input v-model="exercise.video" type="url" placeholder="https://www.youtube.com/embed/..."
                        class="w-full  py-2.5 rounded-xl border border-gray-200 focus:(border-violet-600 ring-1 ring-violet-600) outline-none transition">
                    <p class="mt-2 text-sm text-gray-500">
                        Используйте ссылку для встраивания (embed) видео
                    </p>
                    <div v-if="exercise.video" class="mt-4 aspect-video rounded-xl overflow-hidden">
                        <iframe :src="exercise.video" class="w-full h-full" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <BaseButton variant="text" @click="$router.push('/')">
                    Отмена
                </BaseButton>
                <BaseButton variant="primary" type="submit">
                    Сохранить
                </BaseButton>
            </div>
        </form>
    </div>
</template>