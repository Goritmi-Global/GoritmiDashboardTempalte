<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue3-toastify';

const props = defineProps({
  bank: Object,
});
const emit = defineEmits(['close', 'submitted']);

const isEdit = ref(!!props.bank);

const form = useForm({
  name: props.bank?.name || '',
  account_number: props.bank?.account_number || '',
  branch: props.bank?.branch || '',
  balance: props.bank?.balance || 0,
});

watch(() => props.bank, () => {
  isEdit.value = !!props.bank;
  form.name = props.bank?.name || '';
  form.account_number = props.bank?.account_number || '';
  form.branch = props.bank?.branch || '';
  form.balance = props.bank?.balance || 0;
});

const submit = () => {
  const url = isEdit.value
    ? `/accounting/banks/${props.bank.id}`
    : `/accounting/banks`;

  const options = {
    onSuccess: () => {
      toast.success(isEdit.value ? 'Updated successfully' : 'Created successfully');
      emit('submitted');
      emit('close');
    },
    onError: () => {
      toast.error('Please fix the errors.');
    },
  };

  isEdit.value ? form.put(url, options) : form.post(url, options);
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

        <h2 class="text-xl font-semibold mb-4">{{ isEdit ? 'Edit Bank' : 'Create Bank' }}</h2>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
              v-model="form.name"
              type="text"
              placeholder="Enter bank name"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div v-if="form.errors.name" class="text-sm text-red-500 mt-1">{{ form.errors.name }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
            <input
              v-model="form.account_number"
              type="text"
              placeholder="Enter account number"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div v-if="form.errors.account_number" class="text-sm text-red-500 mt-1">{{ form.errors.account_number }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
            <input
              v-model="form.branch"
              type="text"
              placeholder="Enter branch name"
              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <div v-if="form.errors.branch" class="text-sm text-red-500 mt-1">{{ form.errors.branch }}</div>
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
              {{ isEdit ? 'Update' : 'Create' }}
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