<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, nextTick, watch } from 'vue';
import { type BreadcrumbItem } from '@/types';
import $ from 'jquery';
import 'dropify/dist/css/dropify.min.css';
import 'dropify';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Platform List',
        href: '/platform',
    },
];

interface Item {
    id?: number;
    title: string;
    icon: string;
}

interface Image {
    id?: number;
    image: File | string | null;
    preview?: string;
}

interface Platform {
    id: number;
    section: string;
    title: string;
    items: Item[];
    images: Image[];
}

const props = defineProps<{
    platform: Platform;
}>();


const form = useForm({
    id: props.platform.id,
    section: props.platform.section || '',
    title: props.platform.title || '',

    items: props.platform.items?.length
        ? props.platform.items.map(item => ({
            id: item.id,
            title: item.title,
            icon: item.icon
        }))
        : [],

    images: props.platform.images?.length
        ? props.platform.images.map(item => ({
            id: item.id,
            image: null as File | string | null,
            preview: typeof item.image === 'string' ? item.image : undefined,
        }))
        : [],

});


const addItem = async () => {
    form.items.push({
        id: undefined,
        title: '',
        icon: ''
    });
};


const removeItem = (index: number) => {
    form.items.splice(index, 1);
};

const addImage = async () => {
    form.images.push({
        id: undefined,
        image: null,
        preview: '',
    });

    await initDropify();
};


const removeImage = (index: number) => {
    form.images.splice(index, 1);
};


const handleItemImageChange = (e: Event, index: number) => {
    const file = (e.target as HTMLInputElement).files?.[0];
    if (!file) return;

    form.images[index].image = file;
    form.images[index].preview = URL.createObjectURL(file);
};

const submit = () => {
    form.post(`/platform/update/${props.platform.id}`, {
        forceFormData: true,
    });
};


// DROPIFY
const initDropify = async () => {
    await nextTick();

   $('.mainImage').each(function (this: HTMLElement) {
    const el = $(this);

    if (!el.hasClass('dropify-initialized')) {
        el.dropify({
            height: 120,
        });
    }
});
};

onMounted(async () => {
    await initDropify();
});

watch(() => form.images.length, async () => {
    await initDropify();
});
</script>

<template>

    <Head title="Platform Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Platform Update</h1>
                <LinkButton label="Back" url="/platform" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel label="Section" v-model="form.section" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.section">{{ form.errors.section
                                }}</span>
                        </div>
                        <div>
                            <InputLabel label="Title" v-model="form.title" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.title">{{ form.errors.title }}</span>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div>
                        <div class="flex justify-between items-center mb-3 mt-3">
                            <h2 class="font-medium text-gray-700">Card Items</h2>
                            <button type="button" @click="addItem"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-sm cursor-pointer">
                                + Add Item
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div v-for="(item, index) in form.items" :key="index"
                                class="flex gap-3 items-center bg-gray-50 p-3 rounded-lg border">

                                <div class="w-full">
                                    <InputLabel v-model="item.title" type="text" :placeholder="`Enter items title`" />
                                </div>
                                <div class="w-full">
                                    <InputLabel v-model="item.icon" type="text" :placeholder="`Enter items svg icon`" />
                                </div>

                                <button type="button" @click="removeItem(index)"
                                    class="text-red-500 hover:text-red-700 text-sm">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Images Section -->
                    <div>
                        <div class="flex justify-between items-center my-3">
                            <h2 class="font-medium text-gray-700">Carousel Images</h2>
                            <button type="button" @click="addImage"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-sm cursor-pointer">
                                + Add Image
                            </button>
                        </div>

                        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div v-for="(item, index) in form.images" :key="index"
                                class="border rounded-xl p-3 bg-gray-50">

                                <input 
                                type="file" 
                                @change="handleItemImageChange($event, index)" 
                                class="text-sm mainImage"
                                :data-default-file="item.preview" />

                                <button type="button" @click="removeImage(index)"
                                    class="mt-2 text-red-500 text-sm hover:underline">
                                    Remove
                                </button>
                            </div>
                        </div>
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