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
        title: 'About Us',
        href: '/about-us',
    },
];


const props = defineProps<{
    aboutUs: {
        id: number
        title: string
        description: string
        page_view: number
        unique_visitors: number
        registered_users: number
        active_users: number
        subscribers: number
        image: string
    };
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Title', key: 'title' },
    { label: 'Description', key: 'description' },
    { label: 'Page View', key: 'page_view' },
    { label: 'Unique Visitors', key: 'unique_visitors' },
    { label: 'Registered Users', key: 'registered_users' },
    { label: 'Active Users', key: 'active_users' },
    { label: 'Subscribers', key: 'subscribers' },
    { label: 'Action', key: 'action' },
]

const data = ref([props.aboutUs]);


</script>

<template>

    <Head title="About Us" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="About Us List">

                <template #description="{ item }">
                    {{ item.description ? item.description.substring(0, 120) + '...' : item.description }}
                </template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/about-us/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
