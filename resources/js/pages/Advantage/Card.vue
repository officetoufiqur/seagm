<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import Label from '@/components/admin/Label.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Advantage List',
        href: '/advantage',
    },
];


const props = defineProps<{
    card: {
        id: number;
        title: string;
        description: number;
    };
}>()

const form = useForm({
    title: props.card.title,
    description: props.card.description
});



const submit = () => {
    form.post('/advantage-card', {
        forceFormData: true,
    });
};

</script>

<template>

    <Head title="Advantage Update" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Advantage Update</h1>
                <LinkButton label="Back" url="/advantage" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit" class="space-y-3">

                    <!-- label -->
                    <div>
                        <InputLabel label="Title" v-model="form.title" type="text" />
                        <span class="text-red-500 text-sm" v-if="form.errors.title">
                            {{ form.errors.title }}
                        </span>
                    </div>

                    <!-- description -->
                    <div>
                        <Label label="Description" />
                        <textarea name="" id="" cols="30" rows="3" v-model="form.description" class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm"></textarea>
                        <span class="text-red-500 text-sm" v-if="form.errors.description">
                            {{ form.errors.description }}
                        </span>
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
