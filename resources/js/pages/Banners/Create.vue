<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import 'dropify/dist/css/dropify.min.css';
import $ from 'jquery';
import 'dropify';
import Label from '@/components/admin/Label.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Hero Banner',
        href: '/banners',
    },
];

const form = useForm({
    url: '',
    image: null as File | null,
});


const submit = () => {
    form.post('/banners/store');
}

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

    <Head title="Hero Banner" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <div class="flex items-center justify-between border-b border-dashed border-gray-200 pb-3">
                <h1 class="text-xl font-medium">Hero Banner Create</h1>
                <LinkButton :label="'Back'" :url="'/banners'" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">
                    <div class="space-y-3 mb-5">
                        <div>
                            <Label :label="'URL'" forr="url" />
                            <input type="text" v-model="form.url" placeholder="Enter your url"
                                class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm" />
                            <span class="text-red-500 text-sm" v-if="form.errors.url">{{ form.errors.url }}</span>
                        </div>
                        <div>
                            <InputLabel id="image" forr="image" :label="'Image'"
                                @input="form.image = $event.target.files[0]" type="file" />
                            <span class="text-red-500 text-sm" v-if="form.errors.image">{{ form.errors.image }}</span>
                        </div>
                    </div>
                    <Button :label="`Submit`" :type="`submit`" />
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