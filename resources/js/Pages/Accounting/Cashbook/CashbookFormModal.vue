<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue3-toastify';

const props = defineProps({
  cashbook: Object,
});
const emit = defineEmits(['close', 'submitted']);

const isEdit = ref(!!props.cashbook);

const form = useForm({
  name: props.cashbook?.name || '',
  balance: props.cashbook?.balance || '',
});

watch(() => props.cashbook, () => {
  isEdit.value = !!props.cashbook;
  form.name = props.cashbook?.name || '';
  form.balance = props.cashbook?.balance || '';
});

const submit = () => {
  const url = `/accounting/cashbook/${props.cashbook.id}`;

  form.put(url, {
    onSuccess: () => {
      toast.success('Cashbook updated successfully.');
      emit('submitted');
      emit('close');
    },
    onError: () => {
      toast.error('Please fix the errors.');
    },
  });
};
</script>

<template>
  <transition name="fade-modal">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
        <button
          class="absolute top-2 right-2 text-xl font-bold text-gray-500 hover:text-gray-800"
          @click="() => emit('close')"
        >×</button>

        <h2 class="text-xl font-semibold mb-4">Edit Cashbook</h2>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Enter name"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div v-if="form.errors.name" class="text-sm text-red-500 mt-1">{{ form.errors.name }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Balance</label>
            <input
              v-model="form.balance"
              type="number"
              placeholder="Enter balance"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div v-if="form.errors.balance" class="text-sm text-red-500 mt-1">{{ form.errors.balance }}</div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button
              type="button"
              @click="() => emit('close')"
              class="px-4 py-2 text-sm rounded bg-gray-200"
            >Cancel</button>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-4 py-2 text-sm rounded bg-[#296FB6] hover:bg-[#1f5a96] text-white"
            >
              Update
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
