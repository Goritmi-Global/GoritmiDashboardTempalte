<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const entries = [
    { date: "2025-07-15", description: "Sale Invoice", amount: "+$500.00", type: "Credit" },
    { date: "2025-07-14", description: "Purchase Raw Materials", amount: "-$300.00", type: "Debit" },
    { date: "2025-07-13", description: "Electricity Bill", amount: "-$150.00", type: "Debit" },
];
</script>

<template>
    <Head title="Accounting" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <!-- Header and Breadcrumb -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Accounting Module</h1>

                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                                <Link href="/" class="text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white inline-flex items-center">
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mx-1 rtl:rotate-180" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="text-gray-500 dark:text-gray-400">Accounting</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <Link href="/accounting/create" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 text-sm">
                    ➕ Add New
                </Link>
            </div>

            <!-- Card with Table -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 overflow-x-auto">
                <h2 class="text-lg font-semibold mb-4">Recent Accounting Entries</h2>
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400">
                            <th class="py-2">Date</th>
                            <th class="py-2">Description</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(entry, index) in entries" :key="index" class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-2">{{ entry.date }}</td>
                            <td class="py-2">{{ entry.description }}</td>
                            <td class="py-2 font-semibold">{{ entry.amount }}</td>
                            <td class="py-2">
                                <span
                                    :class="entry.type === 'Credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                >
                                    {{ entry.type }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template> 