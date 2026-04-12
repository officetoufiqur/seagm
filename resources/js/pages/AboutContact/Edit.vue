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
        title: 'About contact',
        href: '/about-contact',
    },
];

interface Contact {
    id: number
    title: string
    subtitle: string
    image: string | null
}

const props = defineProps<{
    contact: Contact;
}>();

const form = useForm({
    id: props.contact.id,
    title: props.contact.title || '',
    subtitle: props.contact.subtitle || '',
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
    form.post(`/about-contact/update/${props.contact.id}`, {
        forceFormData: true
    });
};

// Dropify Init
const initDropify = () => {
    $('.mainImage').dropify({
        defaultFile: props.contact.image,
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

    <Head title="About Contact Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">About Contact Update</h1>
                <LinkButton label="Back" url="/about-contact" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 grid grid-cols-2 gap-4">

                        <!-- Title -->
                        <InputLabel label="Title" v-model="form.title" type="text" />

                        <!-- Sub Title -->
                        <InputLabel label="Sub Title" v-model="form.subtitle" type="text" />

                        <div class="grid grid-cols-2 col-span-2 gap-4">
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