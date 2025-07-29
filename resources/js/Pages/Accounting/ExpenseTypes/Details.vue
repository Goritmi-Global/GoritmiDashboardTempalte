<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import FilterToolbar from "@/Components/FilterToolbar.vue";

const props = defineProps({
    type: Object,
    expenses: Array,
    totalAmount: Number,
    expenseCount: Number,
});



const search = ref("");
const typeFilter = ref(["expense"]);
const year = ref("");
const month = ref("");
const dateRange = ref({ from: "", to: "" });
 const emit = defineEmits([
    "update:search",
    "update:typeFilter",
    "update:showFilter",
    "update:showExport",
    "update:year",
    "update:month",
    "update:dateRange",
]);

const filteredExpenses = computed(() => {
    const term = search.value.toLowerCase();
    const selectedYearVal = parseInt(year.value);
    const selectedMonthVal = parseInt(month.value);
    const fromDate = dateRange.value.from ? new Date(dateRange.value.from) : null;
    const toDate = dateRange.value.to ? new Date(dateRange.value.to) : null;

    return props.expenses.filter((item) => {
        const acc = item.account;
        const txnDate = new Date(acc.date);

        const matchesSearch = [
            acc.description,
            acc.amount,
            acc.date,
            acc.account_country,
            item.receipt_no,
        ].some((val) => val?.toString().toLowerCase().includes(term));

        const matchesYear = !selectedYearVal || txnDate.getFullYear() === selectedYearVal;
        const matchesMonth = !selectedMonthVal || txnDate.getMonth() + 1 === selectedMonthVal;
        const matchesDateRange =
            (!fromDate || txnDate >= fromDate) &&
            (!toDate || txnDate <= toDate);

        return matchesSearch && matchesYear && matchesMonth && matchesDateRange;
    });
});

const filteredTotal = computed(() =>
    filteredExpenses.value.reduce((sum, i) => {
        return sum + (i.account?.amount ? Number(i.account.amount) : 0);
    }, 0)
);


</script>

<template>
    <Head :title="`Expenses of ${type.name}`" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">
                Expense Type: {{ type.name }}
            </h1>
            <p class="text-sm text-gray-500">
                Total Records: {{ expenseCount }} |
                Total Amount: Rs. {{ totalAmount.toLocaleString() }}
            </p>

           

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <FilterToolbar
    :accounts="{ data: props.expenses.map(e => e.account) }"
    v-model:search="search"
    v-model:typeFilter="typeFilter"
    v-model:year="year"
    v-model:month="month"
    v-model:dateRange="dateRange"
    :showFilter="false"
    :showExport="false"
/>

                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-xs text-gray-700 uppercase text-center">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Account</th>
                            <th class="px-4 py-2">Receipt #</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(expense, index) in filteredExpenses"
                            :key="expense.id"
                            class="text-center border-b"
                        >
                            <td class="px-4 py-3">{{ index + 1 }}</td>
                            <td class="px-4 py-3">
                                {{ expense.account?.sourceable?.name || "N/A" }}
                            </td>
                            <td class="px-4 py-3">{{ expense.receipt_no || "—" }}</td>
                            <td class="px-4 py-3">{{ expense.account.date }}</td>
                            <td class="px-4 py-3">{{ expense.account.description || "—" }}</td>
                            <td class="px-4 py-3 text-red-600 font-semibold">
                                {{ Number(expense.account.amount).toLocaleString() }}
                            </td>
                        </tr>
                        <tr v-if="!filteredExpenses.length">
                            <td colspan="6" class="text-center text-gray-500 py-6">No records found.</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold text-center text-sm">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right">Total Expense:</td>
                            <td colspan="2" class="px-6 py-4 text-red-700">
                                {{ filteredTotal.toLocaleString() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
