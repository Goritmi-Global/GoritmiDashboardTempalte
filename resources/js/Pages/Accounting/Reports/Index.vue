<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import FilterToolbar from "@/Components/FilterToolbar.vue";
import jsPDF from "jspdf";
import autoTable from "jspdf-autotable";
import * as XLSX from "xlsx";

const props = defineProps({ reports: Array });

const search = ref("");
const typeFilter = ref(["income"]);
const year = ref("");
const month = ref("");
const dateRange = ref({ from: "", to: "" });

const filterType = computed(() => {
  if (year.value && !month.value && !dateRange.value.from) return "yearly";
  if (year.value && month.value) return "monthly";
  if (dateRange.value.from && dateRange.value.to) return "custom";
  return "none";
});

const filteredReports = computed(() => {
  return props.reports.filter((item) => {
    const date = new Date(item.date);
    const from = dateRange.value.from ? new Date(dateRange.value.from) : null;
    const to = dateRange.value.to ? new Date(dateRange.value.to) : null;
    const matchesYear = !year.value || date.getFullYear() === parseInt(year.value);
    const matchesMonth = !month.value || date.getMonth() + 1 === parseInt(month.value);
    const matchesCustom = (!from || date >= from) && (!to || date <= to);
    return matchesYear && matchesMonth && matchesCustom;
  });
});

const groupedData = computed(() => {
  const map = new Map();
  let balance = 0;

  const keyFn = (item) => {
    const date = new Date(item.date);
    if (filterType.value === "yearly") return `${date.toLocaleString("default", { month: "long" })} ${date.getFullYear()}`;
    return item.date;
  };

  filteredReports.value.forEach((item) => {
    const key = keyFn(item);
    if (!map.has(key)) map.set(key, { date: key, income: 0, expense: 0 });
    if (item.type === "income" || item.type === "opening_balance") {
      map.get(key).income += Number(item.amount);
    } else if (item.type === "expense") {
      map.get(key).expense += Number(item.amount);
    }
  });

  const grouped = Array.from(map.values()).sort((a, b) => new Date(a.date) - new Date(b.date));
  grouped.forEach((entry) => {
    balance += entry.income - entry.expense;
    entry.balance = balance;
  });
  return grouped;
});

const reportTitle = computed(() => {
  if (filterType.value === "yearly") return `Report for Year ${year.value}`;
  if (filterType.value === "monthly") return `Report for ${new Date(0, month.value - 1).toLocaleString("default", { month: "long" })} ${year.value}`;
  if (filterType.value === "custom") return `Report from ${dateRange.value.from} to ${dateRange.value.to}`;
  return "Accounting Report";
});

const totals = computed(() => {
  let income = 0,
    expense = 0,
    balance = 0;
  groupedData.value.forEach((item) => {
    income += item.income;
    expense += item.expense;
    balance = item.balance;
  });
  return { income, expense, balance };
});

const exportPdf = () => {
  const doc = new jsPDF();
  doc.setFontSize(14);
  doc.text(reportTitle.value, 14, 16);

  autoTable(doc, {
    startY: 22,
    head: [["#", filterType.value === "yearly" ? "Month" : "Date", "Income", "Expense", "Balance"]],
    body: groupedData.value.map((item, i) => [
      i + 1,
      item.date,
      item.income.toLocaleString(),
      item.expense.toLocaleString(),
      item.balance.toLocaleString(),
    ]),
    styles: { halign: "center" },
  });

  const finalY = doc.lastAutoTable.finalY || 30;

  doc.setFontSize(12);
  doc.text(`Total Income: Rs. ${totals.value.income.toLocaleString()}`, 14, finalY + 12);
  doc.text(`Total Expense: Rs. ${totals.value.expense.toLocaleString()}`, 14, finalY + 20);
  doc.setTextColor(28, 13, 130); // Blue
  doc.text(`Final Balance: Rs. ${totals.value.balance.toLocaleString()}`, 14, finalY + 28);
  doc.save("accounting-report.pdf");
};


const exportExcel = () => {
  const data = groupedData.value.map((item, i) => ({
    "#": i + 1,
    [filterType.value === "yearly" ? "Month" : "Date"]: item.date,
    Income: item.income,
    Expense: item.expense,
    Balance: item.balance,
  }));

  data.push({
    "#": "",
    [filterType.value === "yearly" ? "Month" : "Date"]: "TOTAL",
    Income: totals.value.income,
    Expense: totals.value.expense,
    Balance: totals.value.balance,
  });

  const worksheet = XLSX.utils.json_to_sheet(data);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "Report");
  XLSX.writeFile(workbook, "accounting-report.xlsx");
};


const printTable = () => {
  const content = document.getElementById("report-table")?.outerHTML;
  const w = window.open("", "_blank");
  if (w) {
    w.document.write(`
      <html>
        <head>
          <title>Print Report</title>
          <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
            th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
            th { background-color: #f0f0f0; }
            .totals { font-size: 16px; margin-top: 20px; }
            .totals div { margin-bottom: 8px; }
          </style>
        </head>
        <body>
          <h2>${reportTitle.value}</h2>
          ${content}
          <div class="totals">
            <div><strong>Total Income:</strong> Rs. ${totals.value.income.toLocaleString()}</div>
            <div><strong>Total Expense:</strong> Rs. ${totals.value.expense.toLocaleString()}</div>
            <div><strong>Final Balance:</strong> <span style="color: #1C0D82;"><strong>Rs. ${totals.value.balance.toLocaleString()}</strong></span></div>
          </div>
        </body>
      </html>
    `);
    w.document.close();
    w.print();
  }
};

</script>

<template>
  <Head title="Accounting Report" />
  <AppLayout>
    <div class="p-6 space-y-6">
      <div>
        <h1 class="text-2xl font-bold">{{ reportTitle }}</h1>
        <p class="text-sm text-gray-500">
          Total Records: {{ groupedData.length }} |
          Income: Rs. {{ totals.income.toLocaleString() }} |
          Expense: Rs. {{ totals.expense.toLocaleString() }} |
          Balance: Rs. {{ totals.balance.toLocaleString() }}
        </p>
      </div>

      <div class="bg-white shadow rounded-lg overflow-hidden">
        <FilterToolbar
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

        <div class="overflow-auto">
          <table id="report-table" class="min-w-full text-sm text-gray-600 text-center">
            <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
              <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">{{ filterType === 'yearly' ? 'Month' : 'Date' }}</th>
                <th class="px-4 py-2">Income</th>
                <th class="px-4 py-2">Expense</th>
                <th class="px-4 py-2">Balance</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in groupedData" :key="index" class="border-b">
                <td class="px-4 py-2">{{ index + 1 }}</td>
                <td class="px-4 py-2">{{ item.date }}</td>
                <td class="px-4 py-2 text-green-600 font-semibold">{{ item.income.toLocaleString() }}</td>
                <td class="px-4 py-2 text-red-600 font-semibold">{{ item.expense.toLocaleString() }}</td>
                <td class="px-4 py-2 text-blue-600 font-semibold">{{ item.balance.toLocaleString() }}</td>
              </tr>
              <tr v-if="!groupedData.length">
                <td colspan="5" class="text-center text-gray-500 py-6">No records found.</td>
              </tr>
            </tbody>
            <tfoot class="bg-gray-50 font-semibold text-sm">
              <tr>
                <td colspan="2" class="text-right px-4 py-2">Total</td>
                <td class="text-green-700 px-4 py-2">{{ totals.income.toLocaleString() }}</td>
                <td class="text-red-700 px-4 py-2">{{ totals.expense.toLocaleString() }}</td>
                <td class="text-blue-700 px-4 py-2">{{ totals.balance.toLocaleString() }}</td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>