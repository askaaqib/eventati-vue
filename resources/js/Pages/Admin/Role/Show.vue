<template>
    <Head title="Role Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Role: {{ role.name }}</h2>
            <Link href="/admin/roles" class="bg-blue-500 text-white px-4 py-2 rounded float-right">Back to Roles</Link>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Permissions:</h3>
                        <div class="flex flex-wrap gap-2">
              <span v-for="perm in role.permissions" :key="perm.id" class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                {{ perm.name }}
              </span>
                        </div>

                        <div class="mt-6">
                            <Link :href="`/admin/roles/${role.id}/edit`" class="bg-green-500 text-white px-4 py-2 rounded mr-2">Edit</Link>
                            <button @click="deleteRole(role.id)" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                        </div>
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
    role: Object
})

const deleteRole = (id) => {
    if (confirm('Are you sure you want to delete this role?')) {
        router.delete(`/admin/roles/${id}`)
    }
}
</script>
