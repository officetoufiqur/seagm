<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, nextTick } from 'vue';
import { type BreadcrumbItem } from '@/types';
import $ from 'jquery';
import 'dropify/dist/css/dropify.min.css';
import 'dropify';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Department List',
        href: '/departments',
    },
];

interface Item {
    id?: number;
    title: string;
    subtitle: string;
    image: File | string | null;
    preview?: string;
}

interface Department {
    id: number;
    description: string;
    image?: string | null;
    items: Item[];
}

const props = defineProps<{
    department: Department;
}>();


const form = useForm({
    id: props.department.id,
    description: props.department.description || '',
    image: null as File | null,

    items: props.department.items?.length
        ? props.department.items.map(item => ({
            id: item.id,
            title: item.title,
            subtitle: item.subtitle,
            image: null as File | string | null,
            preview: typeof item.image === 'string' ? item.image : undefined,
        }))
        : [],
});


const addItem = async () => {
    form.items.push({
        id: undefined,
        title: '',
        subtitle: '',
        image: null,
        preview: '',
    });

    await nextTick();
};


const removeItem = (index: number) => {
    form.items.splice(index, 1);
};


const handleItemImageChange = (e: Event, index: number) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.items[index].image = file;
    form.items[index].preview = URL.createObjectURL(file);
};

const image = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.image = file;
};

const submit = () => {
    form.post(`/departments/update/${props.department.id}`, {
        forceFormData: true,
    });
};


// DROPIFY
const initDropify = () => {
    $('.mainImage').dropify({
        defaultFile: props.department.image,
        height: 150,
    });
};

onMounted(async () => {
    await nextTick();
    initDropify();
});
</script>

<template>

    <Head title="Department Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Department Update</h1>
                <LinkButton label="Back" url="/departments" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="h-30">
                            <label class="text-sm font-medium">Description</label>
                            <QuillEditor v-model:content="form.description" contentType="html" theme="snow"
                                class="mt-4" />
                        </div>
                        <div class="">
                            <label class="text-sm font-medium">Main Image</label>
                            <input class="mainImage mt-2" type="file" @change="image" />
                        </div>
                    </div>

                    <!-- Add Item -->
                    <button type="button" class="bg-blue-500 text-white px-4 py-1.5 rounded mb-4 text-sm ml-auto block mt-5"
                        @click="addItem">
                        + Add Card Item
                    </button>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border">
                            <thead class="bg-gray-100 text-sm">
                                <tr>
                                    <th class="p-2 border">Title</th>
                                    <th class="p-2 border">Subtitle</th>
                                    <th class="p-2 border">Image</th>
                                    <th class="p-2 border">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(item, index) in form.items" :key="index">

                                    <!-- Title -->
                                    <td class="p-2 border">
                                        <InputLabel v-model="item.title" type="text" />
                                    </td>

                                    <!-- Subtitle -->
                                    <td class="p-2 border">
                                        <InputLabel v-model="item.subtitle" type="text" />
                                    </td>

                                    <!-- Image -->
                                    <td class="p-2 border">
                                        <div class="flex items-center gap-2">
                                            <img v-if="item.preview" :src="item.preview" class="w-12 h-10 rounded" />
                                            <input type="file" @change="handleItemImageChange($event, index)" />
                                        </div>
                                    </td>

                                    <!-- Action -->
                                    <td class="p-2 border text-center">
                                        <button type="button" class="bg-red-500 text-white px-2 py-1 text-xs rounded"
                                            @click="removeItem(index)">
                                            Remove
                                        </button>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
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