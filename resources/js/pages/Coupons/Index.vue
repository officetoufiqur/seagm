<script setup lang="ts">
import FilterTable from '@/components/admin/FilterTable.vue';
import FlashMessage from '@/components/admin/FlashMessage.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { BanIcon, CheckCircle2Icon, SquarePenIcon, Trash2Icon } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Coupons List',
        href: '/coupons',
    },
];

type Coupon = {
    id: number
    title: string
    subtitle: string
    discount_percent: number
    total_coupons: number
    valid_from: string,
    valid_to: string,
    is_active: boolean
}

const props = defineProps<{
    coupons: Coupon[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Title', key: 'title' },
    { label: 'Sub Title', key: 'subtitle' },
    { label: 'Discount %', key: 'discount_percent' },
    { label: 'Total Coupons', key: 'total_coupons' },
    { label: 'Valid From', key: 'valid_from' },
    { label: 'Valid To', key: 'valid_to' },
    { label: 'is_active', key: 'is_active' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.coupons);


function deleteCoupon(id: number) {
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
            router.delete(`/coupons/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    data.value = props.coupons;
                }
            });
        }
    })
}

function statusChange(id: number) {
    router.post(`/coupons/status/${id}`, {}, {
        preserveScroll: true,
        onSuccess: () => {
            data.value = props.coupons;
        }
    });
}

</script>

<template>

    <Head title="Coupon" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Coupon List" create-btn create-text="Create Coupon"
                create-url="/coupons/create">

                <template #is_active="{ item }">
                    <span v-if="item.is_active == 1"
                        class="bg-green-200 text-green-600 px-2 py-1 rounded font-medium text-sm">Active</span>
                    <span v-else class="bg-red-200 text-red-600 px-2 py-1 rounded font-medium text-sm">Inactive</span>
                </template>

                <template #valid_from="{ item }">
                    {{ item.valid_from.replace('T', ' ').slice(0, 16) }}
                </template>

                <template #valid_to="{ item }">
                    {{ item.valid_to.replace('T', ' ').slice(0, 16) }}
                </template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/coupons/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                        <button @click="deleteCoupon(item.id)"
                            class="bg-[#F06548] text-sm cursor-pointer text-white rounded font-medium py-2 px-3">
                            <Trash2Icon class="w-4.5 h-4.5" />
                        </button>
                        <button @click="statusChange(item.id)" :class="item.is_active == 1
                            ? 'bg-red-500 hover:bg-red-600'
                            : 'bg-green-500 hover:bg-green-600'"
                            class="text-sm cursor-pointer text-white rounded font-medium py-2 px-3 flex items-center gap-1"
                            :title="item.is_active == 1 ? 'Deactivate' : 'Activate'">
                            <component :is="item.is_active == 1 ? BanIcon : CheckCircle2Icon" class="w-4 h-4" />
                        </button>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
