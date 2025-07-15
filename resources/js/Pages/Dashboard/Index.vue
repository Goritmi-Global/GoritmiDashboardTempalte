<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

// Chart refs
const barChartRef = ref(null);
const pieChartRef = ref(null);
const lineChartRef = ref(null);
const doughnutChartRef = ref(null);

onMounted(() => {
    // Bar Chart
    new Chart(barChartRef.value, {
        type: "bar",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
            datasets: [
                {
                    label: "Sales",
                    data: [120, 190, 300, 500, 200, 300],
                    backgroundColor: "#1f5b99",
                },
            ],
        },
    });

    // Pie Chart
    new Chart(pieChartRef.value, {
        type: "pie",
        data: {
            labels: ["Completed", "Pending", "Failed"],
            datasets: [
                {
                    data: [60, 25, 15],
                    backgroundColor: ["#22c55e", "#f59e0b", "#ef4444"],
                },
            ],
        },
    });

    // Line Chart
    new Chart(lineChartRef.value, {
        type: "line",
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri"],
            datasets: [
                {
                    label: "Visitors",
                    data: [10, 40, 30, 60, 50],
                    borderColor: "#3b82f6",
                    backgroundColor: "rgba(59, 130, 246, 0.1)",
                    fill: true,
                },
            ],
        },
    });

    // Doughnut Chart
    new Chart(doughnutChartRef.value, {
        type: "doughnut",
        data: {
            labels: ["Mobile", "Desktop", "Tablet"],
            datasets: [
                {
                    data: [50, 35, 15],
                    backgroundColor: ["#14b8a6", "#8b5cf6", "#f43f5e"],
                },
            ],
        },
    });
});
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard Overview</h1>

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">Total Sales</h2>
                    <p class="text-2xl font-bold">$24,300</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">New Users</h2>
                    <p class="text-2xl font-bold">320</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">Orders</h2>
                    <p class="text-2xl font-bold">1,240</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">Bounce Rate</h2>
                    <p class="text-2xl font-bold">34%</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">Revenue</h2>
                    <p class="text-2xl font-bold">$87,900</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-2">Page Views</h2>
                    <p class="text-2xl font-bold">45,000</p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Monthly Sales</h2>
                    <canvas ref="barChartRef" height="250"></canvas>
                </div>

                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Status Breakdown</h2>
                    <canvas ref="pieChartRef" height="250"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Visitors Trend</h2>
                    <canvas ref="lineChartRef" height="250"></canvas>
                </div>

                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Device Usage</h2>
                    <canvas ref="doughnutChartRef" height="250"></canvas>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
