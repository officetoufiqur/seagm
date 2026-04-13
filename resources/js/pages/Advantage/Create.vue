<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Advantage List',
        href: '/advantage',
    },
];


const form = useForm({
    label: '',
    value: ''
});


const submit = () => {
    form.post('/advantage/store', {
        forceFormData: true,
    });
};


</script>

<template>

    <Head title="Advantage Create" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Advantage Create</h1>
                <LinkButton label="Back" url="/advantage" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit" class="space-y-3">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- label -->
                        <div>
                            <InputLabel label="Label" v-model="form.label" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.label">
                                {{ form.errors.label }}
                            </span>
                        </div>

                        <!-- value -->
                        <div>
                            <InputLabel label="value" v-model="form.value" type="number" />
                            <span class="text-red-500 text-sm" v-if="form.errors.value">
                                {{ form.errors.value }}
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
