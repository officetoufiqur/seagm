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
        title: 'Socials List',
        href: '/socials',
    },
];

type Social = {
    id: number
    title: string
    url: string
    icon: string
}

const props = defineProps<{
    socials: Social[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Title', key: 'title' },
    { label: 'Url', key: 'url' },
    { label: 'Icon', key: 'icon' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.socials);


function deletesocials(id: number) {
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
            router.delete(`/socials/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    data.value = props.socials;
                }
            });
        }
    })
}

</script>

<template>

    <Head title="Socials" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="Socials List" create-btn create-text="Create socials"
                create-url="/socials/create">

                <template #icon="{ item }">
                    <span v-html="item.icon"></span>
                </template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <Link :href="`/socials/edit/${item.id}`"
                            class="bg-[#0AB39C] text-sm cursor-pointer text-white rounded font-medium hover:bg-[#0AB39C] py-2 px-3">
                            <SquarePenIcon class="w-4.5 h-4.5" />
                        </Link>
                        <button @click="deletesocials(item.id)"
                            class="bg-[#F06548] text-sm cursor-pointer text-white rounded font-medium py-2 px-3">
                            <Trash2Icon class="w-4.5 h-4.5" />
                        </button>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
