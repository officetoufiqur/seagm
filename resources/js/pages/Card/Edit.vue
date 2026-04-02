<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Card Items List',
        href: '/card-items',
    },
];

interface Card {
    id: number
    category_id: number
    api_id: number
    api_category_id: number
    category_name: string
    par_value_currency: string
    par_value: number
    currency: string
    unit_price: number
    max_amount: number
    min_amount: number
    origin_price: number
    discount_rate: number
    has_stock: boolean
    status: boolean
}

const props = defineProps<{
    card: Card;
}>()

const form = useForm({
    id: props.card.id,
    category_name: props.card.category_name,
    par_value_currency: props.card.par_value_currency,
    par_value: props.card.par_value,
    currency: props.card.currency,
    unit_price: props.card.unit_price,
    max_amount: props.card.max_amount,
    min_amount: props.card.min_amount,
    origin_price: props.card.origin_price,
    discount_rate: props.card.discount_rate
});


const submit = () => {
    form.post('/card-items/update/' + props.card.id, {
        forceFormData: true,
    });
}


</script>

<template>

    <Head title="Card Item" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">
            <div class="flex items-center justify-between border-b border-dashed border-gray-200 pb-3">
                <h1 class="text-xl font-medium">Card Item Update</h1>
                <LinkButton :label="'Back'" :url="'/card-items'" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">
                    <div class="space-y-3 mb-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div>
                                <InputLabel label="Category Name" v-model="form.category_name" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.category_name">{{
                                    form.errors.category_name }}</span>
                            </div>

                            <div>
                                <InputLabel label="Par Value Currency" v-model="form.par_value_currency" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.par_value_currency">{{
                                    form.errors.par_value_currency }}</span>
                            </div>

                            <div>
                                <InputLabel label="Par Value" v-model="form.par_value" type="number" />
                                <span class="text-red-500 text-sm" v-if="form.errors.par_value">{{ form.errors.par_value
                                }}</span>
                            </div>

                            <div>
                                <InputLabel label="Currency" v-model="form.currency" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.currency">{{ form.errors.currency
                                }}</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 col-span-2">
                                <div>
                                    <InputLabel label="Unit Price" v-model="form.unit_price" type="number" />
                                    <span class="text-red-500 text-sm" v-if="form.errors.unit_price">{{
                                        form.errors.unit_price }}</span>
                                </div>

                                <div>
                                    <InputLabel label="Max Amount" v-model="form.max_amount" type="number" />
                                    <span class="text-red-500 text-sm" v-if="form.errors.max_amount">{{
                                        form.errors.max_amount }}</span>
                                </div>

                                <div>
                                    <InputLabel label="Min Amount" v-model="form.min_amount" type="number" />
                                    <span class="text-red-500 text-sm" v-if="form.errors.min_amount">{{
                                        form.errors.min_amount }}</span>
                                </div>
                            </div>

                            <div>
                                <InputLabel label="Origin Price" v-model="form.origin_price" type="number" />
                                <span class="text-red-500 text-sm" v-if="form.errors.origin_price">{{
                                    form.errors.origin_price }}</span>
                            </div>

                            <div>
                                <InputLabel label="Discount Rate" v-model="form.discount_rate" type="number" />
                                <span class="text-red-500 text-sm" v-if="form.errors.discount_rate">{{
                                    form.errors.discount_rate }}</span>
                            </div>

                        </div>
                    </div>
                    <div class="border-t border-dashed border-gray-200 pt-3">
                        <Button :label="`Update`" :type="`submit`" />
                    </div>
                </form>
            </div>

        </div>
    </AppLayout>
</template>
