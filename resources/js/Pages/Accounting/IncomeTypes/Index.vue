<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Pencil, Plus } from "lucide-vue-next";
import { toast } from "vue3-toastify";

import IncomeTypeFormModal from "./IncomeTypeFormModal.vue";

const props = defineProps({
    types: Array,
});

// State
const showModal = ref(false);
const editingType = ref(null);
const itemToDelete = ref(null);

// Open modal
const openModal = (type = null) => {
    editingType.value = type;
    showModal.value = true;
};

// Delete logic
const deleteItem = async () => {
    try {
        await axios.delete(`/accounting/income-types/${itemToDelete.value.id}`);
        toast.success(`${itemToDelete.value.name} deleted successfully`);
        refresh();
    } catch (e) {
        if (e.response?.data?.message) {
            toast.error(e.response.data.message);
        } else {
            toast.error("Failed to delete income type.");
        }
    } finally {
        showConfirmModal.value = false;
        itemToDelete.value = null;
    }
};

const refresh = () => {
    router.reload({ only: ["types"] });
};
</script>

<template>
    <Head title="Income Types" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <!-- Page Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Income Types</h1>
                <button
                    class="bg-[#296FB6] hover:bg-[#1f5a96] text-white text-sm px-4 py-2 rounded-lg"
                    @click="openModal()"
                >
                    <Plus class="w-4 h-4 inline-block mr-1" />
                    Add Income Type
                </button>
            </div>

            <!-- Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(type, index) in props.types"
                            :key="type.id"
                            class="border-b hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">{{ index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold">
                                {{ type.name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ type.description || "-" }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button
                                        @click="() => openModal(type)"
                                        title="Edit"
                                        class="p-2 rounded-full text-blue-600 hover:bg-blue-100"
                                    >
                                        <Pencil class="w-4 h-4" />
                                    </button>
                                    <ConfirmModal
                                        :title="'Confirm Delete'"
                                        :message="`Are you sure you want to delete ${type.name}?`"
                                        :showDeleteButton="true"
                                        @confirm="
                                            () => {
                                                itemToDelete = type;
                                                deleteType = 'type';
                                                deleteItem();
                                            }
                                        "
                                        @cancel="() => {}"
                                    />
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!props.types.length">
                            <td
                                colspan="4"
                                class="text-center text-gray-500 py-6"
                            >
                                No income types found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Form -->
            <IncomeTypeFormModal
                v-if="showModal"
                :type="editingType"
                @close="
                    () => {
                        showModal = false;
                        editingType = null;
                    }
                "
                @submitted="refresh"
            />

            <!-- Confirm Delete Modal -->
        </div>
    </AppLayout>
</template>
