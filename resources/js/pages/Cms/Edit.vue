<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import Label from '@/components/admin/Label.vue';
import { onMounted } from 'vue';
import $ from 'jquery';
import 'dropify/dist/css/dropify.min.css';
import 'dropify';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'About CMS List',
        href: '/about-cms',
    },
];

interface Page {
    id: number;
    slug: string;
}

const props = defineProps<{
    page: Page[];
    cms: {
        id: number;
        page_id: number;
        section: string;
        title: string;
        description: string;
        icon: string;
        image: null | string | File;
    };
}>()

const form = useForm({
    page_id: props.cms.page_id,
    section: props.cms.section,
    title: props.cms.title,
    description: props.cms.description,
    icon: props.cms.icon,
    image: null as File | null,
});

const image = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.image = file;
};


const submit = () => {
    form.post('/about-cms/update/' + props.cms.id, {
        forceFormData: true,
    });
};

onMounted(() => {
    $('#image').dropify({
        height: 105,
        defaultFile: props.cms.image,
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

    <Head title="About CMS Update" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">About CMS Update</h1>
                <LinkButton label="Back" url="/about-cms" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit" class="space-y-3">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Page Select -->
                        <div>
                            <Label label="Page" />
                            <select v-model="form.page_id"
                                class="w-full border border-gray-300 text-sm rounded-lg p-2 mt-1">
                                <option value="">Select Page</option>
                                <option v-for="p in page" :key="p.id" :value="p.id">
                                    {{ p.slug }}
                                </option>
                            </select>

                            <span class="text-red-500 text-sm" v-if="form.errors.page_id">
                                {{ form.errors.page_id }}
                            </span>
                        </div>

                        <!-- Section -->
                        <!-- <div>
                            <InputLabel label="Section" v-model="form.section" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.section">
                                {{ form.errors.section }}
                            </span>
                        </div> -->

                        <!-- Title -->
                        <div>
                            <InputLabel label="Title" v-model="form.title" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.title">
                                {{ form.errors.title }}
                            </span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <Label label="Description" />
                        <textarea v-model="form.description" rows="4"
                            class="w-full border border-gray-300 text-sm rounded-lg p-2.5"></textarea>

                        <span class="text-red-500 text-sm" v-if="form.errors.description">
                            {{ form.errors.description }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Icon -->
                        <div>
                            <Label label="Icon (SVG, Optional)" />
                            <textarea v-model="form.icon" rows="5"
                                class="w-full border border-gray-300 text-sm rounded-lg p-2.5"></textarea>

                            <span class="text-red-500 text-sm" v-if="form.errors.icon">
                                {{ form.errors.icon }}
                            </span>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <Label label="Image (Optional)" />
                            <input type="file" id="image" @change="image" class="dropify" />

                            <span class="text-red-500 text-sm" v-if="form.errors.image">
                                {{ form.errors.image }}
                            </span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-5 border-t pt-4">
                        <Button label="Create" type="submit" />
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