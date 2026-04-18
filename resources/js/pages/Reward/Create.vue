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
        title: 'Star rewards List',
        href: '/star-rewards',
    },
];

defineProps<{
    categories: {
        id: number;
        name: string;
    }[];
}>()

const form = useForm({
    star_category_id: '',
    title: '',
    subtitle: '',
    coupon: '',
    reward: '',
    description: '',
    image: null as File | null,
});


const submit = () => {
    form.post('/star-rewards/store', {
        onSuccess: () => {
            form.reset();
        }
    });
};

const image = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.image = file;
};

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

    <Head title="Star rewards" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Star rewards Create</h1>
                <LinkButton label="Back" url="/star-rewards" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <Label label="Star Category" />
                                <select v-model="form.star_category_id"
                                    class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm">
                                    <option value="">Select Section</option>
                                    <option v-for="category in categories" :value="category.id" :key="category.id">{{
                                        category.name }}</option>
                                </select>
                                <span class="text-red-500 text-sm" v-if="form.errors.star_category_id">
                                    {{ form.errors.star_category_id }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Title" v-model="form.title" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.title">
                                    {{ form.errors.title }}
                                </span>
                            </div>

                            <div class="col-span-2">
                                <InputLabel label="Subtitle" v-model="form.subtitle" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.subtitle">
                                    {{ form.errors.subtitle }}
                                </span>
                            </div>

                            <div class="">
                                <InputLabel label="Coupon" v-model="form.coupon" type="number" />
                                <span class="text-red-500 text-sm" v-if="form.errors.coupon">
                                    {{ form.errors.coupon }}
                                </span>
                            </div>

                            <div class="">
                                <InputLabel label="Reward" v-model="form.reward" type="number" />
                                <span class="text-red-500 text-sm" v-if="form.errors.reward">
                                    {{ form.errors.reward }}
                                </span>
                            </div>

                            <div class="h-30">
                                <label class="text-sm font-medium">Description</label>
                                <QuillEditor v-model:content="form.description" contentType="html" theme="snow"
                                    class="mt-4" />
                                <span class="text-red-500 text-sm" v-if="form.errors.description">
                                    {{ form.errors.description }}
                                </span>
                            </div>

                            <div>
                                <Label :label="'Image'" />
                                <input type="file" id="image" class="dropify" @change="image">
                                <span class="text-red-500 text-sm" v-if="form.errors.image">{{ form.errors.image
                                    }}</span>
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
