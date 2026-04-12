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
        title: 'Platforms List',
        href: '/platform',
    },
];


const props = defineProps<{
    platforms: {
        id: number;
        section: string
        title: string
    }[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Section', key: 'section' },
    { label: 'Title', key: 'title' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.platforms);

</script>

<template>

    <Head title="Platforms" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Platforms List" >

                <template #title="{ item }">
                    <span>{{ item.title.slice(0, 100) }}</span>
                </template>
                
                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/platform/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
