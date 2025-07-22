// File: resources/js/Pages/Accounting/Transactions/Index.vue
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { Pencil, Plus } from "lucide-vue-next";
import { toast } from "vue3-toastify";
import TransactionFormModal from "./TransactionForm.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    accounts: Object,
    incomeTypes: [],
    expenseTypes: [],
    banks: [],
});

// console.log("in the index page", props.banks, props.incomeTypes, props.expenseTypes);
const showModal = ref(false);
const editingTransaction = ref(null);
const itemToDelete = ref(null);

const search = ref("");

const filteredAccounts = computed(() => {
    if (!search.value) return props.accounts.data;
    return props.accounts.data.filter((item) =>
        item.description.toLowerCase().includes(search.value.toLowerCase())
    );
});

const openModal = (transaction = null) => {
    editingTransaction.value = transaction;
    showModal.value = true;
};

const refresh = () => {
    router.reload({ only: ["accounts"] });
};

// Zooming image functionality States
const showImageModal = ref(false);
const previewImage = ref(null);
const openImageModal = (src) => {
    console.log("Opening image modal with src:", src);
    previewImage.value = src;
    showImageModal.value = true;
};
</script>

<template>
    <Head title="Transactions" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold">Transactions</h1>
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
                                    <span class="text-gray-500"
                                        >Transactions</span
                                    >
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <PrimaryModalButton
                    @click="() => openModal()"
                    label="Add Transaction"
                >
                    <template #icon><Plus class="w-4 h-4" /></template>
                </PrimaryModalButton>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="flex px-4 py-3">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search by description"
                        class="pl-10 p-2 border border-gray-300 rounded-lg text-sm w-full"
                    />
                </div>

                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Account</th>
                            <th class="px-6 py-3">Description</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Receipt</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(account, index) in filteredAccounts"
                            :key="account.id"
                            class="border-b hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">{{ index + 1 }}</td>
                            <td class="px-6 py-4">{{ account.type }}</td>
                            <td class="px-6 py-4">{{ account.amount }}</td>
                            <td class="px-6 py-4">
                                {{ account.sourceable?.name }}
                            </td>
                            <td class="px-6 py-4">{{ account.description }}</td>
                            <td class="px-6 py-4">{{ account.date }}</td>
                            <td class="px-6 py-4">
                                <img
                                    :src="account.receipt_image_url"
                                    @click="
                                        openImageModal(
                                            account.receipt_image_url
                                        )
                                    "
                                    class="h-24 mt-2 border rounded cursor-zoom-in"
                                />
                                <ImageZoomModal
                                    :show="showImageModal"
                                    :image="account.receipt_image_url"
                                    @close="showImageModal = false"
                                />
                            </td>
                        </tr>
                        <tr v-if="!filteredAccounts.length">
                            <td
                                colspan="6"
                                class="text-center text-gray-500 py-6"
                            >
                                No transactions found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <TransactionFormModal
                v-if="showModal"
                :transaction="editingTransaction"
                :banks="props.banks"
                :expenseTypes="props.expenseTypes"
                :incomeTypes="props.incomeTypes"
                @close="
                    () => {
                        showModal = false;
                        editingTransaction = null;
                    }
                "
                @submitted="refresh"
            />
        </div>
    </AppLayout>
</template>
