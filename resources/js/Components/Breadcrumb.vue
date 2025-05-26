<script setup>
defineProps({
    items: {
        type: Array,
        required: true, // Example: [{ name: 'Users', href: '/admin/users' }, { name: 'Edit', href: null }]
    },
});
</script>

<template>
    <nav class="text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
        <ol class="list-reset flex space-x-2">
            <li>
                <Link href="/admin/dashboard" class="hover:underline text-gray-600">Dashboard</Link>
                <span>/</span>
            </li>
            <li v-for="(item, index) in items" :key="index" class="flex items-center space-x-1">
                <span v-if="index !== 0">/</span>
                <span v-if="item.href">
          <Link :href="item.href" class="hover:underline text-gray-600">{{ item.name }}</Link>
        </span>
                <span v-else class="text-gray-800 font-medium">{{ item.name }}</span>
            </li>
        </ol>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3';
</script>
