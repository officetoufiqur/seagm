<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import Label from '@/components/admin/Label.vue';
import InputLabel from '@/components/admin/InputLabel.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Through Items List',
        href: '/through',
    },
];

const form = useForm({
    title: '',
    icon: '',
});


const submit = () => {
    form.post(`/through/store`, {
        forceFormData: true,
    });
};


</script>

<template>

    <Head title="Through Items Create" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Through Items Update</h1>
                <LinkButton label="Back" url="/through" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div>
                        <InputLabel label="Title" v-model="form.title" type="text" />
                        <span class="text-red-500 text-sm" v-if="form.errors.title">{{ form.errors.title
                        }}</span>
                    </div>
                    <div>
                        <Label label="Icon" />
                        <textarea name="" id="" rows="5" v-model="form.icon"
                            class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm"></textarea>
                        <span class="text-red-500 text-sm" v-if="form.errors.icon">{{ form.errors.icon
                        }}</span>
                    </div>

                    <!-- Submit -->
                    <div class="mt-5 border-t pt-4">
                        <Button label="Store" type="submit" />
                    </div>

                </form>
            </div>
        </div>
    </AppLayout>
</template>
