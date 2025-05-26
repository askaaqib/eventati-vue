<script setup>
import { ref } from 'vue';
import NavLink from '@/Components/NavLink.vue';

const props = defineProps({
    title: String,
    icon: String, // SVG markup as string
    routes: Array, // [{ name: 'Add User', href: '/admin/users/create', icon: '<svg>...</svg>' }]
});

const open = ref(false);
</script>

<template>
    <div>
        <button
            @click="open = !open"
            class="w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-gray-100"
        >
            <span class="mr-3 h-5 w-5 text-gray-500" v-html="icon"></span>
            <span class="flex-1 text-left">{{ title }}</span>
            <svg
                class="h-4 w-4 text-gray-500 transform transition-transform"
                :class="{ 'rotate-90': open }"
                fill="none" viewBox="0 0 24 24" stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <div v-show="open" class="ml-8 mt-1 space-y-1">
            <NavLink
                v-for="route in routes"
                :key="route.href"
                :href="route.href"
                class="flex items-center px-3 py-2 text-sm rounded-md hover:bg-gray-100"
            >
                <span class="mr-3 h-4 w-4 text-gray-400" v-html="route.icon"></span>
                {{ route.name }}
            </NavLink>
        </div>
    </div>
</template>
