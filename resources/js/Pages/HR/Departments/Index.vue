<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { Plus, Pencil, Trash2 } from "lucide-vue-next";
import { toast } from "vue3-toastify";

import DepartmentForm from "./Form.vue";

const props = defineProps({
    departments: Array,
});

const showModal = ref(false);
const editingDepartment = ref(null);
const itemToDelete = ref(null);
const search = ref("");

const filteredDepartments = computed(() => {
    if (!search.value) return props.departments;
    return props.departments.filter((d) =>
        d.title.toLowerCase().includes(search.value.toLowerCase())
    );
});

const openModal = (department = null) => {
    editingDepartment.value = department;
    showModal.value = true;
};

const deleteItem = async () => {
    try {
        await axios.delete(`/hr/departments/${itemToDelete.value.id}`);
        toast.success(`${itemToDelete.value.title} deleted successfully`);
        refresh();
    } catch (e) {
        toast.error("Failed to delete department.");
    } finally {
        itemToDelete.value = null;
    }
};

const refresh = () => {
    router.reload({ only: ["departments"] });
};
</script>

<template>
    <Head title="Departments" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold">Departments</h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2">
                            <li class="inline-flex items-center">
                                <Link href="/" class="text-gray-700 hover:text-blue-600">
                                    Home
                                </Link>
                            </li>
                            <li class="text-gray-500">Departments</li>
                        </ol>
                    </nav>
                </div>
                <button @click="() => openModal()" class="flex items-center gap-2 px-4 py-2 rounded bg-blue-600 text-white">
                    <Plus class="w-4 h-4" /> Add Department
                </button>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="flex flex-col px-4 py-3 gap-3 md:flex-row md:items-center md:justify-between">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search by title"
                        class="pl-10 p-2 border border-gray-300 rounded-lg text-sm w-full md:w-1/3"
                    />
                </div>

                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Title</th>
                            <th class="px-6 py-3">Code</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(department, index) in filteredDepartments"
                            :key="department.id"
                            class="border-b hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">{{ index + 1 }}</td>
                            <td class="px-6 py-4">{{ department.title }}</td>
                            <td class="px-6 py-4">{{ department.code || '-' }}</td>
                            <td class="px-6 py-4">{{ department.description || '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click="() => openModal(department)" title="Edit" class="p-2 rounded-full text-blue-600 hover:bg-blue-100">
                                        <Pencil class="w-4 h-4" />
                                    </button>
                                    <button @click="() => (itemToDelete = department, deleteItem())" title="Delete" class="p-2 rounded-full text-red-600 hover:bg-red-100">
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!filteredDepartments.length">
                            <td colspan="5" class="text-center text-gray-500 py-6">No departments found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <DepartmentForm
                v-if="showModal"
                :department="editingDepartment"
                @close="() => { showModal = false; editingDepartment = null; }"
                @submitted="refresh"
            />
        </div>
    </AppLayout>
</template>

 