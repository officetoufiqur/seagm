<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import CKEditor from '@/components/admin/CKEditor.vue';

const form = reactive({
    content: ''
})
</script>

<template>
    <Head title="Create Post" />

    <AppLayout>
        <div class="m-5">
            <h1>Create Post</h1>

            <CKEditor v-model="form.content" />
        </div>
    </AppLayout>
</template>