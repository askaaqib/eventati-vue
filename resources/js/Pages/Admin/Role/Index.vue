<template>
    <Head title="Roles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Roles</h2>
            <Link href="/admin/roles/create" class="bg-blue-500 text-white px-4 py-2 rounded float-right">Create Role</Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <table class="min-w-full">
                            <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Permissions</th>
                                <th class="text-left">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="role in roles" :key="role.id">
                                <td>{{ role.name }}</td>
                                <td>
                    <span v-for="perm in role.permissions" :key="perm.id" class="inline-block bg-gray-100 rounded px-2 py-1 text-xs mr-1 mb-1">
                      {{ perm.name }}
                    </span>
                                </td>
                                <td>
                                    <Link :href="`/admin/roles/${role.id}`" class="text-blue-600 hover:underline">View</Link>
                                    <Link :href="`/admin/roles/${role.id}/edit`" class="text-green-600 hover:underline ml-2">Edit</Link>
                                    <button @click="deleteRole(role.id)" class="text-red-600 hover:underline ml-2">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
    roles: Array
})

const deleteRole = (id) => {
    if (confirm('Are you sure you want to delete this role?')) {
        router.delete(`/admin/roles/${id}`)
    }
}
</script>
