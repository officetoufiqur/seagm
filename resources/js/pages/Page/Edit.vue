<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Page List',
        href: '/home-page',
    },
];


interface Page {
    id: number;
    title: string
    subtitle: string
    slug: string
}

const props = defineProps<{
    page: Page;
}>();


const form = useForm({
    id: props.page.id,
    title: props.page.title || '',
    subtitle: props.page.subtitle || '',
    slug: props.page.slug || '',
});


const submit = () => {
    form.post(`/home-page/update/${props.page.id}`, {
        forceFormData: true,
    });
};


</script>

<template>

    <Head title="Page Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Page Update</h1>
                <LinkButton label="Back" url="/home-page" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel label="Title" v-model="form.title" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.title">{{ form.errors.title }}</span>
                        </div>
                        <div>
                            <InputLabel label="Sub Title" v-model="form.subtitle" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.subtitle">{{ form.errors.subtitle }}</span>
                        </div>
                        <div>
                            <InputLabel label="Slug" v-model="form.slug" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.slug">{{ form.errors.slug }}</span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-5 border-t pt-4">
                        <Button label="Update" type="submit" />
                    </div>

                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.dropify-wrapper .dropify-preview .dropify-render img {
    width: 100% !important;
    height: auto !important;
    object-fit: contain;
}

.dropify-wrapper .dropify-message p {
    font-size: 16px;
    text-align: center;
}
</style>