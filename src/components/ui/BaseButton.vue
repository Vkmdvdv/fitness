<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'outline', 'danger','text'].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    loading: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'button'
    },
    block: {
        type: Boolean,
        default: false
    },
    active: {
        type: Boolean,
        default: false
    },
    selected: {
        type: Boolean,
        default: false
    },
    centered: {
        type: Boolean,
        default: true
    }
})

const variantClasses = {
    primary: 'bg-transparent border border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white',
    secondary: 'bg-transparent border border-gray-300 text-gray-600 hover:border-indigo-600 hover:text-indigo-600',
    text: 'text-gray-600 hover:text-indigo-600 border-transparent',
    nav: 'text-gray-600 hover:text-indigo-600 bg-transparent hover:bg-transparent border border-transparent hover:border-indigo-600',
    danger: 'bg-transparent border border-rose-300 text-rose-600 hover:border-rose-600 hover:text-rose-600'
}

const selectedClasses = {
    primary: 'bg-indigo-600 text-gray',
    secondary: 'border-indigo-600 text-indigo-600',
    text: 'text-indigo-600',
    nav: 'border-indigo-600 text-indigo-600'
}

const sizeClasses = {
    sm: 'px-3 py-2',
    md: 'px-3 py-2 md:px-4 md:py-2',
    lg: 'px-6 py-3'
}

const buttonClasses = computed(() => {
    const classes = [
        'flex items-center gap-2 transition-all duration-300',
        props.centered ? 'justify-center' : '',
        'rounded-xl font-medium cursor-pointer border',
        props.selected ? selectedClasses[props.variant] : variantClasses[props.variant],
        sizeClasses[props.size],
        props.block ? 'w-full' : '',
        props.disabled ? 'opacity-50 cursor-not-allowed' : '',
        props.loading ? 'cursor-wait' : '',
        props.variant === 'nav' && props.active ? [
            'bg-transparent border-indigo-600',
            'text-indigo-600'
        ] : '',
        'focus:outline-none focus-visible:ring-1 focus-visible:ring-indigo-500'
    ]

    return classes
})
</script>

<template>
    <button :type="type" :class="buttonClasses" :disabled="disabled || loading"
        :aria-current="active ? 'page' : undefined">
        <span v-if="loading" class="i-mdi-loading animate-spin"></span>

        <slot v-else></slot>

        <slot name="icon-right"></slot>
    </button>
</template>