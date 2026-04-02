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
    cards: {
        id: number;
        name: string;
    }[];
    coupon: {
        id: number;
        card_id: number;
        title: string;
        subtitle: string;
        discount_percent: number;
        total_coupons: number;
        claimed_count: number;
        valid_from: string;
        valid_to: string;
        terms: string;
    }
}>()

function formatDate(date: string) {
    if (!date) return '';
    return new Date(date).toISOString().slice(0, 16);
}

const form = useForm({
    card_id: props.coupon.card_id,
    title: props.coupon.title,
    subtitle: props.coupon.subtitle,
    discount_percent: props.coupon.discount_percent.toString(),
    total_coupons: props.coupon.total_coupons.toString(),
    claimed_count: props.coupon.claimed_count,
    valid_from: formatDate(props.coupon.valid_from),
    valid_to: formatDate(props.coupon.valid_to),
    terms: props.coupon.terms
});

const submit = () => {
    form.post(`/coupons/update/${props.coupon.id}`, {
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
                <h1 class="text-xl font-medium">Coupon Update</h1>
                <LinkButton label="Back" url="/coupons" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="card_id" class="text-[#5D5D5D] font-medium text-sm">Select
                                    Card</label>
                                <select name="card_id" id="card_id" v-model="form.card_id"
                                    class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm">
                                    <option value="">Select Card</option>
                                    <option v-for="card in props.cards" :value="card.id" :key="card.id">
                                        {{ card.name }}
                                    </option>
                                </select>
                                <span class="text-red-500 text-sm" v-if="form.errors.card_id">
                                    {{ form.errors.card_id }}
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
                                    <InputLabel label="Valid From" v-model="form.valid_from" type="datetime-local" />
                                    <span class="text-red-500 text-sm" v-if="form.errors.valid_from">
                                        {{ form.errors.valid_from }}
                                    </span>
                                </div>
                                <div class="">
                                    <InputLabel label="Valid To" v-model="form.valid_to" type="datetime-local" />
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
                        <Button label="Update" type="submit" />
                    </div>

                </form>
            </div>

        </div>
    </AppLayout>
</template>
