// File: resources/js/Pages/Accounting/Transactions/Index.vue
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { Pencil, Plus } from "lucide-vue-next";
import { toast } from "vue3-toastify";
import TransactionFormModal from "./TransactionForm.vue";
import { Link } from "@inertiajs/vue3";
import TransactionDetailModal from "./TransactionDetailModal.vue";

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

// Transaction Detail Modal States
const showDetailsModal = ref(false);
const selectedTransaction = ref(null);

const openDetails = (txn) => {
    selectedTransaction.value = txn;
    showDetailsModal.value = true;
};

const countryFlag = (code) => {
    const map = {
        PAK: { label: "Pakistan", iso: "pk" },
        UAE: { label: "UAE", iso: "ae" },
        UK: { label: "United Kingdom", iso: "gb" },
    };

    const country = map[code] || { label: code, iso: code.toLowerCase() };
    return {
        label: country.label,
        img: `https://flagcdn.com/w40/${country.iso}.png`,
    };
};

const deleteItem = async () => {
    try {
        await axios.delete(`/accounting/transactions/${itemToDelete.value.id}`);
        toast.success(`Transaction deleted successfully`);
        refresh();
    } catch (e) {
        toast.error(
            e.response?.data?.message || "Failed to delete transaction."
        );
    } finally {
        itemToDelete.value = null;
    }
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
                    <thead
                        class="bg-gray-100 text-xs text-gray-700 uppercase text-center"
                    >
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3"></th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Account</th>
                            <th class="text-left py-3">Country</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Receipt #</th>
                            <th class="px-6 py-3">Type</th>
                            <th class="text-left px-5 py-3">Added By</th>
                            <th class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(account, index) in filteredAccounts"
                            :key="account.id"
                            class="border-b hover:bg-gray-50 text-center"
                        >
                            <td class="px-6 py-4">{{ index + 1 }}</td>

                            <td
                                class="px-6 py-4 text-blue-600 cursor-pointer"
                                @click="openDetails(account)"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mx-auto transition-transform duration-200 ease-in-out hover:scale-125"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                            </td>

                            <td class="px-6 py-4">{{ account.amount }}</td>
                            <td class="px-6 py-4">
                                {{ account.sourceable?.name }}
                            </td>
                            <td class="px-6 py-4">
                                <img
                                    :src="
                                        countryFlag(account.account_country).img
                                    "
                                    alt="flag"
                                    class="w-6 h-4 object-cover rounded shadow"
                                />
                                <!-- {{ countryFlag(account.account_country).label }} -->
                                <!-- {{ account.account_country }} -->
                            </td>
                            <td class="px-6 py-4">{{ account.date }}</td>
                            <td class="px-6 py-4">
                                {{
                                    account.incomes.length
                                        ? account.incomes[0].receipt_no
                                        : account.expenses.length
                                        ? account.expenses[0].receipt_no
                                        : "N/A"
                                }}
                            </td>
                            <td>
                                <span
                            :class="{
                                'bg-green-100 text-green-800':
                                    account.type === 'income',
                                'bg-red-100 text-red-800':
                                    account.type === 'expense',
                                'bg-yellow-100 text-yellow-800':
                                    account.type !== 'income' &&
                                    account.type !== 'expense',
                            }"
                            class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium"
                        >
                            {{ account.type.replaceAll('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}

                        </span>
                             
                            </td>
                            <td class="px-8 py-3 flex space-x-2">
                                <img
                                    :src="`https://i.pravatar.cc/40?u=${account.user_id}`"
                                    class="w-10 h-10 rounded-full"
                                    alt="User Avatar"
                                />
                                <!-- <span class="font-medium text-gray-800">{{
                                    account.user_id
                                }}</span> -->
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <!-- Edit -->
                                    <button
                                        @click="() => openModal(account)"
                                        title="Edit"
                                        class="p-2 rounded-full text-blue-600 hover:bg-blue-100"
                                    >
                                        <Pencil class="w-4 h-4" />
                                    </button>

                                    <!-- Delete -->
                                    <ConfirmModal
                                        :title="'Confirm Delete'"
                                        :message="`Are you sure you want to delete this transaction?`"
                                        :showDeleteButton="true"
                                        @confirm="
                                            () => {
                                                itemToDelete = account;
                                                deleteItem();
                                            }
                                        "
                                        @cancel="() => {}"
                                    />
                                </div>
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
            <TransactionDetailModal
                :transaction="selectedTransaction"
                :show="showDetailsModal"
                @close="showDetailsModal = false"
            />
        </div>
    </AppLayout>
</template>
