<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue3-toastify';


const props = defineProps({
  permission: Object,
});

const emit = defineEmits(['close', 'submitted']);
 
const isEdit = ref(!!props.permission); 
const form = useForm({
  name: props.permission?.name || '',
});

watch(() => props.permission, () => {
  isEdit.value = !!props.permission;
  form.name = props.permission?.name || '';
});


const submit = () => {
  const url = isEdit.value ? `/permissions/${props.permission.id}` : '/permissions';

  const options = {
    onSuccess: () => {
      toast.success(isEdit.value ? 'Permission updated successfully' : 'Permission created successfully');
      emit('submitted');
      emit('close');
    },
    onError: () => {
      toast.error('Something went wrong. Please try again.');
    }
  };

  if (isEdit.value) {
    form.put(url, options);
  } else {
    form.post(url, options);
  }
};


</script>

<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
      <button
        class="absolute top-2 right-2 text-xl font-bold text-gray-500 hover:text-gray-800"
        @click="() => emit('close')"
      >×</button>

      <h2 class="text-xl font-semibold mb-4">{{ isEdit ? 'Edit Permission' : 'Create Permission' }}</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Permission Name</label>
          <input
            v-model="form.name"
            type="text"
            required
            placeholder="Enter permission name"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
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
            class="px-4 py-2 text-sm rounded bg-blue-600 text-white hover:bg-blue-700"
          >
            {{ isEdit ? 'Update' : 'Create' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
