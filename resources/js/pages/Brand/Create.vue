<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { type BreadcrumbItem } from '@/types';
import $ from 'jquery';
import 'dropify/dist/css/dropify.min.css';
import 'dropify';
import Label from '@/components/admin/Label.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Brands List',
        href: '/brands',
    },
];

const form = useForm({
    logo: null as File | null,
});


const image = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.logo = file;
};

const submit = () => {
    form.post(`/brands/store`, {
        forceFormData: true,
    });
};


// DROPIFY
onMounted(() => {
    $('#image').dropify({
        height: 150,
        messages: {
            default: 'Drag and drop a file here or click',
            replace: 'Drag and drop or click to replace',
            remove: 'Remove',
            error: 'Ooops, something wrong happened.'
        }
    });
});


</script>

<template>

    <Head title="Brands Create" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Brands Update</h1>
                <LinkButton label="Back" url="/brands" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <Label :label="`Image`" />
                            <input type="file" id="image" @change="image" class="dropify" />
                            <span class="text-red-500 text-sm" v-if="form.errors.logo">{{ form.errors.logo
                                }}</span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-5 border-t pt-4">
                        <Button label="Store" type="submit" />
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