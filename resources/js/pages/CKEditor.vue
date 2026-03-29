<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { dashboard } from '@/routes'
import { type BreadcrumbItem } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
]

const form = useForm({
  title: '',
  content: '',
})

const content = ref('')

const submit = () => {
  form.content = content.value
  form.post('/posts')
}
</script>

<template>
    <Head title="Create Post" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-5">
              <div>
    <input v-model="form.title" placeholder="Title" />

    <QuillEditor
      v-model:content="content"
      contentType="html"
      theme="snow"
      class="mt-4"
    />

    <button @click="submit" class="mt-4">Save</button>
  </div>
        </div>
    </AppLayout>
</template>