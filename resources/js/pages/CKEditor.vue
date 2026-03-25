<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { dashboard } from '@/routes'
import { type BreadcrumbItem } from '@/types'
import { onMounted, onBeforeUnmount, reactive } from 'vue'
import { Head } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
]

import { ref, nextTick } from 'vue'

const editorRef = ref<HTMLTextAreaElement | null>(null)

const form = reactive({
    content: ''
})

let editor: any = null

onMounted(async () => {
    const ClassicEditor = (window as any).ClassicEditor

    await nextTick() 

    if (!editorRef.value || !ClassicEditor) {
        console.error('Editor not ready')
        return
    }

    ClassicEditor
        .create(editorRef.value)
        .then((ed: any) => {
            editor = ed

            console.log('Editor working')

            editor.model.document.on('change:data', () => {
                form.content = editor.getData()
            })
        })
        .catch((err: any) => {
            console.error('CKEditor error:', err)
        })
})

onBeforeUnmount(() => {
    if (editor) {
        editor.destroy()
    }
})
</script>

<template>
    <Head title="Create Post" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-5">
            <h1>Create Post</h1>

            <textarea ref="editorRef"></textarea>
        </div>
    </AppLayout>
</template>