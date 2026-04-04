<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'

const props = defineProps<{
    modelValue: string
}>()

const emit = defineEmits(['update:modelValue'])

const editorRef = ref<HTMLDivElement | null>(null)
let editor: any = null

onMounted(async () => {
    const ClassicEditor = (window as any).ClassicEditor

    await nextTick()

    if (!editorRef.value || !ClassicEditor) {
        console.error('CKEditor not loaded')
        return
    }

    ClassicEditor
        .create(editorRef.value)
        .then((ed: any) => {
            editor = ed

            editor.setData(props.modelValue || '')

            editor.model.document.on('change:data', () => {
                emit('update:modelValue', editor.getData())
            })
        })
        .catch((err: any) => {
            console.error(err)
        })
})

onBeforeUnmount(() => {
    if (editor) {
        editor.destroy()
    }
})

// Sync external changes
watch(
    () => props.modelValue,
    (val) => {
        if (editor && val !== editor.getData()) {
            editor.setData(val || '')
        }
    }
)
</script>

<template>
    <div ref="editorRef"></div>
</template>