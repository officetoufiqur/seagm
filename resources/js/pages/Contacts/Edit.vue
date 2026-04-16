<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, nextTick } from 'vue';
import { type BreadcrumbItem } from '@/types';
import $ from 'jquery';
import 'dropify/dist/css/dropify.min.css';
import 'dropify';
import Label from '@/components/admin/Label.vue';
import Textarea from '@/components/admin/Textarea.vue';
import { XIcon } from 'lucide-vue-next';
import FlashMessage from '@/components/admin/FlashMessage.vue';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Contacts List', href: '/contacts' },
];

// Interfaces
interface Contacts {
    id: number;
    heading: string;
    title: string;
    subtitle: string;
    address_title: string;
    map: string;
    image: string | null;
}

interface NumberItem {
    id: number;
    title: string;
    subtitle: string;
    numbers: string[];
}

// Props
const props = defineProps<{
    contacts: Contacts;
    number: NumberItem[];
    flash: {
        message?: string;
    };
}>();

// ================= MAIN FORM =================
const form = useForm({
    id: props.contacts.id,
    heading: props.contacts.heading ?? '',
    title: props.contacts.title ?? '',
    subtitle: props.contacts.subtitle ?? '',
    address_title: props.contacts.address_title ?? '',
    map: props.contacts.map ?? '',
    image: null as File | null,
});

// ================= NUMBER FORM =================
const numberForm = useForm({
    numbers: props.number.map(item => ({
        id: item.id,
        title: item.title,
        subtitle: item.subtitle,
        numbers: item.numbers,
    })),
});

// Submit Main Form
const submitMain = () => {
    form.post(`/contacts/update`, {
        forceFormData: true,
    });
};

// Submit Numbers Form
const submitNumbers = () => {
    numberForm.post(`/contacts/numbers/update`, {
        preserveScroll: true,
    });
};

// Add number field
const addNumber = (index: number) => {
    numberForm.numbers[index].numbers.push('');
};

// Remove number
const removeNumber = (i: number, j: number) => {
    numberForm.numbers[i].numbers.splice(j, 1);
};

// Handle Image
const handleImageChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.image = target.files[0];
    }
};

// Dropify
const initDropify = () => {
    $('.mainImage').dropify({
        defaultFile: props.contacts.image ?? '',
        height: 110,
    });
};

onMounted(async () => {
    await nextTick();
    initDropify();
});

</script>

<template>

    <Head title="Contacts Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="bg-white m-7 p-5 rounded">

            <!-- ================= BASIC INFO ================= -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Basic Information</h1>
                <LinkButton label="Back" url="/contacts" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submitMain">

                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <InputLabel label="Heading" v-model="form.heading" />
                            <span class="text-red-500 text-sm">{{ form.errors.heading }}</span>
                        </div>

                        <div>
                            <InputLabel label="Title" v-model="form.title" />
                            <span class="text-red-500 text-sm">{{ form.errors.title }}</span>
                        </div>
                        <div>
                            <InputLabel label="Sub Title" v-model="form.subtitle" />
                            <span class="text-red-500 text-sm">{{ form.errors.subtitle }}</span>
                        </div>

                        <div>
                            <InputLabel label="Address Title" v-model="form.address_title" />
                            <span class="text-red-500 text-sm">{{ form.errors.address_title }}</span>
                        </div>

                        <div>
                            <Label label="Map" />
                            <Textarea v-model="form.map" :rows="5" />
                        </div>

                        <div>
                            <label class="font-medium text-sm">Image</label>
                            <input type="file" class="mainImage dropify" @change="handleImageChange" />
                        </div>

                    </div>

                    <div class="mt-5 border-t pt-4">
                        <Button label="Update" type="submit" />
                    </div>

                </form>
            </div>

            <!-- ================= CONTACT NUMBERS ================= -->
            <div class="p-5">
                <div class="border-b pb-3 mb-4">
                    <h1 class="text-xl font-medium">Contact Numbers</h1>
                </div>

                <form @submit.prevent="submitNumbers">

                    <div v-for="(item, i) in numberForm.numbers" :key="item.id" class="mb-6 border p-4 rounded">

                        <div class="grid grid-cols-2 gap-4">
                            <InputLabel label="Title" v-model="item.title" />
                            <InputLabel label="Sub Title" v-model="item.subtitle" />
                        </div>

                        <!-- Numbers -->
                        <div class="mt-3">
                            <label class="font-medium text-sm">Numbers</label>

                            <div class="grid grid-cols-3 gap-3 mt-2">
                                <div v-for="(num, j) in item.numbers" :key="j" class="flex gap-2">

                                    <input v-model="item.numbers[j]" class="border p-2 w-full rounded text-sm" />

                                    <button type="button" class="bg-red-500 text-white px-2 rounded cursor-pointer"
                                        @click="removeNumber(i, j)">
                                        <XIcon class="w-4 h-4" />
                                    </button>

                                </div>
                            </div>

                            <button type="button"
                                class="mt-2 bg-blue-500 text-white px-3 py-1 rounded text-sm cursor-pointer"
                                @click="addNumber(i)">
                                + Add Number
                            </button>
                        </div>

                    </div>

                    <div class="mt-5 border-t pt-4">
                        <Button label="Update Numbers" type="submit" />
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
</style>