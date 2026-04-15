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
        title: 'Articles List',
        href: '/articles',
    },
];

type SubCategory = {
    id: number
    name: number
}

const props = defineProps<{
    articles: SubCategory[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Category Name', key: 'subcategory' },
    { label: 'Title', key: 'title' },
    { label: 'Content', key: 'content' },
    { label: 'Promoted', key: 'promoted' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.articles);


function deleteUserGuide(id: number) {
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
            router.delete(`/articles/destroy/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    data.value = props.articles;
                }
            });
        }
    })
}

const statusChange = (id: number) => {
    router.post(`/articles/promoted/${id}`, {}, {
        preserveScroll: true,
        onSuccess: () => {
            data.value = props.articles;
        }
    });
}


</script>

<template>

    <Head title="Articles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Articles List" create-btn create-text="Create"
                create-url="/articles/create">

                <template #subcategory="{ item }">
                   <span>{{ item.sub_category.name }}</span>
                </template>

                <template #content="{ item }">
                    {{ item.content.slice(0, 100) }}
                </template>

                <template #promoted="{ item }">
                    <span v-if="item.is_promoted == 1">Yes</span>
                    <span v-else>No</span>
                </template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/articles/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                        <button @click="deleteUserGuide(item.id)"
                            class="bg-[#F06548] text-sm cursor-pointer text-white rounded font-medium py-2 px-3">
                            <Trash2Icon class="w-4.5 h-4.5" />
                        </button>

                        <!-- promoted button -->
                        <button @click="statusChange(item.id)" :class="item.is_promoted == 1
                            ? 'bg-red-500 hover:bg-red-600'
                            : 'bg-green-500 hover:bg-green-600'"
                            class="text-sm cursor-pointer text-white rounded font-medium py-2 px-3 flex items-center gap-1"
                            :title="item.is_promoted == 1 ? 'Deactivate' : 'Activate'">
                            <component :is="item.is_promoted == 1 ? BanIcon : CheckCircle2Icon" class="w-4 h-4" />
                        </button>
                        
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
