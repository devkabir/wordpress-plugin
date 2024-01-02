<script lang="ts" setup>
defineProps(['modelValue'])

defineEmits(['update:modelValue'])
</script>

<template>
    <button :class="{
        'bg-indigo-600': modelValue,
        'bg-gray-200': !modelValue,
    }" aria-checked="false"
        class="relative inline-flex flex-shrink-0 w-10 h-5 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        role="switch" type="button" @click.prevent="$emit('update:modelValue', !modelValue)">
        <span :class="{
            'translate-x-5': modelValue,
            'translate-x-0': !modelValue,
        }" aria-hidden="true"
            class="inline-block w-4 h-4 transition duration-200 ease-in-out transform bg-white rounded-full shadow pointer-events-none ring-0"></span>
    </button>
</template>

<style scoped></style>