<script setup lang="ts">
import FilterTable from '@/components/admin/FilterTable.vue';
import FlashMessage from '@/components/admin/FlashMessage.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { SquarePenIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Department List',
        href: '/departments',
    },
];


const props = defineProps<{
    department: {
        id: number;
        description: string
        image?: string | null;
    };
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Image', key: 'image' },
    { label: 'Description', key: 'description' },
    { label: 'Action', key: 'action' },
]

const data = ref([props.department]);

</script>

<template>

    <Head title="Department" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Department List" >

                <template #image="{ item }">
                    <img :src="item.image" :alt="item.description" class="w-16 h-16 object-cover rounded" />
                </template>

                <template #description="{ item }">
                    <span>{{ item.description.slice(0, 100) }}</span>
                </template>
                
                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/departments/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
