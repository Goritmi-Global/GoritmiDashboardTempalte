<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import { Pencil } from "lucide-vue-next";
import { toast } from "vue3-toastify";

import CashbookFormModal from "./CashbookFormModal.vue";

const props = defineProps({
    cashbook: Object,
});

const showModal = ref(false);
const editingCashbook = ref(null);

const openModal = () => {
    editingCashbook.value = props.cashbook;
    showModal.value = true;
};

const refresh = () => {
    router.reload({ only: ["cashbook"] });
};
</script>

<template>
    <Head title="Cashbook" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold">Cashbook</h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2">
                            <li class="inline-flex items-center">
                                <Link
                                    href="/"
                                    class="text-gray-700 hover:text-blue-600 inline-flex items-center"
                                >
                                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="text-gray-500">Cashbook</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <button
                    @click="openModal"
                    class="bg-[#296FB6] hover:bg-[#1f5a96] text-white text-sm px-4 py-2 rounded-lg flex items-center gap-1"
                >
                    <Pencil class="w-4 h-4" /> Edit
                </button>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">Name:</span>
                        <span>{{ props.cashbook.name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Balance:</span>
                        <span>{{ props.cashbook.balance }}</span>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <CashbookFormModal
                v-if="showModal"
                :cashbook="editingCashbook"
                @close="() => { showModal = false; editingCashbook = null }"
                @submitted="refresh"
            />
        </div>
    </AppLayout>
</template>
