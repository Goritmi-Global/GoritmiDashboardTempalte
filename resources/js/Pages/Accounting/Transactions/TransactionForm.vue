<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Multiselect from "vue-multiselect";
import { toast } from "vue3-toastify";
import ImageCropperModal from "@/Components/ImageCropperModal.vue";

const props = defineProps({
    banks: Array,
    cashbook: Object,
    expenseTypes: Array,
    incomeTypes: Array,
    accounts: Array,
    transaction: Object,
});

const emit = defineEmits(["close", "submitted"]);

const isEdit = ref(!!props.transaction);
const mainType = ref(props.transaction?.type || "income");
const accountCountry = ref("PAK");

const form = useForm({
    type: props.transaction?.type || "income",
    account_type:
        props.transaction?.sourceable_type === "App\\Models\\Accounting\\Bank"
            ? "bank"
            : "cash",
    account_country: props.transaction?.account_country || null,

    // 👇 Handle transfers & regular txn
    source_id:
        props.transaction?.type === "bank_to_cash"
            ? props.transaction?.bank_id
            : props.transaction?.type === "cash_to_bank"
            ? props.transaction?.cashbook_id
            : props.transaction?.sourceable_id || null,

    // 👇 Income/Expense type IDs
    txn_type_id:
        props.transaction?.incomes?.[0]?.income_type_id ||
        props.transaction?.expenses?.[0]?.expense_type_id ||
        null,

    amount: props.transaction?.amount || "",
    reference: props.transaction?.reference || "",
    description: props.transaction?.description || "",
    date: props.transaction?.date || new Date().toISOString().slice(0, 10),

    // 👇 Receipt #
    receipt_no:
        props.transaction?.incomes?.[0]?.receipt_no ||
        props.transaction?.expenses?.[0]?.receipt_no ||
        "",

    // 👇 Will be updated later
    cropped_image: null,

    // 👇 Only for cash_to_bank
    destination_bank_id:
        props.transaction?.type === "cash_to_bank"
            ? props.transaction?.bank_id
            : null,
});


watch(mainType, () => {
    form.type = mainType.value;
    form.txn_type_id = null;
    form.destination_bank_id = null;

    if (mainType.value === "bank_to_cash") {
        form.account_type = "bank";
    } else if (mainType.value === "cash_to_bank") {
        form.account_type = "cash";
        form.source_id = props.cashbook?.id || null;
    }
});

watch(
    () => form.account_type,
    (val) => {
        if (val === "cash" && ["income", "expense"].includes(mainType.value)) {
            form.source_id = props.cashbook?.id || null;
        }
    }
);

const showCropper = ref(false);
const selectedImage = ref(null);

const handleCropped = (base64) => {
    form.cropped_image = base64;
};
const submit = () => {
    const url = isEdit.value
        ? route("accounting.transactions.update", props.transaction.id)
        : route("accounting.transactions.store");

    const options = {
        onSuccess: () => {
            toast.success(
                isEdit.value ? "Transaction updated" : "Transaction created"
            );
            emit("submitted");
            emit("close");
        },
        onError: () => toast.error("Please fix the validation errors."),
    };

    isEdit.value ? form.put(url, options) : form.post(url, options);
};

// Zooming image functionality States
const showImageModal = ref(false);
const previewImage = ref(null);
const openImageModal = (src) => {
    previewImage.value = src;
    showImageModal.value = true;
};
</script>

<template>
    <transition name="fade-modal">
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div
                class="bg-white w-full max-w-5xl rounded-lg shadow-lg p-6 relative"
            >
                <button
                    class="absolute top-2 right-2 p-2 rounded-full hover:bg-gray-100 transition transform hover:scale-110"
                    @click="$emit('close')"
                    title="Close"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-red-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

                <h2 class="text-xl font-semibold mb-4">
                    {{ isEdit ? "Edit Transaction" : "Add Transaction" }}
                </h2>

                <form @submit.prevent="submit" class="grid grid-cols-12 gap-4">
                    <!-- Main Fields -->
                    <div class="col-span-12 md:col-span-12">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Account Country</label
                        >
                        <Multiselect
                            v-model="form.account_country"
                            :options="['PAK', 'UK', 'UAE']"
                            placeholder="Select Country"
                            class="w-full"
                        />
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Transaction Mode</label
                        >
                        <Multiselect
                            v-model="mainType"
                            :options="[
                                'income',
                                'expense',
                                'bank_to_cash',
                                'cash_to_bank',
                            ]"
                            placeholder="Select transaction mode"
                            class="w-full"
                        />
                    </div>

                    <div
                        class="col-span-12 md:col-span-6"
                        v-if="['income', 'expense'].includes(mainType)"
                    >
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1 text-capitalize"
                            >{{
                                mainType.charAt(0).toUpperCase() +
                                mainType.slice(1)
                            }}
                            Type
                        </label>
                        <Multiselect
                            :modelValue="
                                (mainType === 'income'
                                    ? props.incomeTypes
                                    : props.expenseTypes
                                ).find((t) => t.id === form.txn_type_id) || null
                            "
                            @update:modelValue="
                                (val) => (form.txn_type_id = val?.id)
                            "
                            :options="
                                mainType === 'income'
                                    ? props.incomeTypes
                                    : props.expenseTypes
                            "
                            :track-by="'id'"
                            :label="'name'"
                            placeholder="Select type"
                            class="w-full"
                        />
                    </div>

                    <div
                        class="col-span-12 md:col-span-6"
                        v-if="['income', 'expense'].includes(mainType)"
                    >
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Account Type</label
                        >
                        <Multiselect
                            v-model="form.account_type"
                            :options="['bank', 'cash']"
                            placeholder="Select source type"
                            class="w-full"
                        />
                    </div>

                    <div
                        class="col-span-12 md:col-span-6"
                        v-if="
                            form.account_type === 'bank' &&
                            ['income', 'expense', 'bank_to_cash'].includes(
                                mainType
                            )
                        "
                    >
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Select Bank</label
                        >
                        <Multiselect
                            :modelValue="
                                props.banks.find(
                                    (b) => b.id === form.source_id
                                ) || null
                            "
                            @update:modelValue="
                                (val) => (form.source_id = val?.id)
                            "
                            :options="props.banks"
                            :track-by="'id'"
                            :label="'name'"
                            placeholder="Select bank"
                            class="w-full"
                        />
                    </div>

                    <div
                        class="col-span-12 md:col-span-6"
                        v-if="mainType === 'cash_to_bank'"
                    >
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >To Bank</label
                        >
                        <Multiselect
                            :modelValue="
                                props.banks.find(
                                    (b) => b.id === form.destination_bank_id
                                ) || null
                            "
                            @update:modelValue="
                                (val) => (form.destination_bank_id = val?.id)
                            "
                            :options="props.banks"
                            :track-by="'id'"
                            :label="'name'"
                            placeholder="Select destination bank"
                            class="w-full"
                        />
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Amount</label
                        >
                        <input
                            type="number"
                            v-model="form.amount"
                            class="w-full border rounded px-3 py-2"
                        />
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Date</label
                        >
                        <input
                            type="date"
                            v-model="form.date"
                            class="w-full border rounded px-3 py-2"
                        />
                    </div>

                    <!-- <div class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Reference</label
                        >
                        <input
                            v-model="form.reference"
                            type="text"
                            class="w-full border rounded px-3 py-2"
                        />
                    </div> -->

                    <div class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Receipt No</label
                        >
                        <input
                            v-model="form.receipt_no"
                            type="text"
                            class="w-full border rounded px-3 py-2"
                            placeholder="Enter receipt number"
                        />
                    </div>

                    <div class="col-span-12">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Notes</label
                        >
                        <textarea
                            v-model="form.description"
                            class="w-full border rounded px-3 py-2"
                            rows="3"
                        ></textarea>
                    </div>

                    <!-- Receipt Cropper Section -->
                    <div class="col-span-12">
                        <label class="block mb-1">Upload Receipt Image</label>

                        <button
                            type="button"
                            @click="showCropper = true"
                            class="rounded px-4 py-2 bg-blue-600 text-white"
                        >
                            Upload & Crop Image
                        </button>

                        <div v-if="form.cropped_image" class="mt-3">
                            <p class="text-green-600 text-sm">
                                ✅ Receipt image attached
                            </p>
                            <!-- <img :src="form.cropped_image" class="h-24 mt-2 border rounded" /> -->
                            <img
                                :src="form.cropped_image"
                                @click="openImageModal(form.cropped_image)"
                                class="h-24 mt-2 border rounded cursor-zoom-in"
                            />

                            <ImageZoomModal
                                :show="showImageModal"
                                :image="previewImage"
                                @close="showImageModal = false"
                            />
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="col-span-12 flex justify-end">
                        <button
                            type="submit"
                            class="bg-[#296FB6] text-white px-6 py-2 rounded hover:bg-[#1f5a96]"
                            :disabled="form.processing"
                        >
                            {{ isEdit ? "Update" : "Save" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </transition>

    <ImageCropperModal
        :show="showCropper"
        @close="showCropper = false"
        @cropped="handleCropped"
    />
</template>

<style scoped>
.fade-modal-enter-active,
.fade-modal-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-modal-enter-from,
.fade-modal-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
.fade-modal-enter-to,
.fade-modal-leave-from {
    opacity: 1;
    transform: scale(1);
}
</style>
