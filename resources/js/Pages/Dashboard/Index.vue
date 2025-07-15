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

const cards = [
    {
        title: "Daily Sales",
        amount: "$249.95",
        percent: 67,
        trend: "up",
        bars: [
            {
                label: "Progress",
                value: "67%",
                width: 67,
                color: "#14b8a6", // teal-500
            },
        ],
    },
    {
        title: "Monthly Sales",
        amount: "$2,942.32",
        percent: 36,
        trend: "down",
        bars: [
            {
                label: "Progress",
                value: "36%",
                width: 36,
                color: "#a855f7", // purple-500
            },
        ],
    },
    {
        title: "Yearly Sales",
        amount: "$8,638.32",
        percent: 80,
        trend: "up",
        bars: [
            {
                label: "Progress",
                value: "80%",
                width: 80,
                color: "#14b8a6", // teal-500
            },
        ],
    },
    {
        title: "Facebook Likes",
        amount: "12,281",
        percent: 7.2,
        trend: "up",
        icon: "fab fa-facebook-f",
        iconColor: "text-blue-600",
        bars: [
            {
                label: "Target",
                value: "35,098",
                width: 35,
                color: "#14b8a6",
            },
            {
                label: "Duration",
                value: "350",
                width: 35,
                color: "#a855f7",
            },
        ],
    },
    {
        title: "Twitter Likes",
        amount: "11,200",
        percent: 6.2,
        trend: "up",
        icon: "fab fa-twitter",
        iconColor: "text-sky-500",
        bars: [
            {
                label: "Target",
                value: "34,185",
                width: 34,
                color: "#14b8a6",
            },
            {
                label: "Duration",
                value: "800",
                width: 80,
                color: "#0ea5e9",
            },
        ],
    },
    {
        title: "Google+ Likes",
        amount: "10,500",
        percent: 5.9,
        trend: "up",
        icon: "fab fa-google-plus-g",
        iconColor: "text-red-600",
        bars: [
            {
                label: "Target",
                value: "25,998",
                width: 26,
                color: "#14b8a6",
            },
            {
                label: "Duration",
                value: "900",
                width: 90,
                color: "#a855f7",
            },
        ],
    },
];
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

const ratingCounts = {
    5: 384,
    4: 145,
    3: 24,
    2: 1,
    1: 0,
};

const ratingWidths = {
    5: 76,
    4: 45,
    3: 18,
    2: 4,
    1: 0,
};

const recentEmployees = [
    {
        name: "Isabella Christensen",
        desc: "Lorem Ipsum is simply dummy text of...",
        date: "11 MAY 12:56",
        online: true,
        avatar: "https://i.pravatar.cc/40?img=1",
    },
    {
        name: "Mathilde Andersen",
        desc: "Lorem Ipsum is simply dummy text of...",
        date: "11 MAY 10:35",
        online: false,
        avatar: "https://i.pravatar.cc/40?img=2",
    },
    {
        name: "Karla Sorensen",
        desc: "Lorem Ipsum is simply dummy text of...",
        date: "9 MAY 17:38",
        online: true,
        avatar: "https://i.pravatar.cc/40?img=3",
    },
    {
        name: "Ida Jorgensen",
        desc: "Lorem Ipsum is simply dummy text of...",
        date: "19 MAY 12:56",
        online: false,
        avatar: "https://i.pravatar.cc/40?img=4",
    },
    {
        name: "Albert Andersen",
        desc: "Lorem Ipsum is simply dummy text of...",
        date: "21 JULY 12:56",
        online: true,
        avatar: "https://i.pravatar.cc/40?img=5",
    },
];
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Dashboard Overview</h1>

            <!-- Stats Cards -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10"
            >
                <!-- Card Component Template -->
                <div
                    v-for="card in cards"
                    :key="card.title"
                    class="bg-white shadow-md p-6 space-y-4"
                >
                    <div class="flex items-center justify-between">
                        <div class="space-y-5">
                            <p class="text-sm text-gray-500 font-medium">
                                {{ card.title }}
                            </p>
                            <div class="flex items-center gap-2">
                                <span
                                    :class="[
                                        'text-2xl font-bold',
                                        card.trend === 'up'
                                            ? 'text-green-600'
                                            : 'text-red-500',
                                    ]"
                                >
                                    {{ card.trend === "up" ? "↑" : "↓" }}
                                    {{ card.amount }}
                                </span>
                                <span class="text-sm text-gray-400"
                                    >{{ card.percent }}%</span
                                >
                            </div>
                        </div>
                        <div
                            v-if="card.icon"
                            class="text-3xl"
                            :class="card.iconColor"
                        >
                            <i :class="card.icon"></i>
                        </div>
                    </div>

                    <div v-if="card.bars?.length">
                        <div
                            :class="[
                                'grid gap-2',
                                card.bars.length === 1
                                    ? 'grid-cols-1'
                                    : 'grid-cols-1 sm:grid-cols-2',
                            ]"
                        >
                            <div
                                v-for="(bar, i) in card.bars"
                                :key="i"
                                class="flex flex-col gap-1"
                            >
                                <div
                                    class="flex justify-between text-xs text-gray-500 font-medium"
                                >
                                    <span>{{ bar.label }}</span>
                                    <span>{{ bar.value }}</span>
                                </div>
                                <div
                                    class="w-full bg-gray-200 rounded-full h-2"
                                >
                                    <div
                                        class="h-2 rounded-full"
                                        :style="`width: ${bar.width}%; background-color: ${bar.color};`"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Bar Chart -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Monthly Sales</h2>
                    <canvas ref="barChartRef" height="250"></canvas>
                </div>

                <!-- Pie Chart -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Status Breakdown</h2>
                    <canvas ref="pieChartRef" height="250"></canvas>
                </div>

                <!-- Line Chart -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Visitors Trend</h2>
                    <canvas ref="lineChartRef" height="250"></canvas>
                </div>

                <!-- Doughnut Chart -->
                <div class="bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold mb-4">Device Usage</h2>
                    <canvas ref="doughnutChartRef" height="250"></canvas>
                </div>
            </div>

            <!-- Employees Records -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Ratings Box -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Rating</h2>
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-5xl font-bold">
                            4.7 <span class="text-yellow-400">★</span>
                        </div>
                        <div
                            class="text-sm text-green-500 font-semibold flex items-center gap-1"
                        >
                            0.4 <span>▲</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div
                            v-for="star in [5, 4, 3, 2, 1]"
                            :key="star"
                            class="flex items-center gap-2"
                        >
                            <span class="w-4 text-yellow-400 text-sm">★</span>
                            <span class="w-6 text-sm text-gray-700">{{
                                star
                            }}</span>
                            <div
                                class="w-full bg-gray-200 rounded-full h-2 shadow-inner"
                            >
                                <div
                                    class="h-2 rounded-full bg-teal-400"
                                    :style="{ width: ratingWidths[star] + '%' }"
                                ></div>
                            </div>
                            <span
                                class="text-sm text-gray-600 w-10 text-right"
                                >{{ ratingCounts[star] }}</span
                            >
                        </div>
                    </div>
                </div>

                <!-- Recent Employees Box -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Employees</h2>
                    <ul class="divide-y divide-gray-100">
                        <li
                            v-for="(user, index) in recentEmployees"
                            :key="index"
                            class="py-4 flex items-center justify-between"
                        >
                            <div class="flex items-center gap-3">
                                <img
                                    :src="user.avatar"
                                    alt="avatar"
                                    class="w-10 h-10 rounded-full"
                                />
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ user.name }}
                                    </p>
                                    <p
                                        class="text-sm text-gray-500 truncate max-w-[200px]"
                                    >
                                        {{ user.desc }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-2 h-2 rounded-full"
                                    :class="
                                        user.online
                                            ? 'bg-green-500'
                                            : 'bg-red-500'
                                    "
                                ></span>
                                <span class="text-sm text-gray-500">{{
                                    user.date
                                }}</span>
                                <button
                                    class="bg-purple-200 text-purple-800 px-3 py-1 rounded-full text-xs"
                                >
                                    Reject
                                </button>
                                <button
                                    class="bg-teal-400 text-white px-3 py-1 rounded-full text-xs"
                                >
                                    Approve
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
