<script setup lang="ts">
import Button from '@/components/admin/Button.vue';
import CKEditor from '@/components/admin/CKEditor.vue';
import Label from '@/components/admin/Label.vue';
import LinkButton from '@/components/admin/LinkButton.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { watch } from 'vue'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Terms and Conditions',
        href: '/terms',
    },
];

const props = defineProps<{
    term: {
        id: number;
        type: string;
        content: string;
    };
}>()

const form = useForm({
    type: props.term.type,
    content: props.term.content,
});

const data = ref([
    { value: 'terms_of_use', label: 'Terms of Use' },
    { value: 'terms_of_sale', label: 'Terms of Sale' },
    { value: 'privacy_policy', label: 'Privacy Policy' },
    { value: 'content_policy', label: 'Content Policy' },
    { value: 'cookies', label: 'Cookies' },
    { value: 'commercial_transactions_act', label: 'Commercial Transactions Act' },
])

const submit = () => {
    form.post(`/terms/update/${props.term.id}`, {
        onSuccess: () => {
            form.reset();
        }
    });
};

watch(() => form.type, () => {
    form.content = props.term.content;
})

</script>

<template>

    <Head title="Terms and Conditions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="bg-white m-7 p-5 rounded">

            <!-- Header -->
            <div class="flex items-center justify-between border-b pb-3">
                <h1 class="text-xl font-medium">Terms and Conditions Update</h1>
                <LinkButton label="Back" url="/terms" />
            </div>

            <div class="p-5">
                <form @submit.prevent="submit">

                    <!-- Main Fields -->
                    <div class="mb-3 space-y-4">
                        <div class="w-[50%]">
                            <Label :label="'Type'" />
                            <select name="type" id="type" v-model="form.type" class="border border-gray-300 rounded px-3 py-2 w-full mt-1 text-sm">
                                <option value="">Select Type</option>
                                <option v-for="item in data" :key="item.value" :value="item.value">
                                    {{ item.label }}
                                </option>
                            </select>
                            <span class="text-red-500 text-sm" v-if="form.errors.type">
                                {{ form.errors.type }}
                            </span>
                        </div>
                        <div>
                            <Label :label="'Content'" />
                            <div :key="form.type" class="h-80">
                                <CKEditor v-if="form.type === 'cookies' || form.type === 'commercial_transactions_act'" v-model="form.content" />
                                <QuillEditor v-else v-model:content="form.content" contentType="html" theme="snow"
                                    class="mt-4" />
                            </div>
                            <span class="text-red-500 text-sm" v-if="form.errors.content">
                                {{ form.errors.content }}
                            </span>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="mt-25 border-t pt-4 border-dashed">
                        <Button label="Update" type="submit" />
                    </div>

                </form>
            </div>

        </div>
    </AppLayout>
</template>
<style>
.ck-editor__editable {
    min-height: 300px;
}

.ck-content h1 {
    font-size: 2rem;
    font-weight: bold;
}

.ck-content h2 {
    font-size: 1.5rem;
    font-weight: 600;
}

.ck-content h3 {
    font-size: 1.25rem;
    font-weight: 500;
}
</style>