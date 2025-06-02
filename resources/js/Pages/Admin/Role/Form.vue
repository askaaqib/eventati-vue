<script setup>
    import { computed } from 'vue'
    import {Head, Link, router, useForm} from '@inertiajs/vue3'
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
    import AdminLayout from "@/Layouts/AdminLayout.vue";
    import Breadcrumb from "@/Components/Breadcrumb.vue";
    import Actions from "@/Layouts/Admin/Actions/View.vue";

    const props = defineProps({
        role: Object,
        permissions: Array,
        type: String
    })

    const formTitle = computed(() => props.type === 'create' ? 'Create Role' : 'Edit Role')

    const form = useForm({
        name: props.role?.name || '',
        permissions: props.role?.permissions?.map(p => p.name) || []
    })

    const submit = () => {
        if (props.type === 'create') {
            form.post('/admin/roles')
        } else {
            form.put(`/admin/roles/${props.role.id}`)
        }
    }

    const breadcrumbs = [
        { name: 'Roles', href: '/admin/roles' },
        { name: formTitle, href: null },
    ];

    const deleteRecord = () => {
        router.delete(`/admin/roles/${idToDelete.value}`);
    };
</script>

<template>
    <AdminLayout :title="formTitle" :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label class="block mb-1">Name</label>
                                <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded" required />
                            </div>

                            <div class="mb-4">
                                <label class="block mb-1">Permissions</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label v-for="permission in permissions" :key="permission.id" class="flex items-center">
                                        <input type="checkbox" v-model="form.permissions" :value="permission.name" class="mr-2" />
                                        {{ permission.name }}
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                                {{ type === 'create' ? 'Create' : 'Update' }}
                            </button>
                        </form>
                    </div>

                    <!-- Action Buttons -->
                    <Actions
                        :model-id="role?.id"
                        :model-name="role?.name"
                        base-route="/admin/roles"
                        @delete="deleteRecord"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

