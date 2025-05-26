<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Breadcrumb from '@/Components/Breadcrumb.vue';

defineProps({
    user: {
        type: Object,
        default: () => ({}),
    },
});

const breadcrumbItems = [
    { name: 'Users', href: '/admin/users' },
    { name: 'Show', href: null },
];
</script>

<template>
    <AdminLayout>
        <template #breadcrumbs>
            <Breadcrumb :items="breadcrumbItems" />
        </template>

        <div class="py-12">
            <div class="p-6 max-w-2xl mx-auto">
                <h1 class="text-2xl font-semibold mb-2">{{ user.name }}</h1>
                <p class="mb-2 text-gray-600">{{ user.email }}</p>
                <p class="mb-4">
                    Role:
                    <span
                        class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded"
                    >
            {{ user.roles[0]?.name ?? '—' }}
          </span>
                </p>

                <div>
                    <h3 class="font-semibold mb-2">Permissions:</h3>
                    <div class="flex flex-wrap gap-2">
            <span
                v-for="perm in user.permissions"
                :key="perm.id"
                class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded"
            >
              {{ perm.name }}
            </span>
                    </div>
                </div>

                <Link
                    href="/admin/users"
                    class="text-blue-600 underline mt-6 inline-block"
                >
                    ← Back
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>
