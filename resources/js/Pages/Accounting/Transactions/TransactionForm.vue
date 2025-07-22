<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Multiselect from "vue-multiselect";
import { toast } from "vue3-toastify";

const props = defineProps({
    banks: Array,
    cashbook: Object,
    expenseTypes: Array,
    incomeTypes: Array,
    accounts: Array,
    transaction: Object,
});
console.log(
    "in the index page",
    props.banks,
    props.incomeTypes,
    props.expenseTypes
);

const emit = defineEmits(["close", "submitted"]);

const isEdit = ref(!!props.transaction);
const type = ref(props.transaction?.type || "income");

const form = useForm({
    type: props.transaction?.type || "income",
    account_type:
        props.transaction?.sourceable_type === "App\\Models\\Accounting\\Bank"
            ? "bank"
            : "cash",
    source_id: props.transaction?.sourceable_id || props.cashbook?.id || null,
    txn_type_id: props.transaction?.txn_type_id || null,
    amount: props.transaction?.amount || "",
    reference: props.transaction?.reference || "",
    description: props.transaction?.description || "",
    date: props.transaction?.date || new Date().toISOString().slice(0, 10),
});

watch(type, () => {
    form.type = type.value;
    form.txn_type_id = null;
});

const submit = () => {
    const url = isEdit.value
        ? `/accounting/transactions/${props.transaction.id}`
        : `/accounting/transactions`;

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

const showCropper = ref(false);
const selectedImage = ref(null);

const openCropper = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = () => {
        selectedImage.value = reader.result;
        showCropper.value = true;
    };
    reader.readAsDataURL(file);
};

const handleCropped = (base64) => {
    form.cropped_image = base64;
};
</script>

<template>
    <transition name="fade-modal">
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div
                class="bg-white w-full max-w-6xl rounded-lg shadow-lg p-6 relative"
            >
                <button
                    class="absolute top-2 right-2 text-xl font-bold text-gray-500 hover:text-gray-800"
                    @click="() => emit('close')"
                >
                    ×
                </button>

                <h2 class="text-xl font-semibold mb-4">
                    {{ isEdit ? "Edit Transaction" : "Add Transaction" }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="flex gap-4">
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

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Type</label
                        >
                        <Multiselect
                            v-model="form.txn_type_id"
                            :options="
                                type === 'income'
                                    ? props.incomeTypes
                                    : props.expenseTypes
                            "
                            :track-by="'id'"
                            :label="'name'"
                            placeholder="Select type"
                            class="w-full"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >From</label
                        >
                        <Multiselect
                            v-model="form.account_type"
                            :options="['bank', 'cash']"
                            placeholder="Select source type"
                            class="w-full"
                        />
                    </div>

                    <div v-if="form.account_type === 'bank'">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Bank Account</label
                        >
                        <Multiselect
                            v-model="form.source_id"
                            :options="props.banks || []"
                            :track-by="'id'"
                            :label="'name'"
                            placeholder="Select Bank"
                            class="w-full"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Amount</label
                        >
                        <input
                            type="number"
                            v-model="form.amount"
                            class="w-full border rounded px-3 py-2"
                            required
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Reference</label
                        >
                        <input
                            v-model="form.reference"
                            type="text"
                            class="w-full border rounded px-3 py-2"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            class="w-full border rounded px-3 py-2"
                            rows="3"
                        ></textarea>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Date</label
                        >
                        <input
                            type="date"
                            v-model="form.date"
                            class="w-full border rounded px-3 py-2"
                            required
                        />
                    </div>

                    <!-- Receipt No -->
                    <div>
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

                    <div>
                        <label class="block mb-1">Upload Receipt Image</label>
                        <!-- <input
                            type="file"
                            @change="openCropper"
                            accept="image/*"
                            class="mb-2"
                        /> -->
                        <ImageCropperModal
                            :show="showCropper"
                            :image="selectedImage"
                            @close="showCropper = false"
                            @cropped="handleCropped"
                        />
                        <button
                            @click="$refs.imageInput.click()"
                            class="rounded px-4 py-2 bg-blue-600 text-white"
                        >
                            Upload Image
                        </button>
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="bg-[#296FB6] text-white px-4 py-2 rounded hover:bg-[#1f5a96]"
                            :disabled="form.processing"
                        >
                            {{ isEdit ? "Update" : "Save" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </transition>
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
