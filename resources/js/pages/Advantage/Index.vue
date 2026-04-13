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
        title: 'Advantages List',
        href: '/advantage',
    },
];


const props = defineProps<{
    advantages: {
        id: number;
        label: string
        value: string
    }[];
    card: {
        id: number;
        title: string
        description: string
    }
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Label', key: 'label' },
    { label: 'Value', key: 'value' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.advantages);

</script>

<template>

    <Head title="Advantages" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <div class="bg-white shadow rounded-lg overflow-hidden p-5">
                <div>
                    <h2 class="text-xl font-semibold">Advantage Card Data</h2>
                </div>
                <table class="min-w-full border border-gray-200 mt-3">

                    <!-- Header -->
                    <thead class="bg-[#F3F6F9] text-gray-700 text-sm uppercase">
                        <tr>
                            <th class="text-left px-6 py-3 border-b">ID</th>
                            <th class="text-left px-6 py-3 border-b">Title</th>
                            <th class="text-left px-6 py-3 border-b">Description</th>
                            <th class="text-left px-6 py-3 border-b">Action</th>
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody class="text-gray-700">

                        <tr class="border-b">
                            <td class="px-6 py-4">{{ props.card.id }}</td>
                            <td class="px-6 py-4">{{ props.card.title }}</td>
                            <td class="px-6 py-4">{{ props.card.description }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <Link :href="`/advantage-card`"
                                        class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                                        <SquarePenIcon class="w-4.5 h-4.5" />
                                    </Link>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <FilterTable :rows="data" :columns="columns" title="Advantages List" :create-btn="true" create-text="Create"
                create-url="/advantage/create">

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/advantage/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
