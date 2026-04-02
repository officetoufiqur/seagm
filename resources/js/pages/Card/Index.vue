<script setup lang="ts">
import FilterTable from '@/components/admin/FilterTable.vue';
import FlashMessage from '@/components/admin/FlashMessage.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { SquarePenIcon, Trash2Icon } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Card items List',
        href: '/card-items',
    },
];

type Card = {
    id: number
    category_id: number
    api_id: number
    api_category_id: number
    category_name: string
    par_value_currency: string
    par_value: number
    currency: number
    unit_price: number
    max_amount: number
    min_amount: number
    origin_price: number
    discount_rate: number
    has_stock: boolean
    status: boolean
}

const props = defineProps<{
    cards: Card[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Category', key: 'category_name' },
    { label: 'Par Value', key: 'par_value' },
    { label: 'Currency', key: 'par_value_currency' },
    { label: 'Price', key: 'unit_price' },
    { label: 'Stock', key: 'has_stock' },
    { label: 'Status', key: 'status' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.cards);


function deleteCard(id: number) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/card-items/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    data.value = props.cards;
                }
            });
        }
    })
}

</script>

<template>

    <Head title="Card items" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Card items List">

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/card-items/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                        <button @click="deleteCard(item.id)"
                            class="bg-[#F06548] text-sm cursor-pointer text-white rounded font-medium py-2 px-3">
                            <Trash2Icon class="w-4.5 h-4.5" />
                        </button>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
