<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
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
        title: 'Carousels List',
        href: '/carousels',
    },
];

type Carousel = {
    id: number
    image: null | string
}

const props = defineProps<{
    carousel: Carousel;
}>()

const form = useForm({
    image: null as File | null,
});


const submit = () => {
    form.post('/carousels/update/' + props.carousel.id, {
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
        defaultFile: props.carousel.image,
        height: 110,
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

    <Head title="Carousels" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Carousels Update</h1>
                <LinkButton label="Back" url="/carousels" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
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
