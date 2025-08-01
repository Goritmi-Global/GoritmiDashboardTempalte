// File: resources/js/Pages/HR/Departments/Form.vue

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue3-toastify';

const props = defineProps({ department: Object });
const emit = defineEmits(['close', 'submitted']);
const isEdit = ref(!!props.department);

const form = useForm({
  title: props.department?.title || '',
  code: props.department?.code || '',
  description: props.department?.description || '',
});

watch(() => props.department, () => {
  isEdit.value = !!props.department;
  form.title = props.department?.title || '';
  form.code = props.department?.code || '';
  form.description = props.department?.description || '';
});

const submit = () => {
  const url = isEdit.value
    ? `/hr/departments/${props.department.id}`
    : `/hr/departments`;

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
        <button class="absolute top-2 right-2 text-xl font-bold text-gray-500 hover:text-gray-800" @click="() => emit('close')">×</button>
        <h2 class="text-xl font-semibold mb-4">{{ isEdit ? 'Edit Department' : 'Create Department' }}</h2>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input v-model="form.title" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <div v-if="form.errors.title" class="text-sm text-red-500 mt-1">{{ form.errors.title }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
            <input v-model="form.code" type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2" />
            <div v-if="form.errors.code" class="text-sm text-red-500 mt-1">{{ form.errors.code }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea v-model="form.description" class="w-full border border-gray-300 rounded-lg px-4 py-2" />
            <div v-if="form.errors.description" class="text-sm text-red-500 mt-1">{{ form.errors.description }}</div>
          </div>

          <div class="flex justify-end gap-2 pt-2">
            <button type="button" @click="() => emit('close')" class="px-4 py-2 text-sm rounded bg-gray-200">Cancel</button>
            <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm rounded bg-blue-600 hover:bg-blue-700 text-white">
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
