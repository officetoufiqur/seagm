<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import Multiselect from 'vue-multiselect'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import { onMounted } from 'vue';
import 'dropify/dist/css/dropify.min.css';
import $ from 'jquery';
import 'dropify';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News List',
        href: '/news',
    },
];

const props = defineProps<{
    categories: {
        id: number;
        name: string;
    }[];
}>()

const form = useForm({
    category_id: '',
    tags: [],
    title: '',
    content: '',
    image: null as File | null,
});

const mainImage = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.image = file;
};

const submit = () => {
    form.post('/news/store', {
        onSuccess: () => {
            form.reset();
        }
    });
};


const mainDropify = () => {
    $('.mainImage').dropify({
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
    mainDropify();
});

</script>

<template>

    <Head title="News" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">News Create</h1>
                <LinkButton label="Back" url="/news" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="category_id" class="text-[#5D5D5D] font-medium text-sm">Select
                                    Category</label>
                                <select name="category_id" id="category_id" v-model="form.category_id"
                                    class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm">
                                    <option value="">Select Category</option>
                                    <option v-for="category in props.categories" :value="category.id"
                                        :key="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <span class="text-red-500 text-sm" v-if="form.errors.category_id">
                                    {{ form.errors.category_id }}
                                </span>
                            </div>

                            <div>
                                <label for="category_id" class="text-[#5D5D5D] font-medium text-sm">Select
                                    Tags</label>
                                <Multiselect v-model="form.tags" :options="props.categories" :multiple="true"
                                    label="name" track-by="id" placeholder="Select categories" />
                                <span class="text-red-500 text-sm" v-if="form.errors.category_id">
                                    {{ form.errors.category_id }}
                                </span>
                            </div>

                            <div>
                                <InputLabel label="Title" v-model="form.title" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.title">
                                    {{ form.errors.title }}
                                </span>
                            </div>

                            <div class="h-30">
                                <label for="content" class="text-[#5D5D5D] font-medium text-sm">Terms &
                                    Conditions</label>
                                <QuillEditor v-model:content="form.content" contentType="html" theme="snow"
                                    class="mt-4" />
                                <span class="text-red-500 text-sm" v-if="form.errors.content">
                                    {{ form.errors.content }}
                                </span>
                            </div>
                            <div>
                                <label for="image" class="text-[#5D5D5D] font-medium text-sm">Image</label>
                                <input type="file" id="image" class="mainImage" @change="mainImage" />
                                <span class="text-red-500 text-sm" v-if="form.errors.image">
                                    {{ form.errors.image }}
                                </span>
                            </div>
                        </div>
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
