<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'News Category',
        href: '/news-categories',
    },
];

interface Category {
    id: number;
    name: string;
    slug: string;
}

const props = defineProps<{
    category: Category;
}>()

const form = useForm({
    name: props.category.name
});


const submit = () => {
    form.post('/news-categories/update/' + props.category.id);
}

</script>

<template>

    <Head title="News Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <div class="flex items-center justify-between border-b border-dashed border-gray-200 pb-3">
                <h1 class="text-xl font-medium">News Category Update</h1>
                <LinkButton :label="'Back'" :url="'/news-categories'" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">
                    <div class="space-y-3 mb-5">
                        <div>
                            <InputLabel forr="name" :label="'Name'" v-model="form.name" type="text"
                                :placeholder="'Enter category name'" />
                            <span class="text-red-500 text-sm" v-if="form.errors.name">{{ form.errors.name }}</span>
                        </div>
                    </div>
                    <Button :label="`Update`" :type="`submit`" />
                </form>
            </div>

        </div>
    </AppLayout>
</template>