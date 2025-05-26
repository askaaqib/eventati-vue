<script setup>
    import { ref } from 'vue'
    import {Head, Link, router} from '@inertiajs/vue3';
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
    import ConfirmationDialog from '@/Components/confirmationDialogue.vue'
    import AdminLayout from "@/Layouts/AdminLayout.vue";
    import Breadcrumb from "@/Components/Breadcrumb.vue";

    const confirmationDialog = ref(null)
    const userIdToDelete = ref(null)

    defineProps({
        users: {
            type: Array,
            default: () => []
        }
    });

    const deleteUser = (userId) => {
        if (!confirmationDialog.value) {
            console.error('Confirmation dialog ref is not available')
            return
        }

        userIdToDelete.value = userId
        confirmationDialog.value.open()
    }

    const handleConfirm = async () => {
        if (!userIdToDelete.value) return

        try {
            await router.delete(`/admin/users/${userIdToDelete.value}`, {
                onSuccess: () => {
                    // Inertia will handle the page refresh automatically
                },
                onError: (errors) => {
                    console.error('Error deleting user:', errors)
                    alert('Failed to delete user. Please try again.')
                }
            })
        } catch (error) {
            console.error('Error:', error)
            alert('An unexpected error occurred')
        }
    }

    const breadcrumbItems = [
        { name: 'Users', href: '/admin/users' },
    ];

</script>


<template>
    <AdminLayout>
        <template #breadcrumbs>
            <Breadcrumb :items="breadcrumbItems" />
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <table class="min-w-full mt-4">
                            <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Email</th>
                                <th class="text-left">Role</th>
                                <th class="text-left">Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.roles[0]?.name || '—' }}</td>
                                <td>
                                    <Link :href="`/admin/users/${user.id}`" class="text-blue-600 hover:underline">View</Link>
                                    <Link :href="`/admin/users/${user.id}/edit`" class="text-green-600 hover:underline ml-2">Edit</Link>
                                    <button @click="deleteUser(user.id)" class="text-red-600 hover:underline ml-2">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add the ConfirmationDialog component -->
        <ConfirmationDialog
            ref="confirmationDialog"
            title="Delete User"
            message="Are you sure you want to delete this user? This action cannot be undone."
            @confirm="handleConfirm"
            @cancel="userIdToDelete.value = null"
        />
    </AdminLayout>


</template>

