<script setup>
    import { computed } from 'vue'
    import { Head, Link, useForm } from '@inertiajs/vue3'
    import AdminLayout from '@/Layouts/AdminLayout.vue'

    const props = defineProps({
        permission: Object,
        type: String
    })

    const isEdit = computed(() => !!props.permission);

    const formTitle = computed(() => props.type === 'create' ? 'Create Permission' : 'Edit Permission')
    const breadcrumbs = computed(() => [
        { name: 'Permissions', href: '/admin/permissions' },
        { name: formTitle.value }
    ])

    const form = useForm({
        name: props.permission?.name || ''
    })

    const submit = () => {
        if (props.type === 'create') {
            form.post('/admin/permissions')
        } else {
            form.put(`/admin/permissions/${props.permission.id}`)
        }
    }
</script>

<template>
    <AdminLayout :title="formTitle" :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="py-6">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 bg-white border-b border-gray-200">
                                    <form @submit.prevent="submit">
                                        <div class="mb-6">
                                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                                Permission Name
                                            </label>
                                            <input
                                                v-model="form.name"
                                                id="name"
                                                type="text"
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                :class="{ 'border-red-500': form.errors.name }"
                                                required
                                            />
                                            <p v-if="form.errors.name" class="text-red-500 text-xs italic mt-1">
                                                {{ form.errors.name }}
                                            </p>
                                        </div>

                                        <div class="flex items-center justify-end">
                                            <button
                                                type="submit"
                                                class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                                :disabled="form.processing"
                                            >
                                                {{ type === 'create' ? 'Create Permission' : 'Update Permission' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
