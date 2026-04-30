<script setup lang="ts">
import FilterTable from '@/components/admin/FilterTable.vue';
import FlashMessage from '@/components/admin/FlashMessage.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Trash2Icon } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users List',
        href: '/users',
    },
];

type User = {
    id: number
    name: string,
    username: string,
    email: string,
    mobile: string,
    image: string,
}

const props = defineProps<{
    users: User[];
    flash: {
        message?: string;
    };
}>()

const columns = [
    { label: 'ID', key: 'id' },
    { label: 'Image', key: 'image' },
    { label: 'Name', key: 'name' },
    { label: 'Username', key: 'username' },
    { label: 'Email', key: 'email' },
    { label: 'Mobile', key: 'mobile' },
    { label: 'Action', key: 'action' },
]

const data = ref(props.users);


function deleteUser(id: number) {
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
            router.delete(`/users/${id}`, {
                preserveScroll: true,
                onSuccess: () => {
                    data.value = props.users;
                }
            });
        }
    })
}

</script>

<template>

    <Head title="User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <FlashMessage :message="props.flash.message" />
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-7">
            <FilterTable :rows="data" :columns="columns" title="User List">

                <template #image="{ item }">
                    <img :src="item.image" class="w-10 h-10 rounded" />
                </template>

                <template #action="{ item }">
                    <div class="flex items-center gap-2">
                        <button @click="deleteUser(item.id)"
                            class="bg-[#F06548] text-sm cursor-pointer text-white rounded font-medium py-2 px-3">
                            <Trash2Icon class="w-4.5 h-4.5" />
                        </button>
                    </div>
                </template>
            </FilterTable>
        </div>
    </AppLayout>
</template>
