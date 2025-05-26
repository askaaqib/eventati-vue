<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const showingUserDropdown = ref(false);
const toggleSidebar = defineModel('sidebarOpen', { type: Boolean });
</script>

<template>
    <div class="sticky top-0 z-40 bg-white border-b border-gray-200">
        <div class="flex items-center justify-between h-16 px-4">
            <!-- Toggle Sidebar -->
            <button
                @click="toggleSidebar = !toggleSidebar"
                class="text-gray-500 hover:text-gray-600"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="p-1 text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </button>

                <!-- User Dropdown -->
                <div class="relative">
                    <button
                        @click="showingUserDropdown = !showingUserDropdown"
                        class="flex items-center space-x-2 focus:outline-none"
                    >
                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
              <span class="text-gray-600 font-medium">
                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
              </span>
                        </div>
                        <svg
                            class="h-4 w-4 text-gray-400"
                            :class="{ 'transform rotate-180': showingUserDropdown }"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div
                        v-show="showingUserDropdown"
                        @click.away="showingUserDropdown = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                    >
                        <Link href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profile
                        </Link>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            Logout
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
