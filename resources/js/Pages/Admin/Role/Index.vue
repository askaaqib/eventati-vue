<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmationDialog from '@/Components/confirmationDialogue.vue';
import DataTable from '@/Components/DataTable.vue';
import DataTableActions from '@/Components/DataTableActions.vue';

const confirmationDialog = ref(null);
const idToDelete = ref(null);

const formTitle = 'View Roles';

const props = defineProps({
    roles: Object, // paginated collection expected
    filters: Object,
});

const deleteRecord = (id) => {
    idToDelete.value = id;
    confirmationDialog.value?.open();
};

const handleConfirm = async () => {
    if (!idToDelete.value) return;

    try {
        await router.delete(`/admin/roles/${idToDelete.value}`);
    } catch (error) {
        console.error('Error deleting role:', error);
        alert('An unexpected error occurred.');
    }
};

const breadcrumbs = [
    { name: 'Roles', href: '/admin/roles' },
];

const columns = [
    { key: 'name', label: 'Name', sortable: true },
    { key: 'permissions', label: 'Permissions', sortable: false },
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
                            <h2 class="text-xl font-semibold text-gray-800">Role Management</h2>
                            <Link
                                href="/admin/roles/create"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            >
                                Create New Role
                            </Link>
                        </div>

                        <DataTable
                            :columns="columns"
                            :data="roles"
                            :filters="filters"
                            @delete="deleteRecord"
                            :export-options="{ pdf: true, excel: true, csv: true, print: true }"
                            export-file-name="roles-export"
                        >
                            <template #cell(permissions)="{ item }">
                                <div class="flex flex-wrap">
                                    <span
                                        v-for="perm in item.permissions"
                                        :key="perm.id"
                                        class="inline-block bg-gray-100 text-xs rounded px-2 py-1 mr-1 mb-1"
                                    >
                                        {{ perm.name }}
                                    </span>
                                </div>
                            </template>

                            <template #cell(actions)="{ item }">
                                <DataTableActions
                                    :model="item"
                                    base-route="/admin/roles"
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
            title="Delete Role"
            message="Are you sure you want to delete this role? This action cannot be undone."
            @confirm="handleConfirm"
            @cancel="idToDelete.value = null"
        />
    </AdminLayout>
</template>
