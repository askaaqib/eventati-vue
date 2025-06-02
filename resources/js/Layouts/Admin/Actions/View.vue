<script setup>
    import { Link } from '@inertiajs/vue3';
    import ConfirmationDialogue from '@/Components/ConfirmationDialogue.vue';
    import { ref } from 'vue';

    const props = defineProps({
        modelId: {
            type: [String, Number],
            required: true,
        },
        modelName: {
            type: String,
            default: 'record',
        },
        baseRoute: {
            type: String,
            required: true,
        },
        showBack: {
            type: Boolean,
            default: true,
        },
        showEdit: {
            type: Boolean,
            default: true,
        },
        showDelete: {
            type: Boolean,
            default: true,
        },
        backLabel: {
            type: String,
            default: 'Back',
        },
        editLabel: {
            type: String,
            default: 'Edit',
        },
        deleteLabel: {
            type: String,
            default: 'Delete',
        },
    });

    const emit = defineEmits(['delete']);

    const confirmationDialog = ref(null);

    const openDeleteDialog = () => {
        confirmationDialog.value.open();
    };

    const handleConfirm = () => {
        emit('delete', props.modelId);
    };
</script>

<template>
    <div class="flex items-center justify-between border-t pt-4">
        <div v-if="showBack">
            <Link
                :href="baseRoute"
                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition"
            >
                {{ backLabel }}
            </Link>
        </div>
        <div v-else class="flex-1"></div>

        <div class="flex space-x-2">
            <Link
                v-if="showEdit && modelId"
                :href="`${baseRoute}/${modelId}/edit`"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition"
            >
                {{ editLabel }}
            </Link>
            <button
                v-if="showDelete && modelId"
                @click="openDeleteDialog"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition"
            >
                {{ deleteLabel }}
            </button>
        </div>
    </div>

    <ConfirmationDialogue
        v-if="showDelete"
        ref="confirmationDialog"
        title="Delete Confirmation"
        :message="`Are you sure you want to delete this ${modelName}? This action cannot be undone.`"
        @confirm="handleConfirm"
    />
</template>
