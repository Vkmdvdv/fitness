import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useTagsStore = defineStore('tags', () => {
  const defaultTags = [
    { name: 'Все', icon: 'i-mdi-tag-multiple' },
    { name: 'Для начинающих', icon: 'i-mdi-star-outline' },
    { name: 'Без прыжков', icon: 'i-mdi-run' },
    { name: 'На выносливость', icon: 'i-mdi-heart-pulse' },
    { name: 'На баланс', icon: 'i-mdi-yoga' },
    { name: 'На гибкость', icon: 'i-mdi-human' },
    { name: 'Силовые', icon: 'i-mdi-arm-flex' },
    { name: 'Кардио', icon: 'i-mdi-lightning-bolt' },
    { name: 'Для осанки', icon: 'i-mdi-human' },
    { name: 'Для похудения', icon: 'i-mdi-fire' },
    { name: 'Реабилитация', icon: 'i-mdi-medical-bag' },
    { name: 'Домашние', icon: 'i-mdi-home' },
  ]

  const tags = ref(defaultTags)
  const selectedTags = ref(['Все'])
  const searchQuery = ref('')

  const getAllTags = computed(() => tags.value)

  const getFilteredTags = computed(() => {
    if (!searchQuery.value) return tags.value

    const query = searchQuery.value.toLowerCase()
    return tags.value.filter((tag) => tag.name.toLowerCase().includes(query) || tag.name === 'Все')
  })

  const getSelectedTags = computed(() => selectedTags.value)

  const setSearchQuery = (query) => {
    searchQuery.value = query
  }

  const toggleTag = (tagName) => {
    if (tagName === 'Все') {
      selectedTags.value = ['Все']
      return
    }

    selectedTags.value = selectedTags.value.filter((i) => i !== 'Все')

    const index = selectedTags.value.indexOf(tagName)
    if (index === -1) {
      selectedTags.value.push(tagName)
    } else {
      selectedTags.value.splice(index, 1)
    }

    if (selectedTags.value.length === 0) {
      selectedTags.value = ['Все']
    }
  }

  const addTag = (tag) => {
    if (!tags.value.find((t) => t.name === tag.name)) {
      tags.value.push(tag)
    }
  }

  const removeTag = (tagName) => {
    const tag = tags.value.find((t) => t.name === tagName)
    if (tag && tag.name !== 'Все') {
      tags.value = tags.value.filter((t) => t.name !== tagName)
      selectedTags.value = selectedTags.value.filter((t) => t !== tagName)
      if (selectedTags.value.length === 0) {
        selectedTags.value = ['Все']
      }
    }
  }

  return {
    tags,
    selectedTags,
    searchQuery,
    getAllTags,
    getFilteredTags,
    getSelectedTags,
    setSearchQuery,
    toggleTag,
    addTag,
    removeTag,
  }
})