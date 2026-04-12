<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import InputLabel from '@/components/admin/InputLabel.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

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
    social: Social;
}>()

const form = useForm({
    title: props.social.title,
    url: props.social.url,
    icon: props.social.icon,
});


const submit = () => {
    form.post('/socials/update/' + props.social.id, {
        onSuccess: () => {
            form.reset();
        }
    });
};


</script>

<template>

    <Head title="Socials" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Socials Create</h1>
                <LinkButton label="Back" url="/socials" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <InputLabel label="Title" v-model="form.title" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.title">
                                    {{ form.errors.title }}
                                </span>
                            </div>

                            <div>
                                <InputLabel label="Url" v-model="form.url" type="text" />
                                <span class="text-red-500 text-sm" v-if="form.errors.url">
                                    {{ form.errors.url }}
                                </span>
                            </div>

                            <div>
                                <label for="icon" class="text-[#5D5D5D] font-medium text-sm">Icon (SVG)</label>
                                <textarea name="" id="icon" v-model="form.icon" rows="5" class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm"></textarea>
                                <span class="text-red-500 text-sm" v-if="form.errors.icon">
                                    {{ form.errors.icon }}
                                </span>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="border-t pt-4 border-dashed">
                        <Button label="Update" type="submit" />
                    </div>

                </form>
            </div>

        </div>
    </AppLayout>
</template>

