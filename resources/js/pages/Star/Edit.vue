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
import { Trash2Icon } from 'lucide-vue-next';
import Label from '@/components/admin/Label.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Star banner List',
        href: '/star-banner',
    },
];

interface Item {
    id?: number;
    title: string;
    subtitle: string;
    image: File | string | null;
    preview?: string;
}

interface Star {
    id: number;
    heading: string
    title: string
    subtitle: string
    image?: string | null;
    items: Item[];
}

const props = defineProps<{
    star: Star;
}>();


const form = useForm({
    id: props.star.id,
    heading: props.star.heading || '',
    title: props.star.title || '',
    subtitle: props.star.subtitle || '',
    image: null as File | null,

    items: props.star.items?.length
        ? props.star.items.map(item => ({
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
    form.post(`/star-banner/update`, {
        forceFormData: true,
    });
};


// DROPIFY
const initDropify = () => {
    $('.mainImage').dropify({
        defaultFile: props.star.image,
        height: 100,
    });
};

onMounted(async () => {
    await nextTick();
    initDropify();
});
</script>

<template>

    <Head title="Star banner Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Star banner Update</h1>
                <LinkButton label="Back" url="/star-banner" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="heading" label="Heading" v-model="form.heading" />
                            <span class="text-red-500 text-sm" v-if="form.errors.heading">
                                {{ form.errors.heading }}
                            </span>
                        </div>
                        <div>
                            <InputLabel for="title" label="Title" v-model="form.title" />
                            <span class="text-red-500 text-sm" v-if="form.errors.title">
                                {{ form.errors.title }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <InputLabel for="subtitle" label="Sub Title" v-model="form.subtitle" />
                        <span class="text-red-500 text-sm" v-if="form.errors.subtitle">
                            {{ form.errors.subtitle }}
                        </span>
                    </div>
                    <div class="mt-2">
                        <Label label="Image" />
                        <input class="mainImage mt-2" type="file" @change="image" />
                    </div>

                    <!-- Add Item -->
                    <button type="button"
                        class="bg-blue-500 text-white px-4 py-1.5 rounded mb-4 text-sm ml-auto block mt-5"
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
                                    <th class="p-2 border w-50">Image</th>
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
                                            <Trash2Icon class="w-4 h-4" />
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