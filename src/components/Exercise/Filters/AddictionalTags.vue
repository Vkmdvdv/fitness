<script setup>
import { useTagsStore } from '@/stores/tags'
import BaseButton from '@/components/ui/BaseButton.vue'

const tagsStore = useTagsStore()

const toggleFilter = (item) => {
    tagsStore.toggleTag(item.name)
}
</script>

<template>
    <div>
        <h3 class="text-base font-semibold mb-4 flex items-center gap-1.5">
            <span class="i-mdi-tag-multiple text-xl text-violet-600"></span>
            Дополнительные атрибуты
        </h3>

        <div class="relative mb-3">
            <input v-model="tagsStore.searchQuery" type="text" placeholder="Поиск атрибутов..."
                class="w-full py-1.5 rounded-lg bg-gray-100 border-2 border-transparent focus:border-violet-600 focus:bg-white focus:outline-none transition-colors">
        </div>

        <div class="flex flex-wrap gap-1.5">
            <BaseButton v-for="tag in tagsStore.getFilteredTags" :key="tag.name" variant="primary" size="sm"
                :selected="tagsStore.selectedTags.includes(tag.name)" @click="toggleFilter(tag)">
                <span :class="[tag.icon, 'text-base']"></span>
                <span class="text-xs font-medium whitespace-nowrap">{{ tag.name }}</span>
            </BaseButton>
        </div>

        <div v-if="tagsStore.searchQuery && tagsStore.getFilteredTags.length <= 1"
            class="mt-4 text-center text-gray-500">
            <span class="i-mdi-emoticon-sad text-2xl"></span>
            <p class="mt-2">Атрибуты не найдены</p>
        </div>
    </div>
</template>