<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, nextTick } from 'vue';
import 'dropify/dist/css/dropify.min.css';
import $ from 'jquery';
import 'dropify';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'About Us List',
        href: '/about-us',
    },
];

interface About {
    id: number
    title: string
    description: string
    page_view: number
    unique_visitors: number
    registered_users: number
    active_users: number
    subscribers: number
    image: string | null
}

const props = defineProps<{
    aboutUs: About;
}>();

const form = useForm({
    id: props.aboutUs.id,
    title: props.aboutUs.title || '',
    description: props.aboutUs.description || '',
    page_view: props.aboutUs.page_view || 0,
    unique_visitors: props.aboutUs.unique_visitors || 0,
    registered_users: props.aboutUs.registered_users || 0,
    active_users: props.aboutUs.active_users || 0,
    subscribers: props.aboutUs.subscribers || 0,
    image: null as File | null,
});

// Image handler
const image = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.image = file;
};

// Submit
const submit = () => {
    form.post(`/about-us/update/${props.aboutUs.id}`, {
        forceFormData: true
    });
};

// Dropify Init
const initDropify = () => {
    $('.mainImage').dropify({
        defaultFile: props.aboutUs.image,
        height: 150,
        messages: {
            default: 'Drag and drop or click',
            replace: 'Replace',
            remove: 'Remove',
            error: 'Error'
        }
    });
};

onMounted(async () => {
    await nextTick();
    initDropify();
});
</script>

<template>

    <Head title="About Us Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">About Us Update</h1>
                <LinkButton label="Back" url="/about-us" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 grid grid-cols-2 gap-4">

                        <!-- Title -->
                        <InputLabel label="Title" v-model="form.title" type="text" />

                        <!-- Stats -->
                        <InputLabel label="Page View" v-model="form.page_view" type="number" />
                        <InputLabel label="Unique Visitors" v-model="form.unique_visitors" type="number" />

                        <InputLabel label="Registered Users" v-model="form.registered_users" type="number" />
                        <InputLabel label="Active Users" v-model="form.active_users" type="number" />

                        <InputLabel label="Subscribers" v-model="form.subscribers" type="number" />

                        <div class="grid grid-cols-2 col-span-2 gap-4">
                            <!-- Description -->
                            <div class="h-30">
                                <label class="text-sm font-medium">Description</label>
                                <QuillEditor v-model:content="form.description" contentType="html" theme="snow"
                                    class="mt-2" />
                            </div>
                            <!-- Image -->
                            <div class="">
                                <label class="text-[#5D5D5D] font-medium text-sm">Main Image</label>
                                <input class="mainImage mt-2" type="file" @change="image" />

                                <span class="text-red-500 text-sm" v-if="form.errors.image">
                                    {{ form.errors.image }}
                                </span>
                            </div>
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