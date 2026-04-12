<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Socials List',
        href: '/socials',
    },
];

const form = useForm({
    title: '',
    url: '',
    icon: '',
});


const submit = () => {
    form.post('/socials/store', {
        onSuccess: () => {
            form.reset();
        }
    });
};


</script>

<template>

    <Head title="Socials" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Socials Create</h1>
                <LinkButton label="Back" url="/socials" />
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

                            <div>
                                <InputLabel label="Url" v-model="form.url" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.url">
                                    {{ form.errors.url }}
                                </span>
                            </div>

                            <div>
                                <label for="icon" class="text-[#5D5D5D] font-medium text-sm">Icon (SVG)</label>
                                <textarea name="" id="icon" v-model="form.icon" rows="5" class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm"></textarea>
                                <span class="text-red-500 text-sm" v-if="form.errors.icon">
                                    {{ form.errors.icon }}
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
