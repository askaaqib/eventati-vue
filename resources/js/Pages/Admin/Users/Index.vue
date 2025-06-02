<script setup>
    import { ref } from 'vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import AdminLayout from "@/Layouts/AdminLayout.vue";
    import ConfirmationDialog from '@/Components/confirmationDialogue.vue';
    import DataTable from "@/Components/DataTable.vue";
    import DataTableActions from "@/Components/DataTableActions.vue";

    const confirmationDialog = ref(null);
    const idToDelete = ref(null);

    const formTitle = "Users";

    const props = defineProps({
        users: Object, // Now expecting a paginated collection
        filters: Object,
    });

    const deleteRecord = (userId) => {
        idToDelete.value = userId;
        confirmationDialog.value.open(); // triggers centralized dialog
    };

    const handleConfirm = async () => {
        if (!idToDelete.value) return;

        try {
            await router.delete(`/admin/users/${idToDelete.value}`);
        } catch (error) {
            console.error('Error:', error);
            alert('An unexpected error occurred');
        }
    };

    const breadcrumbs = [
        { name: 'Users', href: '/admin/users' },
    ];

    const columns = [
        { key: 'name', label: 'Name', sortable: true },
        { key: 'email', label: 'Email', sortable: true },
        { key: 'role', label: 'Role', sortable: false },
        { key: 'actions', label: 'Actions', sortable: false },
    ];
</script>

<template>
    <Head :title="formTitle" />

    <AdminLayout :title="formTitle" :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">User Management</h2>
                            <Link
                                href="/admin/users/create"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            >
                                Create New User
                            </Link>
                        </div>

                        <DataTable
                            :columns="columns"
                            :data="users"
                            :filters="filters"
                            @delete="deleteRecord"
                            :export-options="{
                                pdf: true,
                                excel: true,
                                csv: true,
                                print: true
                              }"
                            export-file-name="users-export"
                        >
                            <template #cell(role)="{ item }">
                                {{ item.roles[0]?.name || '—' }}
                            </template>
                            <template #cell(actions)="{ item }">
                                <DataTableActions
                                    :model="item"
                                    base-route="/admin/users"
                                    @delete="deleteRecord"
                                />
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmationDialog
            ref="confirmationDialog"
            title="Delete User"
            message="Are you sure you want to delete this user? This action cannot be undone."
            @confirm="handleConfirm"
            @cancel="idToDelete.value = null"
        />
    </AdminLayout>
</template>
