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
        title: 'News List',
        href: '/news',
    },
];

type News = {
    id: number
    category_id: number
    author_id: number
    title: string
    slug: string
    content: string
    image: string
    published_at: string
}

const props = defineProps<{
    news: News[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Author', key: 'author' },
    { label: 'Image', key: 'image' },
    { label: 'Title', key: 'title' },
    { label: 'Slug', key: 'slug' },
    { label: 'Category', key: 'category' },
    { label: 'Published At', key: 'published_at' },
    { label: 'Status', key: 'status' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.news);


function deleteNews(id: number) {
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
            router.delete(`/news/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    data.value = props.news;
                }
            });
        }
    })
}

function statusChange(id: number) {
    router.post(`/news/status/${id}`, {}, {
        preserveScroll: true,
        onSuccess: () => {
            data.value = props.news;
        }
    });
}

</script>

<template>

    <Head title="News" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="News List" create-btn create-text="Create News"
                create-url="/news/create">

                <template #author="{ item }">
                    {{ item.author ? item.author.name : 'N/A' }}
                </template>

                <template #category="{ item }">
                    {{ item.category ? item.category.name : 'N/A' }}
                </template>

                <template #image="{ item }">
                    <img :src="item.image" class="w-10 h-10 rounded" />
                </template>

                <template #published_at="{ item }">
                    {{ item.published_at.replace('T', ' ').slice(0, 10) }}
                </template>

                <template #status="{ item }">
                    <span v-if="item.status == 1"
                        class="bg-green-200 text-green-600 px-2 py-1 rounded font-medium text-sm">Active</span>
                    <span v-else class="bg-red-200 text-red-600 px-2 py-1 rounded font-medium text-sm">Inactive</span>
                </template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/news/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                        <button @click="deleteNews(item.id)"
                            class="bg-[#F06548] text-sm cursor-pointer text-white rounded font-medium py-2 px-3">
                            <Trash2Icon class="w-4.5 h-4.5" />
                        </button>
                        <button @click="statusChange(item.id)" :class="item.status == 1
                            ? 'bg-red-500 hover:bg-red-600'
                            : 'bg-green-500 hover:bg-green-600'"
                            class="text-sm cursor-pointer text-white rounded font-medium py-2 px-3 flex items-center gap-1"
                            :title="item.status == 1 ? 'Deactivate' : 'Activate'">
                            <component :is="item.status == 1 ? BanIcon : CheckCircle2Icon" class="w-4 h-4" />
                        </button>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
