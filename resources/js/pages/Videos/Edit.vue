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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News videos List',
        href: '/news-videos',
    },
];

const props = defineProps<{
    video: {
        id: number;
        title: string;
        video_url: string;
        thumbnail: string;
    };
}>();

const form = useForm({
    title: props.video.title,
    video_url: props.video.video_url,
    thumbnail: null as File | null
});


const mainImage = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.thumbnail = file;
};

const submit = () => {
    form.post(`/news-videos/update/${props.video.id}`, {
        forceFormData: true
    });
};

// Init Dropify
const initDropify = () => {
    $('.dropify').dropify({
        defaultFile: props.video.thumbnail,
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

    <Head title="News videos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">News videos Update</h1>
                <LinkButton label="Back" url="/news-videos" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <InputLabel label="Title" v-model="form.title" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.title">
                                    {{ form.errors.title }}
                                </span>
                            </div>
                            <div class="">
                                <InputLabel label="Video URL" v-model="form.video_url" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.video_url">
                                    {{ form.errors.video_url }}
                                </span>
                            </div>

                            <div class="col-span-2">
                                <div>
                                    <label for="" class="text-[#5D5D5D] font-medium text-sm">Thumbnail Image</label>
                                    <input type="file" class="dropify" @change="mainImage" />
                                    <span class="text-red-500 text-sm" v-if="form.errors.thumbnail">
                                        {{ form.errors.thumbnail }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-5 border-t pt-4 border-dashed">
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