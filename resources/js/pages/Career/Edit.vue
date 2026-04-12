<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Careers List',
        href: '/careers',
    },
];

interface Career {
    id: number;
    section: string;
    title: string;
    subtitle: string;
}

const props = defineProps<{
    career: Career;
}>();


const form = useForm({
    id: props.career.id,
    section: props.career.section || '',
    title: props.career.title || '',
    subtitle: props.career.subtitle || '',
});


const submit = () => {
    form.post(`/careers/update/${props.career.id}`, {
        forceFormData: true,
    });
};


</script>

<template>

    <Head title="Careers Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Careers Update</h1>
                <LinkButton label="Back" url="/careers" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <InputLabel label="Title" v-model="form.title" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.title">{{ form.errors.title
                                }}</span>
                        </div>
                        <div>
                            <InputLabel label="Sub Title" v-model="form.subtitle" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.subtitle">{{ form.errors.subtitle }}</span>
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
