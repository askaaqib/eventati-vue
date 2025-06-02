<script setup>
    import { Head, Link, router } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import AdminLayout from "@/Layouts/AdminLayout.vue";
    import Breadcrumb from '@/Components/Breadcrumb.vue';
    import ConfirmationDialogue from '@/Components/ConfirmationDialogue.vue';
    import Actions from '@/Layouts/Admin/Actions/View.vue';

    const formTitle = "View Role";

    const props = defineProps({
        role: {
            type: Object,
            default: () => ({}),
        },
    });

    const breadcrumbs = [
        { name: 'Roles', href: '/admin/roles' },
        { name: formTitle, href: null },
    ];

    // Confirmation dialog setup
    const confirmationDialog = ref(null);
    const idToDelete = ref(null);

    const deleteRecord = () => {
        router.delete(`/admin/roles/${idToDelete.value}`);
    };

    const handleConfirm = () => {
        if (idToDelete.value) {
            deleteRecord();
        }
    };

</script>

<template>
    <AdminLayout :title="formTitle" :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Role Name -->
                        <h1 class="text-2xl font-semibold mb-2">{{ role.name }}</h1>

                        <!-- Permissions Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Permissions:</h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="perm in role.permissions"
                                    :key="perm.id"
                                    class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm"
                                >
                                    {{ perm.name }}
                                </span>
                                <span
                                    v-if="role.permissions.length === 0"
                                    class="text-gray-500 text-sm"
                                >
                                    No permissions assigned
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <Actions
                            :model-id="role.id"
                            :model-name="role.name"
                            base-route="/admin/roles"
                            @delete="deleteRecord"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
