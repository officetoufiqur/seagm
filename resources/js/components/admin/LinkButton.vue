<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps < {
    label?: string
    url?: string
} > ()
</script>
<template>
    <Link :href="url || '#'"
        class="bg-[#F79219] hover:bg-[#662B8E] duration-300 px-6 py-1.5 text-white rounded-md cursor-pointer">
        {{ label }}
    </Link>
</template>