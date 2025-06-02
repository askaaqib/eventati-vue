<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Breadcrumb from '@/Components/Breadcrumb.vue';
import ConfirmationDialogue from '@/Components/ConfirmationDialogue.vue';
import Actions from "@/Layouts/Admin/Actions/View.vue";

const formTitle = "View User";

const props = defineProps({
    user: {
        type: Object,
        default: () => ({}),
    },
});

const breadcrumbs = [
    { name: 'Users', href: '/admin/users' },
    { name: 'View', href: null },
];

// Confirmation dialog setup
const confirmationDialog = ref(null);
const idToDelete = ref(null);

const deleteRecord = () => {
    router.delete(`/admin/users/${idToDelete.value}`);
};

const handleConfirm = () => {
    if (idToDelete.value) {
        deleteRecord();
    }
};

const openDeleteDialog = (id) => {
    idToDelete.value = id;
    confirmationDialog.value.open();
};
</script>

<template>
    <AdminLayout :title="formTitle" :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h1 class="text-2xl font-semibold mb-2">{{ user.name }}</h1>
                        <p class="mb-2 text-gray-600">{{ user.email }}</p>
                        <p class="mb-4">
                            Role:
                            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">
                                {{ user.roles[0]?.name ?? '—' }}
                            </span>
                        </p>

                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Permissions:</h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="perm in user.permissions"
                                    :key="perm.id"
                                    class="inline-block bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded"
                                >
                                    {{ perm.name }}
                                </span>
                                <span
                                    v-if="user.permissions.length === 0"
                                    class="text-gray-500 text-sm"
                                >
                                    No permissions assigned
                                </span>
                            </div>
                        </div>

                        <Actions
                            :model-id="user.id"
                            :model-name="user.name"
                            base-route="/admin/users"
                            @delete="deleteRecord"
                        />
                    </div>
                </div>
            </div>
        </div>

        <ConfirmationDialogue
            ref="confirmationDialog"
            title="Delete User"
            message="Are you sure you want to delete this user? This action cannot be undone."
            @confirm="handleConfirm"
        />
    </AdminLayout>
</template>
