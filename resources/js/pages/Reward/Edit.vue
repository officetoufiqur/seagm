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
import Textarea from '@/components/admin/Textarea.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Star abouts List',
        href: '/star-abouts',
    },
];

type About = {
    id: number
    section: string
    title: string
    subtitle: string
    description: string
    image: null | string
}

const props = defineProps<{
    about: About;
}>()

const form = useForm({
    section: props.about.section,
    title: props.about.title,
    subtitle: props.about.subtitle,
    description: props.about.description,
    image: null as File | null,
});


const submit = () => {
    form.post('/star-abouts/update/' + props.about.id, {
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
        defaultFile: props.about.image,
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

    <Head title="Star abouts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Star abouts Update</h1>
                <LinkButton label="Back" url="/star-abouts" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <Label label="Section" />
                                <select v-model="form.section"
                                    class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm">
                                    <option value="">Select Section</option>
                                    <option value="about">About</option>
                                    <option value="cards">Cards</option>
                                </select>
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

                            <div>
                                <Textarea v-model="form.description" :rows="5" :label="`Description`" />
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
