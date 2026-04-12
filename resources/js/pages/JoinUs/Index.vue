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
        title: 'Joinus List',
        href: '/join-us',
    },
];


const props = defineProps<{
    joinus: {
        id: number;
        title: string
        icon: string
    }[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Icon', key: 'icon' },
    { label: 'Title', key: 'title' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.joinus);

</script>

<template>

    <Head title="Joinus" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Joinus List" :create-btn="true" create-text="Create Joinus" create-url="/join-us/create">

                <template #title="{ item }">
                    <span>{{ item.title.slice(0, 100) }}</span>
                </template>

                 <template #icon="{ item }">
                    <span v-html="item.icon"></span>
                </template>
                
                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/join-us/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
