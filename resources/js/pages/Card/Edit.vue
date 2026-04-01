<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Card Category',
        href: '/card-categories',
    },
];

interface Category {
    id: number
    api_id: number
    name: string
    code: string
    mode: string
    region: string
    publisher: string
    auto_delivery: boolean
    icon: string
}

const props = defineProps<{
    category: Category;
}>()

const form = useForm({
    name: props.category.name,
    code: props.category.code,
    mode: props.category.mode,
    region: props.category.region,
    publisher: props.category.publisher,
    auto_delivery: props.category.auto_delivery,
    icon: props.category.icon
});


const submit = () => {
    form.post('/card-categories/update/' + props.category.id);
}

</script>

<template>

    <Head title="Card Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <div class="flex items-center justify-between border-b border-dashed border-gray-200 pb-3">
                <h1 class="text-xl font-medium">Card Category Update</h1>
                <LinkButton :label="'Back'" :url="'/card-categories'" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">
                    <div class="space-y-3 mb-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <InputLabel forr="name" :label="'Name'" v-model="form.name" type="text"
                                :placeholder="'Enter category name'" />
                            <span class="text-red-500 text-sm" v-if="form.errors.name">{{ form.errors.name }}</span>
                        </div>
                        <div>
                            <InputLabel forr="code" :label="'Code'" v-model="form.code" type="text"
                                :placeholder="'Enter category code'" />
                            <span class="text-red-500 text-sm" v-if="form.errors.code">{{ form.errors.code }}</span>
                        </div>
                        <div>
                            <InputLabel forr="mode" :label="'Mode'" v-model="form.mode" type="text"
                                :placeholder="'Enter category mode'" />
                            <span class="text-red-500 text-sm" v-if="form.errors.mode">{{ form.errors.mode }}</span>
                        </div>
                        <div>
                            <InputLabel forr="region" :label="'Region'" v-model="form.region" type="text"
                                :placeholder="'Enter category region'" />
                            <span class="text-red-500 text-sm" v-if="form.errors.region">{{ form.errors.region }}</span>
                        </div>
                        <div>
                            <InputLabel forr="publisher" :label="'Publisher'" v-model="form.publisher" type="text"
                                :placeholder="'Enter category publisher'" />
                            <span class="text-red-500 text-sm" v-if="form.errors.publisher">{{ form.errors.publisher }}</span>
                        </div>
                        <div>
                            <InputLabel forr="icon" :label="'Icon'" v-model="form.icon" type="text"
                                :placeholder="'Enter category icon'" />
                            <span class="text-red-500 text-sm" v-if="form.errors.icon">{{ form.errors.icon }}</span>
                        </div>
                        </div>
                    </div>
                    <Button :label="`Update`" :type="`submit`" />
                </form>
            </div>

        </div>
    </AppLayout>
</template>