<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import FilterToolbar from "@/Components/FilterToolbar.vue";
const props = defineProps({
    incomes: Array,
    totalIncomeAmount: Number,
    incomeCount: Number,
});

const search = ref("");
const typeFilter = ref(["income"]);
const year = ref("");
const month = ref("");
const dateRange = ref({ from: "", to: "" });

const filteredIncomes = computed(() => {
    const term = search.value.toLowerCase();

    return props.incomes.filter((item) => {
        const txn = item.account;
        const txnDate = new Date(txn.date);

        const matchesSearch = [
            txn.description,
            txn.amount,
            txn.account_country,
            txn.date,
            item.receipt_no,
        ].some((val) => val?.toString().toLowerCase().includes(term));

        const matchesYear =
            !year.value || txnDate.getFullYear() === parseInt(year.value);
        const matchesMonth =
            !month.value || txnDate.getMonth() + 1 === parseInt(month.value);
        const from = dateRange.value.from
            ? new Date(dateRange.value.from)
            : null;
        const to = dateRange.value.to ? new Date(dateRange.value.to) : null;
        const matchesCustomRange =
            (!from || txnDate >= from) && (!to || txnDate <= to);

        return (
            matchesSearch && matchesYear && matchesMonth && matchesCustomRange
        );
    });
});

const ledgerTotals = computed(() => {
    let credit = 0;
    filteredIncomes.value.forEach((i) => {
        credit += Number(i.account.amount);
    });
    return {
        credit,
        debit: 0,
        bankToCash: 0,
        cashToBank: 0,
        finalBalance: credit,
    };
});
</script>

<template>
    <Head title="Income Records" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Income Records</h1>
            <p class="text-sm text-gray-500">
                Overall Records: {{ incomeCount }} | Overall Total Income: Rs.
                {{ totalIncomeAmount.toLocaleString() }}
            </p>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <FilterToolbar
                    :accounts="{ data: props.incomes.map((i) => i.account) }"
                    v-model:search="search"
                    v-model:typeFilter="typeFilter"
                    v-model:year="year"
                    v-model:month="month"
                    v-model:dateRange="dateRange"
                    :showFilter="false"
                    :showExport="false"
                />

                <table class="w-full text-sm text-left text-gray-600">
                    <thead
                        class="bg-gray-100 text-xs text-gray-700 uppercase text-center"
                    >
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
                            v-for="(income, index) in filteredIncomes"
                            :key="income.id"
                            class="text-center border-b"
                        >
                            <td class="px-4 py-3">{{ index + 1 }}</td>
                            <td class="px-4 py-3">
                                {{ income.account?.sourceable?.name || "N/A" }}
                            </td>
                            <td class="px-4 py-3">
                                {{ income.receipt_no || "—" }}
                            </td>
                            <td class="px-4 py-3">{{ income.account.date }}</td>
                            <td class="px-4 py-3">
                                {{ income.account.description || "—" }}
                            </td>
                            <td class="px-4 py-3 text-green-600 font-semibold">
                                {{
                                    Number(
                                        income.account.amount
                                    ).toLocaleString()
                                }}
                            </td>
                        </tr>
                        <tr v-if="!filteredIncomes.length">
                            <td
                                colspan="6"
                                class="text-center text-gray-500 py-6"
                            >
                                No income records found.
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold text-center text-sm">
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-right">
                                Total Income:
                            </td>
                            <td colspan="2" class="px-6 py-4 text-green-700">
                                {{ ledgerTotals.credit.toLocaleString() }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
