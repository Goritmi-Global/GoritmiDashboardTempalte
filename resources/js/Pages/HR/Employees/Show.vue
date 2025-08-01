// File: resources/js/Pages/HR/Employees/Show.vue

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref } from "vue";

// Tab components
import Joining from "./Partials/Joining.vue";
import SalaryHistory from "./Partials/SalaryHistory.vue";
import DepartmentHistory from "./Partials/DepartmentHistory.vue";

const props = defineProps({
    employee: Object,
});

const activeTab = ref("basic");
</script>

<template>
    <Head :title="`${employee.name}`" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <div class="space-y-1">
                    <h1 class="text-2xl font-bold">
                        Employee: {{ employee.name }}
                    </h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-2"
                        >
                            <li class="inline-flex items-center">
                                <Link
                                    href="/"
                                    class="text-gray-700 hover:text-blue-600"
                                    >Home</Link
                                >
                            </li>
                            <li class="inline-flex items-center">
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
                                <Link
                                    href="/hr/employees"
                                    class="text-gray-700 hover:text-blue-600"
                                    >Employees</Link
                                >
                            </li>
                            <li>
                                <span class="text-gray-500">Profile</span>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white shadow rounded-lg">
                <div class="border-b px-6 pt-4 flex space-x-4">
                    <button
                        @click="activeTab = 'basic'"
                        :class="[
                            activeTab === 'basic'
                                ? 'border-b-2 border-blue-600 text-blue-600'
                                : 'text-gray-600 hover:text-blue-600',
                            'pb-2 font-medium',
                        ]"
                    >
                        Basic Info
                    </button>
                    <button
                        @click="activeTab = 'joining'"
                        :class="[
                            activeTab === 'joining'
                                ? 'border-b-2 border-blue-600 text-blue-600'
                                : 'text-gray-600 hover:text-blue-600',
                            'pb-2 font-medium',
                        ]"
                    >
                        Joining
                    </button>
                    <button
                        @click="activeTab = 'salary'"
                        :class="[
                            activeTab === 'salary'
                                ? 'border-b-2 border-blue-600 text-blue-600'
                                : 'text-gray-600 hover:text-blue-600',
                            'pb-2 font-medium',
                        ]"
                    >
                        Salary History
                    </button>
                    <button
                        @click="activeTab = 'departments'"
                        :class="[
                            activeTab === 'departments'
                                ? 'border-b-2 border-blue-600 text-blue-600'
                                : 'text-gray-600 hover:text-blue-600',
                            'pb-2 font-medium',
                        ]"
                    >
                        Department History
                    </button>
                </div>

                <div class="p-6">
                    <div
                        v-if="activeTab === 'basic'"
                        class="rounded-xl shadow p-6 bg-white border border-gray-200"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.name }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">NIC</p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.nic }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.email || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Contact</p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.contact || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Gender</p>
                                <p
                                    class="text-base font-semibold text-gray-800 capitalize"
                                >
                                    {{ employee.gender || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Date of Birth
                                </p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.dob || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Nationality</p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.nationality || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Marital Status
                                </p>
                                <p
                                    class="text-base font-semibold text-gray-800 capitalize"
                                >
                                    {{ employee.marital_status || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Address</p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.address || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Status</p>
                                <p
                                    class="text-base font-semibold text-gray-800 capitalize"
                                >
                                    {{ employee.status || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Designation</p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.designation || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">Department</p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.department?.title || "-" }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Department Code
                                </p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{ employee.department?.code || "-" }}
                                </p>
                            </div>

                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-500">
                                    Department Description
                                </p>
                                <p
                                    class="text-base font-semibold text-gray-800"
                                >
                                    {{
                                        employee.department?.description || "-"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="activeTab === 'joining'">
                        <Joining :employee-id="employee.id" />
                    </div>

                    <div v-if="activeTab === 'salary'">
                        <SalaryHistory :employee-id="employee.id" />
                    </div>

                    <div v-if="activeTab === 'departments'">
                        <DepartmentHistory :employee-id="employee.id" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
