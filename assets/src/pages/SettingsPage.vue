<template>
    <div class="grid gap-4 ">
        <div class="grid grid-cols-2">
            <div class="col-span-2">
                <h1 class="block text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">{{ strings.title }}</h1>
                <p class="mb-2 text-sm font-semibold text-indigo-600">{{ strings.description }}</p>
            </div>
            <div class="justify-end col-span-1 py-2">
                <InputGroup :label="strings.form.inputs.enable.label" type="toggle"
                    :hint="strings.form.inputs.enable.description">
                    <SwitchInput v-model="store.settings.enable" :invalid="false" />
                </InputGroup>
            </div>
        </div>
        <div class="flex gap-4 ">
            <BaseButton :loading="store.loading" @click="store.save"> {{ strings.form.submit }} </BaseButton>
            <BaseButton type="lite"> {{ strings.form.reset }} </BaseButton>
        </div>
    </div>
</template>
<script lang="ts" setup>
import SwitchInput from '@/components/form/SwitchInput.vue';
import InputGroup from '@/components/form/InputGroup.vue';
import BaseButton from '@/components/form/BaseButton.vue';
import { useSettingsStore } from '@/stores/settings';
import { pluginStrings } from '@/composable';
import { onBeforeMount } from 'vue';
const strings = pluginStrings.settings;
const store = useSettingsStore();
onBeforeMount(() => {
    store.fetch();
})
</script>
