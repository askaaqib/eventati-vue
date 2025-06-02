<script setup>
    import { ref } from 'vue';
    import { Link, router, Head } from '@inertiajs/vue3';
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    import NavLink from '@/Components/NavLink.vue';
    import Sidebar from '@/Layouts/Admin/Sidebar.vue';
    import TopNav from '@/Layouts/Admin/TopNav.vue';
    import Breadcrumb from '@/Components/Breadcrumb.vue';

    const sidebarOpen = ref(true);
    const showingUserDropdown = ref(false);

    defineProps({
        title: String,
        breadcrumbs: {
            type: Array,
            default: () => []
        },
        flash: {
            type: Object,
            default: () => ({})
        }
    });

</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <Sidebar v-model:sidebarOpen="sidebarOpen" @toggleSidebar="sidebarOpen = !sidebarOpen" />

        <!-- Main Content -->
        <div
            class="transition-all duration-300"
            :class="{'ml-64': sidebarOpen, 'ml-0': !sidebarOpen}"
        >
            <!-- Top Navigation -->
            <TopNav v-model:sidebarOpen="sidebarOpen" />

            <!-- Page Content -->
            <main class="p-6">
                <!-- Success Flash Message -->
                <div
                    v-if="flash.success"
                    class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300"
                >
                    {{ flash.success }}
                </div>

                <!-- Page Header Card -->
                <div class="bg-white rounded-lg shadow-sm mb-6 p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <!-- Page Title -->
                        <h1 class="text-xl font-bold text-gray-800 mb-2 md:mb-0">{{ title }}</h1>

                        <!-- Breadcrumbs -->
                        <div class="flex items-center">
                            <Breadcrumb :items="breadcrumbs" />
                        </div>
                    </div>
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>
