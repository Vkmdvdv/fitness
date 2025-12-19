<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  type: {
    type: String,
    default: 'text'
  },
  placeholder: {
    type: String,
    default: ''
  },
  label: {
    type: String,
    default: ''
  },
  id: {
    type: String,
    default: () => `input-${Math.random().toString(36).substring(2, 9)}`
  },
  required: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: ''
  },
  autocomplete: {
    type: String,
    default: 'off'
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  }
})

const emit = defineEmits(['update:modelValue', 'focus', 'blur', 'input'])

const updateValue = (event) => {
  emit('update:modelValue', event.target.value)
  emit('input', event)
}

const onFocus = (event) => {
  emit('focus', event)
}

const onBlur = (event) => {
  emit('blur', event)
}

const inputClasses = computed(() => {
  const classes = [
    'w-full rounded-xl transition-all duration-300',
    'appearance-none border focus:outline-none focus:ring-2',
    'bg-white dark:bg-gray-700',
    'text-gray-700 dark:text-gray-200',
    'border-gray-300 dark:border-gray-600',
    'focus:border-indigo-500 dark:focus:border-indigo-400',
    'focus:ring-indigo-500/20 dark:focus:ring-indigo-400/20'
  ]

  if (props.error) {
    classes.push('border-red-500 dark:border-red-600')
    classes.push('focus:border-red-500 dark:focus:border-red-600')
    classes.push('focus:ring-red-500/20 dark:focus:ring-red-600/20')
  }

  if (props.disabled) {
    classes.push('bg-gray-100 dark:bg-gray-800')
    classes.push('text-gray-500 dark:text-gray-400')
    classes.push('cursor-not-allowed')
  }

  // Размеры
  switch (props.size) {
    case 'sm':
      classes.push('px-2 py-1 text-sm')
      break
    case 'lg':
      classes.push('px-4 py-3 text-lg')
      break
    default: // md
      classes.push('px-3 py-2')
      break
  }

  return classes
})

const labelClasses = computed(() => [
  'block text-sm font-medium mb-1',
  'text-gray-700 dark:text-gray-300',
  props.required ? 'after:content-["*"] after:ml-0.5 after:text-red-500' : ''
])

const errorClasses = computed(() => [
  'text-red-500 dark:text-red-400 text-sm mt-1'
])
</script>

<template>
  <div class="w-full">
    <label v-if="label" :for="id" :class="labelClasses">
      {{ label }}
    </label>

    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      :autocomplete="autocomplete"
      :class="inputClasses"
      @input="updateValue"
      @focus="onFocus"
      @blur="onBlur"
    />

    <p v-if="error" :class="errorClasses">{{ error }}</p>
  </div>
</template>