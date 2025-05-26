<script setup>
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Bar, Pie, Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement);

const barChartData = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
        {
            label: 'Users',
            backgroundColor: '#3B82F6',
            data: [40, 20, 12, 39, 10, 40, 39]
        },
        {
            label: 'Posts',
            backgroundColor: '#10B981',
            data: [30, 25, 40, 20, 25, 35, 20]
        }
    ]
};

const pieChartData = {
    labels: ['Admin', 'Editor', 'User'],
    datasets: [
        {
            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B'],
            data: [30, 40, 30]
        }
    ]
};

const doughnutChartData = {
    labels: ['Read', 'Write', 'Delete'],
    datasets: [
        {
            backgroundColor: ['#3B82F6', '#10B981', '#EF4444'],
            data: [50, 30, 20]
        }
    ]
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Users Card -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Users</p>
                            <p class="text-2xl font-semibold text-gray-900">1,234</p>
                        </div>
                    </div>
                </div>

                <!-- Roles Card -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Roles</p>
                            <p class="text-2xl font-semibold text-gray-900">12</p>
                        </div>
                    </div>
                </div>

                <!-- Permissions Card -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Permissions</p>
                            <p class="text-2xl font-semibold text-gray-900">45</p>
                        </div>
                    </div>
                </div>

                <!-- Posts Card -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Posts</p>
                            <p class="text-2xl font-semibold text-gray-900">567</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Bar Chart -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User & Post Growth</h3>
                    <Bar :data="barChartData" :options="{ responsive: true }" />
                </div>

                <!-- Pie Chart -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">User Roles Distribution</h3>
                    <Pie :data="pieChartData" :options="{ responsive: true }" />
                </div>

                <!-- Doughnut Chart -->
                <div class="p-6 bg-white rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions Distribution</h3>
                    <Doughnut :data="doughnutChartData" :options="{ responsive: true }" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
