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
        title: 'Articles List',
        href: '/articles',
    },
];

interface Category {
    id: number;
    name: string;
}

defineProps<{
    sub_category: Category[];
    flash: {
        message?: string;
    };
}>()

const form = useForm({
    title: '',
    content: '',
    sub_category_id: '',
    items: [
        {
            description: '',
            image: null as File | null,
        }
    ]
});

const addItem = async () => {
    form.items.push({
        description: '',
        image: null as File | null,
    });

    await nextTick();
    initDropify();
};

// Remove Item
const removeItem = (index: number) => {
    form.items.splice(index, 1);
};


const submit = () => {
    form.post('/articles/store', {
        onSuccess: () => {
            form.reset();
        }
    });
};

const initDropify = () => {
    $('.dropify').dropify({
        height: 150,
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

    <Head title="Articles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Articles Create</h1>
                <LinkButton label="Back" url="/articles" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[#5D5D5D] font-medium text-sm">Category</label>
                                <select class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm"
                                    v-model="form.sub_category_id">
                                    <option value="">Select Category</option>
                                    <option v-for="item in sub_category" :value="item.id" :key="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <span class="text-red-500 text-sm" v-if="form.errors.sub_category_id">
                                    {{ form.errors.sub_category_id }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Title" v-model="form.title" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.title">
                                    {{ form.errors.title }}
                                </span>
                            </div>

                            <div>
                                <QuillEditor v-model:content="form.content" contentType="html" theme="snow"
                                    class="mt-4" />
                                <span class="text-red-500 text-sm" v-if="form.errors.content">
                                    {{ form.errors.content }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Add Button -->
                    <button type="button"
                        class="bg-blue-500 text-white px-4 py-1.5 rounded mb-4 text-sm ml-auto block cursor-pointer"
                        @click="addItem">
                        + Add Step
                    </button>

                    <!-- Dynamic Items -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded">

                            <!-- Table Head -->
                            <thead class="bg-gray-100 text-left text-sm">
                                <tr>
                                    <th class="p-2 border">Step Description</th>
                                    <th class="p-2 border w-70">Image (Optional)</th>
                                    <th class="p-2 border text-center w-30">Action</th>
                                </tr>
                            </thead>

                            <!-- Table Body -->
                            <tbody>
                                <tr v-for="(item, index) in form.items" :key="index" class="text-sm">

                                    <td class="p-2 border">
                                        <InputLabel v-model="item.description" type="text"
                                            :placeholder="`Step 1: Type here`" />
                                    </td>

                                    <td class="p-2 border">
                                        <InputLabel type="file" @input="item.image = $event.target.files[0]" />
                                    </td>

                                    <td class="p-2 border text-center">
                                        <button type="button"
                                            class="bg-red-500 text-white px-3 py-1 rounded text-xs cursor-pointer"
                                            @click="removeItem(index)">
                                            Remove
                                        </button>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit -->
                    <div class="border-t pt-4 border-dashed">
                        <Button label="Submit" type="submit" />
                    </div>

                </form>
            </div>

        </div>
    </AppLayout>
</template>
