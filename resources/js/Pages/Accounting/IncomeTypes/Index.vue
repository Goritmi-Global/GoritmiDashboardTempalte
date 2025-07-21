<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Pencil, Plus,KeyRound ,Trash2} from "lucide-vue-next";
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
             <div class="flex justify-between items-center">
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold">Income Types</h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-2"
                        >
                            <li class="inline-flex items-center">
                                <Link
                                    href="/"
                                    class="text-gray-700 hover:text-blue-600 inline-flex items-center"
                                >
                                    <svg
                                        class="w-3 h-3 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                                        />
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li>
                                <div class="flex items-center">
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
                                    <span class="text-gray-500">Income Types</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                 <!-- <button
                    class="bg-[#296FB6] hover:bg-[#1f5a96] text-white text-sm px-4 py-2 rounded-lg"
                    @click="openModal()"
                >
                    <Plus class="w-4 h-4 inline-block mr-1" />
                    Add Income Type
                </button> -->
                <PrimaryModalButton
                         
                        @click="() => openModal()"
                        label="Add Income Type"
                    >
                        <template #icon><Plus class="w-4 h-4" /></template>
                    </PrimaryModalButton>
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
