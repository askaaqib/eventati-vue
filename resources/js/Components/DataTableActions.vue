<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

// Define props with sensible defaults
const props = defineProps({
    model: {
        type: Object,
        required: true
    },
    baseRoute: {
        type: String,
        required: true
    },
    canView: {
        type: Boolean,
        default: true
    },
    canEdit: {
        type: Boolean,
        default: true
    },
    canDelete: {
        type: Boolean,
        default: true
    },
    size: {
        type: String,
        default: 'md' // sm, md, lg
    },
    variant: {
        type: String,
        default: 'solid' // solid, outline, ghost
    },
    useModal: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['delete']);

// Emit delete request to parent
const handleDeleteClick = () => {
    emit('delete', props.model.id);
};

// Tailwind utility classes for buttons
const buttonClasses = computed(() => {
    const sizes = {
        sm: 'px-2.5 py-1 text-xs',
        md: 'px-3 py-1.5 text-sm',
        lg: 'px-4 py-2 text-base'
    };

    const variants = {
        solid: {
            base: 'text-white shadow-sm',
            view: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
            edit: 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
            delete: 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
        },
        outline: {
            base: 'bg-white border shadow-sm',
            view: 'text-blue-600 border-blue-300 hover:bg-blue-50 focus:ring-blue-200',
            edit: 'text-green-600 border-green-300 hover:bg-green-50 focus:ring-green-200',
            delete: 'text-red-600 border-red-300 hover:bg-red-50 focus:ring-red-200'
        },
        ghost: {
            base: 'bg-transparent',
            view: 'text-blue-600 hover:bg-blue-100 focus:ring-blue-200',
            edit: 'text-green-600 hover:bg-green-100 focus:ring-green-200',
            delete: 'text-red-600 hover:bg-red-100 focus:ring-red-200'
        }
    };

    const variant = variants[props.variant] || variants.solid;

    return {
        base: `inline-flex items-center rounded-md font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 ${sizes[props.size]}`,
        view: `${variant.base} ${variant.view}`,
        edit: `${variant.base} ${variant.edit}`,
        delete: `${variant.base} ${variant.delete}`
    };
});
</script>

<template>
    <div class="flex items-center space-x-2">
        <!-- View -->
        <Link
            v-if="canView"
            :href="`${baseRoute}/${model.id}`"
            :class="[buttonClasses.base, buttonClasses.view]"
        >
            View
        </Link>

        <button
            v-if="canEdit && useModal"
            @click="$emit('edit', model)"
            :class="[buttonClasses.base, buttonClasses.edit]"
        >
            Edit
        </button>

        <!-- Edit -->
        <Link
            v-else-if="canEdit"
            :href="`${baseRoute}/${model.id}/edit`"
            :class="[buttonClasses.base, buttonClasses.edit]"
        >
            Edit
        </Link>

        <!-- Delete -->
        <button
            v-if="canDelete"
            @click="handleDeleteClick"
            :class="[buttonClasses.base, buttonClasses.delete]"
        >
            Delete
        </button>
    </div>
</template>
