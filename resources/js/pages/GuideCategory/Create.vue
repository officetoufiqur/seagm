<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'User Guide Categories List',
        href: '/user-guide-categories',
    },
];

const form = useForm({
    name: '',
    icon: '',
    description: '',
});


const submit = () => {
    form.post('/user-guide-categories/store', {
        onSuccess: () => {
            form.reset();
        }
    });
};



</script>

<template>

    <Head title="User Guide Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">User Guide Categories Create</h1>
                <LinkButton label="Back" url="/user-guide-categories" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <InputLabel label="Name" v-model="form.name" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.name">
                                    {{ form.errors.name }}
                                </span>
                            </div>

                            <div>
                                <InputLabel label="icon" v-model="form.icon" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.icon">
                                    {{ form.errors.icon }}
                                </span>
                            </div>
                            
                            <div class="h-30">
                                <label for="description" class="text-[#5D5D5D] font-medium text-sm">Description</label>
                                <QuillEditor v-model:content="form.description" contentType="html" theme="snow"
                                    class="mt-4" />
                                <span class="text-red-500 text-sm" v-if="form.errors.description">
                                    {{ form.errors.description }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="border-t pt-4 border-dashed mt-20">
                        <Button label="Submit" type="submit" />
                    </div>

                </form>
            </div>

        </div>
    </AppLayout>
</template>

