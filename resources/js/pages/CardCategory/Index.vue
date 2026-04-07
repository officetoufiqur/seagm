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
        title: 'Card Categories List',
        href: '/card-categories',
    },
];

type Category = {
    id: number
    api_id: number
    name: string
    code: string
    mode: string
    region: string
    publisher: string
    auto_delivery: boolean
    icon: string
    status: boolean
}

const props = defineProps<{
    categories: Category[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Name', key: 'name' },
    { label: 'Code', key: 'code' },
    { label: 'Mode', key: 'mode' },
    { label: 'Region', key: 'region' },
    { label: 'Publisher', key: 'publisher' },
    { label: 'Status', key: 'status' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.categories);


function deleteCategory(id: number) {
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
            router.delete(`/news-categories/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    data.value = props.categories;
                }
            });
        }
    })
}

</script>

<template>

    <Head title="Card Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Card Category List">

                <template #status="{ item }">
                    <span :class="item.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium">
                        {{ item.status ? 'Updated' : 'Not Updated' }}
                    </span>
                </template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/card/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                        <button @click="deleteCategory(item.id)"
                            class="bg-[#F06548] text-sm cursor-pointer text-white rounded font-medium py-2 px-3">
                            <Trash2Icon class="w-4.5 h-4.5" />
                        </button>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
