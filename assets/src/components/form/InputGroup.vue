<template>
    <div class="flex items-start gap-8 py-2">
        <label :id="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <div>
            <div class="relative">
                <slot :id="id" />
                <div v-if="invalid" class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <ExclamationCircleIcon class="w-6 h-6 text-rose-600" />
                </div>
            </div>
            <p v-if="invalid" :class="errorClass">{{ errorMessage }}</p>
            <p v-else class="mt-2 text-sm text-gray-500">{{ hint }}</p>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ExclamationCircleIcon } from "@heroicons/vue/24/outline";

const id = Math.random().toString(36).substring(7);

defineProps({
    label: {
        type: String,
        default: "Input Label",
    },

    errorClass: {
        type: String,
        default: "mt-2 text-sm text-rose-600",
    },
    errorMessage: {
        type: String,
        default: "",
    },

    invalid: {
        type: Boolean,
        default: false,
    },
    hint: {
        type: String,
        default: "Hint is not added",
    }
});


</script>