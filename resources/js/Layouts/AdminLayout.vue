<script setup>
    import { ref } from 'vue';
    import { Link, router, Head } from '@inertiajs/vue3';
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    import NavLink from '@/Components/NavLink.vue';
    import Sidebar from '@/Layouts/Admin/Sidebar.vue';
    import TopNav from '@/Layouts/Admin/TopNav.vue';

    const sidebarOpen = ref(true);
    const showingUserDropdown = ref(false);


    defineProps({
        title: String,
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
                <!-- Page Head and Breadcrumb -->
                <Head :title="title" />
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-800 mb-2">{{ title }}</h1>
                    <slot name="breadcrumbs" />
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>
