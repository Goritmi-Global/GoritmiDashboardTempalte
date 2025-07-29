<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    accounts: Object,
    typeFilter: Array,
    showFilter: Boolean,
    showExport: Boolean,
    year: String,
    month: String,
    dateRange: Object,
});

const emit = defineEmits([
    "update:search",
    "update:typeFilter",
    "update:showFilter",
    "update:showExport",
    "update:year",
    "update:month",
    "update:dateRange",
]);

const localSearch = ref("");
const localShowFilter = ref(props.showFilter || false);
const localShowExport = ref(props.showExport || false);

// Filters (without type)
const selectedYear = ref(props.year || "");
const selectedMonth = ref(props.month || "");
const dateRange = ref(props.dateRange || { from: "", to: "" });

watch(localSearch, (val) => emit("update:search", val));
watch(localShowFilter, (val) => emit("update:showFilter", val));
watch(localShowExport, (val) => emit("update:showExport", val));
watch(selectedYear, (val) => emit("update:year", val));
watch(selectedMonth, (val) => emit("update:month", val));
watch(dateRange, (val) => emit("update:dateRange", val));

const exportToPDF = () => alert("Export to PDF triggered");
const exportToExcel = () => alert("Export to Excel triggered");
</script>

<template>
    <div
        class="flex flex-col px-4 py-3 space-y-3 lg:flex-row lg:items-center lg:justify-between lg:space-y-0 lg:space-x-4"
    >
        <!-- Search -->
        <div class="w-full md:w-1/2">
            <div class="relative w-full">
                <div
                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                >
                    <svg
                        class="w-5 h-5 text-gray-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.9 3.5l4.8 4.8a1 1 0 01-1.4 1.4l-4.8-4.8A6 6 0 012 8z"
                        />
                    </svg>
                </div>
                <input
                    v-model="localSearch"
                    type="text"
                    class="w-full border border-gray-300 text-sm pl-10 p-2 rounded-lg"
                    placeholder="Search in all fields..."
                />
            </div>
        </div>

        <!-- Filter & Export -->
        <div
            class="flex flex-col md:flex-row items-center gap-3 relative overflow-visible z-10"
        >
            <!-- Filter Dropdown -->
            <div class="relative inline-block text-left">
                <button
                    @click="localShowFilter = !localShowFilter"
                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-dark border rounded-md focus:outline-none focus:ring-2 focus:ring-gray-600"
                >
                    <svg
                        class="w-4 h-4 text-dark"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>Filter</span>
                    <svg
                        class="w-4 h-4 text-dark"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </button>

                <teleport to="body">
                    <div
                        v-show="localShowFilter"
                        class="fixed top-[130px] right-[50px] w-64 bg-white shadow-lg rounded-md z-[9999] p-3"
                        :style="{
                            marginTop: '110px',
                            border: '1px solid #296FB6',
                        }"
                    >
                        <div class="p-3">
                            <!-- Year Filter -->
                            <div class="mt-4">
                                <label class="text-sm font-medium mb-1 block"
                                    >Filter by Year</label
                                >
                                <select
                                    v-model="selectedYear"
                                    class="w-full border p-1 rounded text-sm"
                                >
                                    <option value="">All Years</option>
                                    <option
                                        v-for="year in [2025, 2024, 2023, 2022, 2021]"
                                        :key="year"
                                        :value="year"
                                    >
                                        {{ year }}
                                    </option>
                                </select>
                            </div>

                            <!-- Month Filter -->
                            <div class="mt-4">
                                <label class="text-sm font-medium mb-1 block"
                                    >Filter by Month</label
                                >
                                <select
                                    v-model="selectedMonth"
                                    class="w-full border p-1 rounded text-sm"
                                >
                                    <option value="">All Months</option>
                                    <option
                                        v-for="(m, i) in [
                                            'January',
                                            'February',
                                            'March',
                                            'April',
                                            'May',
                                            'June',
                                            'July',
                                            'August',
                                            'September',
                                            'October',
                                            'November',
                                            'December',
                                        ]"
                                        :key="i"
                                        :value="i + 1"
                                    >
                                        {{ m }}
                                    </option>
                                </select>
                            </div>

                            <!-- From-To Date -->
                            <div class="mt-4">
                                <label class="text-sm font-medium mb-1 block"
                                    >Custom Date Range</label
                                >
                                <div class="grid grid-cols-12 gap-2">
                                    <div class="col-span-12">
                                        <label class="text-sm font-medium mb-1 block">From Date</label>
                                        <input
                                            type="date"
                                            v-model="dateRange.from"
                                            class="w-full border p-1 rounded text-sm"
                                            placeholder="From"
                                        />
                                    </div>
                                    <div class="col-span-12">
                                        <label class="text-sm font-medium mb-1 block">To Date</label>
                                        <input
                                            type="date"
                                            v-model="dateRange.to"
                                            class="w-full border p-1 rounded text-sm"
                                            placeholder="To"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </teleport>
            </div>

            <!-- Export Dropdown -->
            <div class="relative">
                <button
                    @click="localShowExport = !localShowExport"
                    class="border px-3 py-2 text-sm rounded-md"
                >
                    Export
                </button>
                <div
                    v-if="localShowExport"
                    class="absolute mt-2 right-0 bg-white rounded shadow p-2 w-40 z-10"
                >
                    <button
                        @click="exportToPDF"
                        class="block w-full text-left px-2 py-1 hover:bg-gray-100"
                    >
                        📄 Export PDF
                    </button>
                    <button
                        @click="exportToExcel"
                        class="block w-full text-left px-2 py-1 hover:bg-gray-100"
                    >
                        📊 Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
