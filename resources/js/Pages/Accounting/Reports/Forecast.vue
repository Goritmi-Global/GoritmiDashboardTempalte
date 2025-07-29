<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    forecastAmount: Number,
    salary: Number,
    netProfit: Number,
    totalIncome: Number,
    totalExpenses: Number,
});

const currentMonth = new Date().toLocaleString("default", { month: "long" });
const nextMonth = new Date(
    new Date().setMonth(new Date().getMonth() + 1)
).toLocaleString("default", { month: "long" });
</script>

<template>
    <AppLayout title="Forecasting">
        <Head title="Expense Forecasting" />

        <div class="px-4 py-6 space-y-6">
            <!-- Header & Breadcrumb -->
            <div class="flex justify-between items-center">
                <div class="space-y-1">
                    <h1 class="text-2xl font-bold text-gray-800">
                        Expense Forecasting Report
                    </h1>
                    <p class="text-sm text-gray-500">
                        Based on historical expense trends and salaries
                    </p>

                    <nav class="flex text-sm mt-1" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-2"
                        >
                            <li class="inline-flex items-center">
                                <Link
                                    href="/"
                                    class="text-gray-700 hover:text-blue-600 inline-flex items-center"
                                >
                                    <svg
                                        class="w-3 h-3 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                                        />
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg
                                        class="w-3 h-3 text-gray-400 mx-1"
                                        fill="none"
                                        viewBox="0 0 6 10"
                                    >
                                        <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m1 9 4-4-4-4"
                                        />
                                    </svg>
                                    <span class="text-gray-500"
                                        >Forecasting</span
                                    >
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Forecast Summary -->
            <div class="bg-white shadow-lg rounded-4 p-6 space-y-4 border">
                <h2 class="text-xl font-semibold text-gray-800">
                    Forecast Overview for
                    <span class="text-primary">{{ nextMonth }}</span>
                </h2>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-4"
                >
                    <div class="bg-gray-50 p-4 rounded-3 shadow-sm border">
                        <p
                            class="text-sm text-gray-500"
                            title="Based on average of last 3 months expenses"
                        >
                            Avg. Expense (Last 3 Months)
                        </p>
                        <p class="text-xl font-semibold text-red-600">
                            Rs. {{ (forecastAmount - salary).toLocaleString() }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-3 shadow-sm border">
                        <p class="text-sm text-gray-500">
                            Fixed Salaries (Employees)
                        </p>
                        <p class="text-xl font-semibold text-blue-600">
                            Rs. {{ salary.toLocaleString() }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-3 shadow-sm border">
                        <p class="text-sm text-gray-500">
                            Total Forecasted Expenses
                        </p>
                        <p class="text-xl font-bold text-green-700">
                            Rs. {{ forecastAmount.toLocaleString() }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 text-sm text-gray-500 italic">
                    Forecast includes average of past 3 months expenses + fixed
                    monthly salary of Rs. {{ salary.toLocaleString() }}.
                </div>
            </div>

            <!-- Profit vs Forecast Summary -->
            <div class="bg-white shadow-lg rounded-4 p-6 space-y-4 border">
                <h2 class="text-xl font-semibold text-gray-800">
                   Over all Financial Health
                    
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
                    <div class="bg-gray-50 p-4 rounded-3 shadow-sm border">
                        <p class="text-sm text-gray-500">Total Income</p>
                        <p class="text-xl font-semibold text-green-600">
                            Rs. {{ totalIncome.toLocaleString() }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-3 shadow-sm border">
                        <p class="text-sm text-gray-500">
                            Total Actual Expenses (To-Date)
                        </p>
                        <p class="text-xl font-semibold text-red-600">
                            Rs. {{ totalExpenses.toLocaleString() }}
                        </p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-3 shadow-sm border">
                        <p class="text-sm text-gray-500">
                            Net Profit Available
                        </p>
                        <p
                            class="text-xl font-bold"
                            :class="
                                netProfit >= forecastAmount
                                    ? 'text-green-700'
                                    : 'text-red-600'
                            "
                        >
                            Rs. {{ netProfit.toLocaleString() }}
                        </p>
                        <p
                            class="text-sm mt-1"
                            :class="
                                netProfit >= forecastAmount
                                    ? 'text-green-500'
                                    : 'text-red-500'
                            "
                        >
                            {{
                                netProfit >= forecastAmount
                                    ? "Sufficient Profit to Cover Forecasted Expenses."
                                    : "Alert: Profit is Insufficient to Meet Forecasted Expenses."
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
