<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmationDialog from '@/Components/confirmationDialogue.vue';
import DataTable from '@/Components/DataTable.vue';
import DataTableActions from '@/Components/DataTableActions.vue';
import ModalForm from "@/Pages/Admin/Permission/ModalForm.vue";

const confirmationDialog = ref(null);
const showModal = ref(false)
const editingPermission = ref(null)
const idToDelete = ref(null);

const props = defineProps({
    permissions: Object, // Paginated collection
    filters: Object
});

const formTitle = 'Permissions';

const breadcrumbs = [
    { name: 'Permissions', href: '/admin/permissions' },
];

const deleteRecord = (id) => {
    idToDelete.value = id;
    confirmationDialog.value?.open();
};

const handleConfirm = async () => {
    if (!idToDelete.value) return;

    try {
        await router.delete(`/admin/permissions/${idToDelete.value}`);
    } catch (error) {
        console.error('Error deleting permission:', error);
        alert('An unexpected error occurred');
    }
};

const columns = [
    { key: 'name', label: 'Name', sortable: true },
    { key: 'created_at', label: 'Created At', sortable: true },
    { key: 'actions', label: 'Actions', sortable: false }
];

const openCreateModal = () => {
    editingPermission.value = null
    showModal.value = true
}

const editRecord = (permission) => {
    editingPermission.value = permission
    showModal.value = true
}

</script>

<template>
    <Head :title="formTitle" />

    <AdminLayout :title="formTitle" :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">Permission Management</h2>
                            <Link
                                @click="openCreateModal"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            >
                                Create New Permission
                            </Link>
                        </div>

                        <DataTable
                            :columns="columns"
                            :data="permissions"
                            :filters="filters"
                            @delete="deleteRecord"
                            :export-options="{ pdf: true, excel: true, csv: true, print: true }"
                            export-file-name="permissions-export"
                        >
                            <template #cell(created_at)="{ item }">
                                {{ new Date(item.created_at).toLocaleDateString() }}
                            </template>

                            <template #cell(actions)="{ item }">
                                <DataTableActions
                                    :model="item"
                                    base-route="/admin/permissions"
                                    :use-modal="true"
                                    @delete="deleteRecord"
                                    @edit="editRecord"
                                />
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmationDialog
            ref="confirmationDialog"
            title="Delete Permission"
            message="Are you sure you want to delete this permission? This action cannot be undone."
            @confirm="handleConfirm"
            @cancel="idToDelete.value = null"
        />

        <ModalForm
            :show="showModal"
            :editing-permission="editingPermission"
            @close="showModal = false"
            @saved="router.reload({ only: ['permissions'] })"
        />
    </AdminLayout>
</template>
