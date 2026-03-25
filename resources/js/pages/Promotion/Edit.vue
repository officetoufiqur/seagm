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
        title: 'Promotions List',
        href: '/promotions',
    },
];

interface Item {
    id?: number;
    title: string;
    country: string;
    sales_count: string;
    rating: string;
    image?: string;
}

interface FormItem {
    id?: number;
    title: string;
    country: string;
    sales_count: string;
    rating: string;
    old_image: string | undefined;
    image: File | null;
    preview?: string;
}

interface FormData {
    id: number;
    heading: string;
    title: string;
    subtitle: string;
    icon: string;
    description: string;
    image: File | null;
    items: FormItem[];
}

interface Promotion {
    id: number;
    heading: string;
    title: string;
    subtitle: string;
    icon: string;
    description: string;
    image: string;
    items: Item[];
}

const props = defineProps<{
    promotion: Promotion;
}>();

const form = useForm<FormData>({
    id: props.promotion.id,
    heading: props.promotion.heading || '',
    title: props.promotion.title || '',
    subtitle: props.promotion.subtitle || '',
    icon: props.promotion.icon || '',
    description: props.promotion.description || '',
    image: null as File | null,
    items: props.promotion.items?.length
        ? props.promotion.items.map(item => ({
            id: item.id,
            title: item.title,
            country: item.country,
            sales_count: item.sales_count,
            rating: item.rating,
            old_image: item.image,
            image: null as File | null,
            preview: undefined,
        }))
        : []
});

// Add Item
const addItem = async () => {
    form.items.push({
        id: undefined,
        title: '',
        country: '',
        sales_count: '',
        rating: '',
        old_image: '',
        image: null as File | null,
        preview: undefined,
    });

    await nextTick();
};

// Remove Item
const removeItem = (index: number) => {
    form.items.splice(index, 1);
};

const handleItemImageChange = (e: Event, index: number) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.items[index].image = file;
    form.items[index].preview = URL.createObjectURL(file);
};

// Submit
const submit = () => {
    form.post(`/promotions/${props.promotion.id}`, {
        forceFormData: true
    });
};


// Dropify Init
const initDropify = () => {
    $('.mainImage').dropify({
        defaultFile: props.promotion.image,
        height: 70,
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

    <Head title="Promotion Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Promotion Update</h1>
                <LinkButton label="Back" url="/promotions" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 grid grid-cols-2 gap-3">

                        <InputLabel label="Heading" v-model="form.heading" type="text" />
                        <InputLabel label="Title" v-model="form.title" type="text" />
                        <InputLabel label="Sub Title" v-model="form.subtitle" type="text" />
                        <InputLabel label="Icon" v-model="form.icon" type="text" />

                        <div class="">
                            <label class="text-sm font-medium">Description</label>
                            <textarea v-model="form.description" class="border rounded px-3 py-2 w-full text-sm"
                                rows="3"></textarea>
                        </div>

                        <!-- Main Image -->
                        <div>
                            <label for="" class="text-[#5D5D5D] font-medium text-sm">Main Image</label>
                            <InputLabel class="mainImage" type="file" @input="form.image = $event.target.files[0]" />
                        </div>
                    </div>

                    <!-- Add Item -->
                    <button type="button" class="bg-blue-500 text-white px-4 py-1.5 rounded mb-4 text-sm ml-auto block"
                        @click="addItem">
                        + Add Card Item
                    </button>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border">

                            <thead class="bg-gray-100 text-sm">
                                <tr>
                                    <th class="p-2 border">Title</th>
                                    <th class="p-2 border">Country</th>
                                    <th class="p-2 border">Sales</th>
                                    <th class="p-2 border">Rating</th>
                                    <th class="p-2 border">Image</th>
                                    <th class="p-2 border">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(item, index) in form.items" :key="index">

                                    <td class="p-2 border">
                                        <InputLabel v-model="item.title" type="text" />
                                    </td>

                                    <td class="p-2 border">
                                        <InputLabel v-model="item.country" type="text" />
                                    </td>

                                    <td class="p-2 border">
                                        <InputLabel v-model="item.sales_count" type="text" />
                                    </td>

                                    <td class="p-2 border">
                                        <InputLabel v-model="item.rating" type="text" />
                                    </td>

                                    <td class="p-2 border">
                                        <div class="flex items-center gap-2">
                                            <img v-if="item.preview || item.old_image" :src="item.preview || item.old_image"
                                            class="w-12 h-10 rounded" />
                                            <input type="file" @change="handleItemImageChange($event, index)" class="cursor-pointer" />
                                        </div>
                                    </td>

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