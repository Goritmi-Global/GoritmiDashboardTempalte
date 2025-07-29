<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import FilterToolbar from "@/Components/FilterToolbar.vue";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import * as XLSX from "xlsx";

// ✅ use `reports` from backend
const props = defineProps({
    reports: Array,
});

// Filters
const search = ref("");
const typeFilter = ref(["income"]);
const year = ref("");
const month = ref("");
const dateRange = ref({ from: "", to: "" });

// Filtered data
const filteredReports = computed(() => {
    const term = search.value.toLowerCase();

    return props.reports.filter((item) => {
        const txnDate = new Date(item.date);

        const matchesSearch = [
            item.description,
            item.amount,
            item.account_country,
            item.date,
            item.incomes?.[0]?.receipt_no ?? item.expenses?.[0]?.receipt_no ?? "",
        ].some((val) => val?.toString().toLowerCase().includes(term));

        const matchesYear = !year.value || txnDate.getFullYear() === parseInt(year.value);
        const matchesMonth = !month.value || txnDate.getMonth() + 1 === parseInt(month.value);

        const from = dateRange.value.from ? new Date(dateRange.value.from) : null;
        const to = dateRange.value.to ? new Date(dateRange.value.to) : null;
        const matchesCustomRange =
            (!from || txnDate >= from) && (!to || txnDate <= to);

        return matchesSearch && matchesYear && matchesMonth && matchesCustomRange;
    });
});

// Summary totals
const ledgerTotals = computed(() => {
    let credit = 0;
    filteredReports.value.forEach((i) => {
        credit += Number(i.amount);
    });
    return {
        credit,
        finalBalance: credit,
    };
});

// Accounts for toolbar
const mappedAccounts = computed(() => props.reports.map((i) => i));

// Export PDF
const exportPdf = () => {
    const doc = new jsPDF();
    doc.text("Income Report", 14, 16);
    autoTable(doc, {
        head: [["#", "Account", "Receipt #", "Date", "Description", "Amount"]],
        body: filteredReports.value.map((item, i) => [
            i + 1,
            item.sourceable?.name || "N/A",
            item.incomes?.[0]?.receipt_no || item.expenses?.[0]?.receipt_no || "—",
            item.date,
            item.description || "—",
            item.amount.toLocaleString(),
        ]),
    });
    doc.save("report.pdf");
};

// Export Excel
const exportExcel = () => {
    const data = filteredReports.value.map((item, i) => ({
        "#": i + 1,
        Account: item.sourceable?.name || "N/A",
        "Receipt #": item.incomes?.[0]?.receipt_no || item.expenses?.[0]?.receipt_no || "—",
        Date: item.date,
        Description: item.description || "—",
        Amount: item.amount,
    }));

    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Report");
    XLSX.writeFile(workbook, "report.xlsx");
};

// Print Table
const printTable = () => {
    const printContent = document.getElementById("income-report-table")?.outerHTML;
    const printWindow = window.open("", "_blank");
    if (printWindow) {
        printWindow.document.write(`
            <html>
            <head><title>Print Report</title></head>
            <body>
                <h2>Report</h2>
                ${printContent}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
};
</script>

<template>
    <Head title="Accounting Report" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div>
                <h1 class="text-2xl font-bold">Accounting Report</h1>
                <p class="text-sm text-gray-500">
                    Total Records: {{ filteredReports.length }} |
                    Total Amount: Rs. {{ ledgerTotals.credit.toLocaleString() }}
                </p>
            </div>

            <!-- Filter Toolbar -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <FilterToolbar
                    :accounts="{ data: mappedAccounts }"
                    v-model:search="search"
                    v-model:typeFilter="typeFilter"
                    v-model:year="year"
                    v-model:month="month"
                    v-model:dateRange="dateRange"
                    :showFilter="false"
                    :showExport="true"
                    @exportPdf="exportPdf"
                    @exportExcel="exportExcel"
                    @print="printTable"
                />

                <!-- Report Table -->
                <div class="overflow-auto">
                    <table
                        id="income-report-table"
                        class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-600"
                    >
                        <thead class="bg-gray-100 text-xs text-gray-700 uppercase text-center">
                            <tr>
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2">Account</th>
                                <th class="px-4 py-2">Receipt #</th>
                                <th class="px-4 py-2">Date</th>
                                <th class="px-4 py-2">Transaction</th>
                                <th class="px-4 py-2">Description</th>
                                <th class="px-4 py-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in filteredReports"
                                :key="item.id"
                                class="text-center border-b"
                            >
                                <td class="px-4 py-3">{{ index + 1 }}</td>
                                <td class="px-4 py-3">
                                    {{ item.sourceable?.name || "N/A" }}
                                </td>
                                <td class="px-4 py-3">
                                    {{
                                        item.incomes?.[0]?.receipt_no ||
                                        item.expenses?.[0]?.receipt_no ||
                                        "—"
                                    }}
                                </td>

                                <td class="px-4 py-3">{{ item.date }}</td>
                                <td class="px-4 py-3">
                                  {{
                                        item.type
                                            .replaceAll("_", " ")
                                            .replace(/\b\w/g, (l) =>
                                                l.toUpperCase()
                                            )
                                    }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ item.description || "—" }}
                                </td>
                                <td class="px-4 py-3 text-green-600 font-semibold">
                                    {{ Number(item.amount).toLocaleString() }}
                                </td>
                            </tr>
                            <tr v-if="!filteredReports.length">
                                <td colspan="6" class="text-center text-gray-500 py-6">
                                    No records found.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50 font-semibold text-center text-sm">
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-right">
                                    Total Amount:
                                </td>
                                <td colspan="2" class="px-6 py-4 text-green-700">
                                    {{ ledgerTotals.credit.toLocaleString() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
