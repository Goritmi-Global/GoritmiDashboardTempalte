<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Multiselect from 'vue-multiselect';
import { toast } from 'vue3-toastify';


const props = defineProps({
  role: Object,
  permissions: Array,
});

console.log('permissions:', props.permissions);
const emit = defineEmits(['close', 'submitted']);
const isEdit = ref(!!props.role);
const form = useForm({
  name: props.role?.name || '',
  permissions: props.role?.permissions?.map(p => p.name) || []
});

// const permissionOptions = ref([]);
const permissionOptions = props.permissions;


watch(() => props.role, () => {
  isEdit.value = !!props.role;
  form.name = props.role?.name || '';
  form.permissions = props.role?.permissions?.map(p => p.name) || [];
});

const submit = () => {
  const url = isEdit.value ? `/roles/${props.role.id}` : '/roles';

  const options = {
    onSuccess: () => {
      toast.success(isEdit.value ? 'Role updated successfully' : 'Role created successfully');
      emit('submitted');
      emit('close');
    },
    onError: () => {
      toast.error('Something went wrong while saving the role.');
    },
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

      <h2 class="text-xl font-semibold mb-4">{{ isEdit ? 'Edit Role' : 'Create Role' }}</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
          <input
            v-model="form.name"
            type="text"
            required
            placeholder="Enter role name"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
          <Multiselect
            v-model="form.permissions"
            :options="permissionOptions"
            placeholder="Select permissions"
            :multiple="true"
            :taggable="true"
            tag-placeholder="Add permission"
            class="text-sm"
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

<style scoped>
.multiselect {
  min-height: 40px;
}
</style>