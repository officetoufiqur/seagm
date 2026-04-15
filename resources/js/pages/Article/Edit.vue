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
import Textarea from '@/components/admin/Textarea.vue';

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

interface ArticleStep {
    id?: number;
    description: string;
    old_image: string | undefined;
    image: string | null;
    order?: number;
    preview?: string;
}

interface Article {
    id: number;
    title: string;
    content: string;
    sub_category_id: number;
    steps?: ArticleStep[];
}

const props = defineProps<{
    sub_category: Category[];
    article: Article;
    flash: {
        message?: string;
    };
}>()

const form = useForm({
    title: props.article.title,
    content: props.article.content,
    sub_category_id: props.article.sub_category_id,
    items: props.article.steps?.length 
    ? props.article.steps.map(item => ({
        description: item.description,
        old_image: item.image ?? undefined,
        image: null as File | null,
        preview: undefined as string | undefined,
    })) 
    : []
});

const addItem = async () => {
    form.items.push({
        description: '',
        image: null as File | null,
        old_image: undefined,
        preview: undefined as string | undefined,
    });

    await nextTick();
    initDropify();
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

const submit = () => {
    form.post('/articles/update/' + props.article.id, {
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
                                <Textarea label="Content" v-model="form.content"
                                    placeholder="Write article content..." :rows="5" />
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
                                    <th class="p-2 border">Image (Optional)</th>
                                    <th class="p-2 border text-center">Action</th>
                                </tr>
                            </thead>

                            <!-- Table Body -->
                            <tbody>
                                <tr v-for="(item, index) in form.items" :key="index" class="text-sm">

                                    <td class="p-2 border">
                                        <InputLabel v-model="item.description" type="text" :placeholder="`Step 1: Type here`" />
                                    </td>

                                    <td class="p-2 border">
                                        <div class="flex items-center gap-2">
                                            <img v-if="item.preview || item.old_image"
                                                :src="item.preview || item.old_image" class="w-12 h-10 rounded" />
                                            <input type="file" @change="handleItemImageChange($event, index)"
                                                class="cursor-pointer" />
                                        </div>
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
