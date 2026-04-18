<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import InputLabel from '@/components/admin/InputLabel.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Star category List',
        href: '/star-category',
    },
];

type Category = {
    id: number
    name: string
    slug: string
}

const props = defineProps<{
    category: Category;
}>()

const form = useForm({
    name: props.category.name,
});


const submit = () => {
    form.post('/star-category/update/' + props.category.id, {
        onSuccess: () => {
            form.reset();
        }
    });
};


</script>

<template>

    <Head title="Star category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Star category Update</h1>
                <LinkButton label="Back" url="/star-category" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <InputLabel label="Name" v-model="form.name" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.name">{{ form.errors.name
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

