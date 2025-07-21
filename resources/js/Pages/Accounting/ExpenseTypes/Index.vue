<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Pencil, Plus } from "lucide-vue-next";
import { toast } from "vue3-toastify";

import ExpenseTypeFormModal from "./ExpenseTypeFormModal.vue"; 

const props = defineProps({
    types: Array,
});
const itemToDelete = ref(null);
// State
const showModal = ref(true);
const editingType = ref(null);

// Open modal
const openModal = (type = null) => {
    editingType.value = type;
    showModal.value = true;
};

// Delete
// const deleteType = async (type) => {
//     if (!confirm(`Are you sure you want to delete "${type.name}"?`)) return;

//     try {
//         await axios.delete(`/accounting/expense-types/${type.id}`);
//         toast.success("Expense type deleted successfully");
//         refresh();
//     } catch (error) {
//         toast.error("Failed to delete expense type.");
//     }
// };

const deleteItem = async () => {
  try {
    await axios.delete(`/accounting/expense-types/${itemToDelete.value.id}`);
    toast.success(`${itemToDelete.value.name} deleted successfully`);
    refresh();
  } catch (e) {
    if (e.response?.data?.message) {
      toast.error(e.response.data.message);
    } else {
      toast.error("Failed to delete expense type.");
    }
  } finally {
    itemToDelete.value = null;
  }
};



const refresh = () => {
    router.reload({ only: ['types'] });
};
</script>

<template>
    <Head title="Expense Types" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Expense Types</h1>
                <button
                    class="bg-[#296FB6] hover:bg-[#1f5a96] text-white text-sm px-4 py-2 rounded-lg"
                    @click="openModal()"
                >
                    <Plus class="w-4 h-4 inline-block mr-1" />
                    Add Expense Type
                </button>
            </div>

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
                            <td class="px-6 py-4 font-semibold">{{ type.name }}</td>
                            <td class="px-6 py-4">{{ type.description || '-' }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                 <div
                                    class="flex items-center justify-center gap-2"
                                >
                                <button
                                    @click="() => openModal(type)"
                                    title="Edit"
                                    class="p-2 rounded-full text-blue-600 hover:bg-blue-100"
                                >
                                    <Pencil class="w-4 h-4" />
                                </button>
                                <ConfirmModal
                                    v-if="type.name !== 'admin'"
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
                            <td colspan="4" class="text-center text-gray-500 py-6">No expense types found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <ExpenseTypeFormModal
                v-if="showModal"
                :type="editingType"
                @close="() => { showModal = false; editingType = null }"
                @submitted="refresh"
            />

              
        </div>
    </AppLayout>
</template>
