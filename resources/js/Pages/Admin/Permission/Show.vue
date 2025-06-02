<script setup>
    import { Head, Link, router } from '@inertiajs/vue3'
    import AdminLayout from '@/Layouts/AdminLayout.vue'
    import {computed} from "vue";
    import Actions from "@/Layouts/Admin/Actions/View.vue";

    const props =  defineProps({
        permission: Object
    })

    const formTitle = 'View Permissions'

    const breadcrumbs = [
        { name: 'Permissions', href: '/admin/permissions' },
        { name: 'Permission Details', href: null }
    ]

    const deleteRecord = () => {
        router.delete(`/admin/permissions/${idToDelete.value}`);
    };
</script>

<template>
    <Head :title="`Permission: ${permission.name}`" />

    <AdminLayout :title="formTitle" :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Permission Information</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Name</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ permission.name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Created At</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ new Date(permission.created_at).toLocaleString() }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                                        <p class="mt-1 text-sm text-gray-900">
                                            {{ new Date(permission.updated_at).toLocaleString() }}
                                        </p>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- Actions properly styled and spaced -->
                        <div class="mt-6 pt-6">
                            <Actions
                                :model-id="permission.id"
                                :model-name="permission.name"
                                base-route="/admin/permissions"
                                @delete="deleteRecord"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
