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
        title: 'About contact',
        href: '/about-contact',
    },
];


const props = defineProps<{
    contact: {
        id: number
        title: string
        subtitle: string
        image: string
    };
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Image', key: 'image' },
    { label: 'Title', key: 'title' },
    { label: 'Sub Title', key: 'subtitle' },
    { label: 'Action', key: 'action' },
]

const data = ref([props.contact]);


</script>

<template>

    <Head title="About contact" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="About Us List">

                <template #subtitle="{ item }">
                    {{ item.subtitle ? item.subtitle.substring(0, 120) + '...' : item.subtitle }}
                </template>

                <template #image="{ item }">
                    <img :src="item.image" class="w-10 h-10 rounded" />
                </template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/about-contact/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
