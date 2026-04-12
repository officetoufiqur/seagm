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
        title: 'Joinus List',
        href: '/join-us',
    },
];

interface Item {
    id?: number;
    title: string;
}


interface Joinus {
    id: number;
    title: string;
    icon: string;
    items: Item[];
}

const props = defineProps<{
    joinus: Joinus;
}>();


const form = useForm({
    id: props.joinus.id,
    title: props.joinus.title || '',
    icon: props.joinus.icon || '',

    items: props.joinus.items?.length
        ? props.joinus.items.map(item => ({
            id: item.id,
            title: item.title
        }))
        : []
});


const addItem = async () => {
    form.items.push({
        id: undefined,
        title: '',
    });
};


const removeItem = (index: number) => {
    form.items.splice(index, 1);
};


const submit = () => {
    form.post(`/joinus/update/${props.joinus.id}`, {
        forceFormData: true,
    });
};

</script>

<template>

    <Head title="Joinus Edit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Joinus Update</h1>
                <LinkButton label="Back" url="/join-us" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="space-y-3">
                        <div>
                            <InputLabel label="Title" v-model="form.title" type="text" />
                            <span class="text-red-500 text-sm" v-if="form.errors.title">{{ form.errors.title }}</span>
                        </div>
                        <div>
                            <Label :label="`Icon (SVG)`" />
                            <textarea name="" id="icon" rows="5" v-model="form.icon" class="w-full border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                            <span class="text-red-500 text-sm" v-if="form.errors.icon">{{ form.errors.icon
                                }}</span>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div>
                        <div class="flex justify-between items-center mb-3 mt-3">
                            <h2 class="font-medium text-gray-700">Items</h2>
                            <button type="button" @click="addItem"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-sm cursor-pointer">
                                + Add Item
                            </button>
                        </div>

                        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="(item, index) in form.items" :key="index"
                                class="flex gap-3 items-center bg-gray-50 p-3 rounded-lg border">

                                <div class="w-full">
                                    <InputLabel v-model="item.title" type="text" :placeholder="`Enter items title`" />
                                </div>

                                <button type="button" @click="removeItem(index)"
                                    class="text-red-500 hover:text-red-700 text-sm">
                                    Remove
                                </button>
                            </div>
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
