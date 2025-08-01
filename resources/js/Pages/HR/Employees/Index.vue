// File: resources/js/Pages/HR/Employees/Index.vue

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { Plus, Pencil, Trash2 } from "lucide-vue-next";
import { toast } from "vue3-toastify";
import EmployeeForm from "./Form.vue";

const props = defineProps({ employees: Array, departments: Array });
const showModal = ref(false);
const editingEmployee = ref(null);
const itemToDelete = ref(null);
const search = ref("");

const filteredEmployees = computed(() => {
    if (!search.value) return props.employees;
    return props.employees.filter((e) =>
        e.name.toLowerCase().includes(search.value.toLowerCase()) ||
        e.nic.toLowerCase().includes(search.value.toLowerCase())
    );
});

const openModal = (employee = null) => {
    editingEmployee.value = employee;
    showModal.value = true;
};

const deleteItem = async () => {
    try {
        await axios.delete(`/hr/employees/${itemToDelete.value.id}`);
        toast.success(`${itemToDelete.value.name} deleted successfully`);
        refresh();
    } catch (e) {
        toast.error("Failed to delete employee.");
    } finally {
        itemToDelete.value = null;
    }
};

const refresh = () => {
    router.reload({ only: ["employees"] });
};
</script>

<template>
    <Head title="Employees" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold">Employees</h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2">
                            <li class="inline-flex items-center">
                                <Link href="/" class="text-gray-700 hover:text-blue-600">Home</Link>
                            </li>
                            <li class="text-gray-500">Employees</li>
                        </ol>
                    </nav>
                </div>
                <button @click="() => openModal()" class="flex items-center gap-2 px-4 py-2 rounded bg-blue-600 text-white">
                    <Plus class="w-4 h-4" /> Add Employee
                </button>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="flex flex-col px-4 py-3 gap-3 md:flex-row md:items-center md:justify-between">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search by name or NIC"
                        class="pl-10 p-2 border border-gray-300 rounded-lg text-sm w-full md:w-1/3"
                    />
                </div>

                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">NIC</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Department</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(employee, index) in filteredEmployees"
                            :key="employee.id"
                            class="border-b hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">{{ index + 1 }}</td>
                            <td class="px-6 py-4 text-blue-600">
                                <Link :href="route('hr.employees.show', employee.id)">
                                    {{ employee.name }}
                                </Link>
                            </td>
                            <td class="px-6 py-4">{{ employee.nic }}</td>
                            <td class="px-6 py-4">{{ employee.email || '-' }}</td>
                            <td class="px-6 py-4">{{ employee.department?.title || '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click="() => openModal(employee)" class="p-2 rounded-full text-blue-600 hover:bg-blue-100">
                                        <Pencil class="w-4 h-4" />
                                    </button>
                                    <button @click="() => (itemToDelete = employee, deleteItem())" class="p-2 rounded-full text-red-600 hover:bg-red-100">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!filteredEmployees.length">
                            <td colspan="6" class="text-center text-gray-500 py-6">No employees found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <EmployeeForm
                v-if="showModal"
                :employee="editingEmployee"
                :departments="props.departments"
                @close="() => { showModal = false; editingEmployee = null; }"
                @submitted="refresh"
            />
        </div>
    </AppLayout>
</template>
