<script setup>
import { ref, watch } from "vue";
const props = defineProps({
    transaction: Object,
    show: Boolean,
});

const emit = defineEmits(["close"]);

// Dynamic border color based on transaction type
const borderColor = {
    income: "border-green-600",
    expense: "border-red-600",
    default: "border-yellow-600",
};

// Zooming image functionality States
const showImageModal = ref(false);
const previewImage = ref(null);
const openImageModal = (src) => {
    previewImage.value = src;
    showImageModal.value = true;
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
</script>

<template>
    <transition name="fade">
        <div
            v-if="show"
            class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div
                :class="[
                    'bg-white rounded-md shadow-xl w-full max-w-3xl relative p-6 border-l-4',
                    borderColor[transaction.type] || borderColor.default,
                ]"
            >
                <!-- Close Button -->
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

                <!-- Modal Header -->
                <h2
                    class="text-2xl font-bold mb-6 border-b pb-3 flex items-center gap-2"
                    :class="{
                        'text-green-700': transaction.type === 'income',
                        'text-red-700': transaction.type === 'expense',
                        'text-yellow-700':
                            transaction.type !== 'income' &&
                            transaction.type !== 'expense',
                    }"
                >
                    <svg
                        class="w-6 h-6"
                        :class="{
                            'text-green-500': transaction.type === 'income',
                            'text-red-500': transaction.type === 'expense',
                            'text-yellow-500':
                                transaction.type !== 'income' &&
                                transaction.type !== 'expense',
                        }"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m4 0v-6a2 2 0 00-2-2H7a2 2 0 00-2 2v6m0 0h14"
                        />
                    </svg>
                    Transaction Details
                </h2>

                <!-- Info Grid -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm text-gray-700"
                >
                    <div class="flex items-center gap-2">
                        <strong>Type:</strong>
                        <span
                            :class="{
                                'bg-green-100 text-green-800':
                                    transaction.type === 'income',
                                'bg-red-100 text-red-800':
                                    transaction.type === 'expense',
                                'bg-yellow-100 text-yellow-800':
                                    transaction.type !== 'income' &&
                                    transaction.type !== 'expense',
                            }"
                            class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium"
                        >
                            {{ transaction.type.toUpperCase() }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <strong>Account:</strong>
                        {{ transaction.sourceable?.name || "N/A" }}
                    </div>

                    <div class="flex items-center gap-2">
                        <strong>Country:</strong>
                        <img
                            :src="countryFlag(transaction.account_country).img"
                            alt="flag"
                            class="w-6 h-4 object-cover rounded shadow"
                        />
                        <span>{{
                            countryFlag(transaction.account_country).label
                        }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <strong>Amount:</strong> Rs. {{ transaction.amount }}
                    </div>

                    <div class="flex items-center gap-2">
                        <strong>Date:</strong> {{ transaction.date }}
                    </div>

                    <!-- <div class="flex items-center gap-2">
                        <strong>Reference:</strong>
                        {{ transaction.reference || "—" }}
                    </div> -->

                    <div class="flex items-center gap-2">
                        <strong>Description:</strong>
                        {{ transaction.description || "—" }}
                    </div>

                    <div class="flex items-center gap-2">
                        <strong>Receipt No:</strong>
                        {{
                            transaction.incomes?.[0]?.receipt_no ||
                            transaction.expenses?.[0]?.receipt_no ||
                            "—"
                        }}
                    </div>
                </div>

                <!-- Receipt Image -->
                <div class="mt-6">
                    <div
                        class="flex items-center gap-2 mb-2 text-sm font-semibold text-gray-600"
                    >
                        <svg
                            class="w-5 h-5 text-indigo-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 7l6 6-6 6M21 7l-6 6 6 6"
                            />
                        </svg>
                        Receipt Image:
                    </div>
 

                            <ImageZoomModal
                                :show="showImageModal"
                                :image="transaction.receipt_image_url"
                                @close="showImageModal = false"
                            />
                    <img
                        v-if="transaction.receipt_image_url"
                        :src="transaction.receipt_image_url"
                        alt="Receipt"
                         @click="openImageModal(transaction.receipt_image_url)"
                        class="max-h-64 rounded border hover:scale-105 transition-transform duration-300 h-24 mt-2 border rounded cursor-zoom-in"
                    />
                    <p v-else class="text-sm text-gray-400 italic">
                        No image available
                    </p>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

</style>
