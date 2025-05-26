<script setup>
import NavLink from '@/Components/NavLink.vue';
import SidebarModule from './SidebarModule.vue';

const sidebarOpen = defineModel('sidebarOpen', { type: Boolean });
</script>

<template>
    <div
        class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-50 text-gray-800 border-r border-gray-200 transition-all duration-300"
        :class="{ 'ml-0': sidebarOpen, '-ml-64': !sidebarOpen }"
    >
        <!-- Header -->
        <div class="flex items-center justify-between h-16 px-4 bg-white border-b border-gray-200">
            <NavLink href="/admin/dashboard" class="text-lg font-semibold text-gray-800">
                Admin Panel
            </NavLink>
            <button @click="$emit('toggleSidebar')" class="text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="px-2 py-4 space-y-1">
            <!-- Dashboard -->
            <NavLink
                href="/admin/dashboard"
                :active="route().current('admin.dashboard')"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-md hover:bg-gray-100"
            >
                <svg class="mr-3 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </NavLink>

            <!-- Users -->
            <SidebarModule
                title="Users"
                icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z" /></svg>'
                :routes="[
                    {
                        name: 'Add User',
                        href: '/admin/users/create',
                        icon: '<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\' /></svg>'
                    },
                    {
                        name: 'View Users',
                        href: '/admin/users',
                        icon: '<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 6h16M4 12h16M4 18h16\' /></svg>'
                    }
                ]"
            />

            <!-- Roles -->
            <SidebarModule
                title="Roles"
                icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" /></svg>'
                :routes="[
                    {
                        name: 'Add Role',
                        href: '/admin/roles/create',
                        icon: '<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\' /></svg>'
                    },
                    {
                        name: 'View Roles',
                        href: '/admin/roles',
                        icon: '<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 6h16M4 12h16M4 18h16\' /></svg>'
                    }
                ]"
            />

            <!-- Permissions -->
            <SidebarModule
                title="Permissions"
                icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg>'
                :routes="[
                    {
                        name: 'Add Permission',
                        href: '/admin/permissions/create',
                        icon: '<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4v16m8-8H4\' /></svg>'
                    },
                    {
                        name: 'View Permissions',
                        href: '/admin/permissions',
                        icon: '<svg fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 6h16M4 12h16M4 18h16\' /></svg>'
                    }
                ]"
            />
        </nav>
    </div>
</template>
