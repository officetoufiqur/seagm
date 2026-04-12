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
        title: 'joinus List',
        href: '/join-us',
    },
];

interface Item {
    id?: number;
    title: string;
}

const form = useForm({
    title: '',
    icon: '',
    items: [] as Item[],
});

const addItem = () => {
    form.items.push({
        title: '',
    });
};

const removeItem = (index: number) => {
    form.items.splice(index, 1);
};

const submit = () => {
    form.post('/join-us/store', {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Joinus Create" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Joinus Create</h1>
                <LinkButton label="Back" url="/join-us" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="space-y-3">
                        <div>
                            <InputLabel label="Title" v-model="form.title" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.title">
                                {{ form.errors.title }}
                            </span>
                        </div>

                        <div>
                            <Label :label="`Icon (SVG)`" />
                            <textarea
                                v-model="form.icon"
                                rows="5"
                                class="w-full border border-gray-300 text-sm rounded-lg p-2.5"
                            ></textarea>
                            <span class="text-red-500 text-sm" v-if="form.errors.icon">
                                {{ form.errors.icon }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div>
                        <div class="flex justify-between items-center mb-3 mt-3">
                            <h2 class="font-medium text-gray-700">Items</h2>
                            <button
                                type="button"
                                @click="addItem"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-sm cursor-pointer"
                            >
                                + Add Item
                            </button>
                        </div>

                        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                v-for="(item, index) in form.items"
                                :key="index"
                                class="flex gap-3 items-center p-3 rounded-lg border"
                            >
                                <div class="w-full">
                                    <InputLabel
                                        v-model="item.title"
                                        type="text"
                                        placeholder="Enter item title"
                                    />
                                </div>

                                <button
                                    type="button"
                                    @click="removeItem(index)"
                                    class="text-red-500 hover:text-red-700 text-sm"
                                >
                                    Remove
                                </button>
                            </div>
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