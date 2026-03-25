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

const form = useForm({
    heading: '',
    title: '',
    subtitle: '',
    icon: '',
    description: '',
    image: null as File | null,
    items: [
        {
            title: '',
            country: '',
            sales_count: '',
            rating: '',
            image: null as File | null,
        }
    ]
});

// Add Item
const addItem = async () => {
    form.items.push({
        title: '',
        country: '',
        sales_count: '',
        rating: '',
        image: null,
    });

    await nextTick();
    initDropify();
};

// Remove Item
const removeItem = (index: number) => {
    form.items.splice(index, 1);
};

// Submit
const submit = () => {
    form.post('/promotions/store', {
        forceFormData: true
    });
};

// Init Dropify
const initDropify = () => {
    $('.dropify').dropify({
        height: 70,
        messages: {
            default: 'Drag and drop or click',
            replace: 'Replace',
            remove: 'Remove',
            error: 'Error'
        }
    });
};

onMounted(() => {
    initDropify();
});
</script>

<template>

    <Head title="Promotion" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Promotion Create</h1>
                <LinkButton label="Back" url="/promotions" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <InputLabel label="Heading" v-model="form.heading" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.heading">
                                    {{ form.errors.heading }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Title" v-model="form.title" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.title">
                                    {{ form.errors.title }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Sub Title" v-model="form.subtitle" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.subtitle">
                                    {{ form.errors.subtitle }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Icon" v-model="form.icon" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.icon">
                                    {{ form.errors.icon }}
                                </span>
                            </div>
                            <div>
                                <label for="desc" class="text-[#5D5D5D] font-medium text-sm">Description</label>
                                <textarea name="" id="desc" v-model="form.description"
                                    class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm"
                                    rows="3"></textarea>
                                <span class="text-red-500 text-sm" v-if="form.errors.description">
                                    {{ form.errors.description }}
                                </span>
                            </div>

                            <div>
                                <label for="" class="text-[#5D5D5D] font-medium text-sm">Main Image</label>
                                <InputLabel class="dropify" type="file" @input="form.image = $event.target.files[0]" />
                            </div>
                        </div>
                    </div>

                    <!-- Add Button -->
                    <button type="button" class="bg-blue-500 text-white px-4 py-1.5 rounded mb-4 text-sm ml-auto block cursor-pointer"
                        @click="addItem">
                        + Add Card Item
                    </button>

                    <!-- Dynamic Items -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded">

                            <!-- Table Head -->
                            <thead class="bg-gray-100 text-left text-sm">
                                <tr>
                                    <th class="p-2 border">Title</th>
                                    <th class="p-2 border">Country</th>
                                    <th class="p-2 border">Sales</th>
                                    <th class="p-2 border">Rating</th>
                                    <th class="p-2 border">Image</th>
                                    <th class="p-2 border text-center">Action</th>
                                </tr>
                            </thead>

                            <!-- Table Body -->
                            <tbody>
                                <tr v-for="(item, index) in form.items" :key="index" class="text-sm">

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
                                        <InputLabel type="file" @input="item.image = $event.target.files[0]" />
                                    </td>

                                    <td class="p-2 border text-center">
                                        <button type="button" class="bg-red-500 text-white px-3 py-1 rounded text-xs cursor-pointer"
                                            @click="removeItem(index)">
                                            Remove
                                        </button>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit -->
                    <div class="mt-5 border-t pt-4 border-dashed">
                        <Button label="Submit" type="submit" />
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