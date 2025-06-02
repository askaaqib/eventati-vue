<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    show: Boolean,
    editingPermission: {
        type: Object,
        default: null
    }
})

const emit = defineEmits(['close', 'saved'])

const form = useForm({
    name: ''
})

watch(() => props.editingPermission, (newVal) => {
    form.name = newVal?.name || ''
})

const closeModal = () => {
    form.reset()
    emit('close')
}

const handleSubmit = () => {
    if (props.editingPermission) {
        form.put(`/admin/permissions/${props.editingPermission.id}`, {
            onSuccess: () => {
                closeModal()
                emit('saved')
            }
        })
    } else {
        form.post('/admin/permissions', {
            onSuccess: () => {
                closeModal()
                emit('saved')
            }
        })
    }
}
</script>

<template>
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg w-full max-w-md p-6 shadow-lg relative">
            <h2 class="text-lg font-bold mb-4">{{ editingPermission ? 'Edit Permission' : 'Create Permission' }}</h2>
            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Permission Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        id="name"
                        class="mt-1 block w-full border rounded-md shadow-sm px-3 py-2 focus:ring focus:ring-blue-300"
                        :class="{ 'border-red-500': form.errors.name }"
                        required
                    />
                    <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        {{ editingPermission ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
