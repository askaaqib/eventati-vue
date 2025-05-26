<script setup>
    import { ref } from 'vue'

    const props = defineProps({
        title: {
            type: String,
            default: 'Are you sure?'
        },
        message: {
            type: String,
            default: 'This action cannot be undone.'
        }
    })

    const emit = defineEmits(['confirm', 'cancel'])

    const isOpen = ref(false)

    const open = () => {
        isOpen.value = true
    }

    const onConfirm = () => {
        isOpen.value = false
        emit('confirm')
    }

    const onCancel = () => {
        isOpen.value = false
        emit('cancel')
    }

    defineExpose({ open })
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ title }}</h3>
            <p class="text-gray-600 mb-6">{{ message }}</p>
            <div class="flex justify-end space-x-3">
                <button
                    @click="onCancel"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Cancel
                </button>
                <button
                    @click="onConfirm"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    Confirm
                </button>
            </div>
        </div>
    </div>
</template>
