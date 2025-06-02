<script setup>
    import { ref, computed } from 'vue';
    import { Head, Link, useForm, router } from "@inertiajs/vue3";
    import AdminLayout from "@/Layouts/AdminLayout.vue";
    import Breadcrumb from '@/Components/Breadcrumb.vue';
    import Actions from "@/Layouts/Admin/Actions/View.vue";

    const props = defineProps({
        roles: {
            type: Array,
            default: () => []
        },
        permissions: {
            type: Array,
            default: () => []
        },
        user: {
            type: Object,
            default: null
        },
        errors: {
            type: Object,
            default: () => ({})
        },
        flash : {
            type: Object,
            default: () => ({})
        }
    });

    const isEdit = computed(() => !!props.user);

    const formTitle = computed(() => isEdit.value ? 'Edit User' : 'Create User');

    const deleteRecord = () => {
        router.delete(`/admin/users/${idToDelete.value}`);
    };

    const form = useForm({
        name: props.user?.name || '',
        email: props.user?.email || '',
        password: '',
        role: props.user?.roles[0]?.name || '',
        permissions: props.user?.permissions?.map(p => p.name) || []
    });

    const submit = () => {
        if (isEdit.value) {
            form.put(`/admin/users/${props.user.id}`);
        } else {
            form.post('/admin/users');
        }
    };

    const breadcrumbs = [
        { name: 'Users', href: '/admin/users' },
        { name: formTitle, href: null },
    ];
</script>


<template>
    <AdminLayout :title="formTitle" :breadcrumbs="breadcrumbs" :flash="flash">
        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block mb-1 font-medium text-sm text-gray-700">Name</label>
                                <input v-model="form.name" type="text" class="w-full border px-3 py-2 rounded" required />
                                <div v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</div>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block mb-1 font-medium text-sm text-gray-700">Email</label>
                                <input v-model="form.email" type="email" class="w-full border px-3 py-2 rounded" required />
                                <div v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</div>
                            </div>

                            <!-- Password (only for create) -->
                            <div v-if="!isEdit">
                                <label class="block mb-1 font-medium text-sm text-gray-700">Password</label>
                                <input v-model="form.password" type="password" class="w-full border px-3 py-2 rounded" />
                                <div v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</div>
                            </div>

                            <!-- Role -->
                            <div>
                                <label class="block mb-1 font-medium text-sm text-gray-700">Role</label>
                                <select v-model="form.role" class="w-full border px-3 py-2 rounded">
                                    <option disabled value="">Select Role</option>
                                    <option v-for="role in roles" :key="role.id" :value="role.name">{{ role.name }}</option>
                                </select>
                                <div v-if="errors.role" class="text-red-500 text-sm mt-1">{{ errors.role }}</div>
                            </div>


                            <!-- Submit Button (full width) -->
                            <div class="lg:col-span-2">
                                <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded">
                                    {{ isEdit ? 'Update' : 'Create' }}
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </AdminLayout>
</template>
