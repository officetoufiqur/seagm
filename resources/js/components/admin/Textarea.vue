<script setup lang="ts">
const props = defineProps<{
    forr?: string;
    label?: string;
    placeholder?: string;
    modelValue?: string;
    rows?: number;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

function updateValue(event: Event) {
    const target = event.target as HTMLTextAreaElement | null;

    if (target) {
        emit('update:modelValue', target.value);
    }
}
</script>

<template>
    <div class="w-full">
        <label :for="props.forr" class="text-[#5D5D5D] font-medium text-sm">
            {{ props.label }}
        </label>

        <textarea :id="props.forr" :name="props.forr" :placeholder="props.placeholder" :rows="props.rows || 4"
            :value="props.modelValue" @input="updateValue"
            class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm resize-none"></textarea>
    </div>
</template>