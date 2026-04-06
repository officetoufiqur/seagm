<script setup lang="ts">
const props = defineProps<{
  forr?: string;
  label?: string;
  type?: string;
  placeholder?: string;
  modelValue?: string | number;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number): void;
}>();

function updateValue(event: Event) {
  const target = event.target as HTMLInputElement | null;

  if (target) {
    if (props.type === 'number') {
      emit('update:modelValue', target.valueAsNumber);
    } else {
      emit('update:modelValue', target.value);
    }
  }
}
</script>

<template>
  <div class="w-full">
    <label :for="props.forr" class="text-[#5D5D5D] font-medium text-sm">
      {{ props.label }}
    </label>

    <input
      :type="props.type || 'text'"
      :id="props.forr"
      :name="props.forr"
      :placeholder="props.placeholder"
      :value="props.modelValue"
      @input="updateValue"
      class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm"
    />
  </div>
</template>