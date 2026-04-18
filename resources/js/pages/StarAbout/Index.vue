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
        title: 'Star abouts List',
        href: '/star-abouts',
    },
];

type About = {
    id: number
    section: string
    title: string
    subtitle: string
    description: string
    image: null | string
}

const props = defineProps<{
    abouts: About[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Mmage', key: 'image' },
    { label: 'Section', key: 'section' },
    { label: 'Title', key: 'title' },
    { label: 'Sub Title', key: 'subtitle' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.abouts);


function deleteAbouts(id: number) {
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
            router.delete(`/star-abouts/destroy/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    data.value = props.abouts;
                }
            });
        }
    })
}

</script>

<template>

    <Head title="Star abouts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Star abouts List" create-btn create-text="Create"
                create-url="/star-abouts/create">

                <template #image="{ item }">
                    <img :src="item.image" alt="image" class="w-10 h-10 object-cover rounded" />
                </template>

                <template #subtitle="{ item }"><span>{{ item.subtitle.slice(0, 50) }}</span></template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/star-abouts/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                        <button @click="deleteAbouts(item.id)"
                            class="bg-[#F06548] text-sm cursor-pointer text-white rounded font-medium py-2 px-3">
                            <Trash2Icon class="w-4.5 h-4.5" />
                        </button>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
