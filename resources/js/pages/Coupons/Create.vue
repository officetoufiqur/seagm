<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Coupons List',
        href: '/coupons',
    },
];

const props = defineProps<{
    products: {
        id: number;
        name: string;
    }[];
}>()

const form = useForm({
    product_id: '',
    title: '',
    subtitle: '',
    discount_percent: '',
    total_coupons: '',
    claimed_count: '',
    valid_from: '',
    valid_to: '',
    terms: ''
});

const submit = () => {
    form.post('/coupons/store', {
        onSuccess: () => {
            form.reset();
        }
    });
};

</script>

<template>

    <Head title="Coupon" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Coupon Create</h1>
                <LinkButton label="Back" url="/coupons" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="product_id" class="text-[#5D5D5D] font-medium text-sm">Select Product</label>
                               <select name="product_id" id="product_id" v-model="form.product_id" class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm">
                                    <option value="">Select Product</option>
                                    <option v-for="product in props.products" :value="product.id" :key="product.id">
                                        {{ product.name }}
                                    </option>
                                </select>
                                <span class="text-red-500 text-sm" v-if="form.errors.product_id">
                                    {{ form.errors.product_id }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Title" v-model="form.title" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.title">
                                    {{ form.errors.title }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Sub Title" v-model="form.subtitle" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.subtitle">
                                    {{ form.errors.subtitle }}
                                </span>
                            </div>
                            <div>
                                <InputLabel label="Discount Percent" v-model="form.discount_percent" type="number" />
                                <span class="text-red-500 text-sm" v-if="form.errors.discount_percent">
                                    {{ form.errors.discount_percent }}
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-3 col-span-2">
                            <div>
                                <InputLabel label="Total Coupons" v-model="form.total_coupons" type="number" />
                                <span class="text-red-500 text-sm" v-if="form.errors.total_coupons">
                                    {{ form.errors.total_coupons }}
                                </span>
                            </div>

                            <div>
                                <InputLabel label="Valid From" v-model="form.valid_from" type="date" />
                                <span class="text-red-500 text-sm" v-if="form.errors.valid_from">
                                    {{ form.errors.valid_from }}
                                </span>
                            </div>
                            <div class="">
                                <InputLabel label="Valid To" v-model="form.valid_to" type="date" />
                                <span class="text-red-500 text-sm" v-if="form.errors.valid_to">
                                    {{ form.errors.valid_to }}
                                </span>
                            </div>
                            </div>
                            <div class="col-span-2">
                                <label for="desc" class="text-[#5D5D5D] font-medium text-sm">Terms & Conditions</label>
                                <QuillEditor v-model:content="form.terms" contentType="html" theme="snow"
                                    class="mt-4" />
                                <span class="text-red-500 text-sm" v-if="form.errors.terms">
                                    {{ form.errors.terms }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-20 border-t pt-4 border-dashed">
                        <Button label="Submit" type="submit" />
                    </div>

                </form>
            </div>

        </div>
    </AppLayout>
</template>
