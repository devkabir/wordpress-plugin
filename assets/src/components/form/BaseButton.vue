<script lang="ts" setup>
defineProps({
    type: {
        type: String,
        default: 'primary'
    },
    loading: {
        type: Boolean,
        default: false
    }

})
</script>

<template>
    <button :class="{
        'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 text-white': type === 'primary',
        'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-gray-500': type === 'lite',
    }"
        class="flex items-center gap-2 px-4 py-2 font-medium border rounded-md shadow focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed"
        type="button" v-bind="$attrs">
        <svg v-if="loading" :class="{
            'text-white': type === 'primary',
            'text-gray-900': type === 'lite'
        }" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                fill="currentColor"></path>
        </svg>
        <span v-else>
            <slot />
        </span>
    </button>
</template>