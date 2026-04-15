<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sub Categories List',
        href: '/sub_categories',
    },
];

interface Category {
    id: number;
    name: string;
}

defineProps<{
    category: Category[];
    flash: {
        message?: string;
    };
}>()

const form = useForm({
    name: '',
    category_id: ''
});


const submit = () => {
    form.post('/sub-categories/store', {
        onSuccess: () => {
            form.reset();
        }
    });
};



</script>

<template>

    <Head title="Sub Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Sub Categories Create</h1>
                <LinkButton label="Back" url="/sub-categories" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[#5D5D5D] font-medium text-sm">Category</label>
                                <select class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm" v-model="form.category_id">
                                    <option value="">Select Category</option>
                                    <option v-for="item in category" :value="item.id" :key="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <span class="text-red-500 text-sm" v-if="form.errors.category_id">
                                    {{ form.errors.category_id }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Name" v-model="form.name" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.name">
                                    {{ form.errors.name }}
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
