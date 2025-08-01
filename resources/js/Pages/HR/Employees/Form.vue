<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { toast } from 'vue3-toastify';

const props = defineProps({
  employee: Object,
  departments: Array
});
const emit = defineEmits(['close', 'submitted']);
const isEdit = ref(!!props.employee);

const form = useForm({
  name: props.employee?.name || '',
  nic: props.employee?.nic || '',
  email: props.employee?.email || '',
  designation: props.employee?.designation || '',
  contact: props.employee?.contact || '',
  nationality: props.employee?.nationality || '',
  dob: props.employee?.dob || '',
  address: props.employee?.address || '',
  gender: props.employee?.gender || '',
  marital_status: props.employee?.marital_status || '',
  status: props.employee?.status || 'active',
  department_id: props.employee?.department_id || '',
});

watch(() => props.employee, () => {
  isEdit.value = !!props.employee;
  Object.keys(form.data()).forEach(key => {
    form[key] = props.employee?.[key] || (key === 'status' ? 'active' : '');
  });
});

const submit = () => {
  const url = isEdit.value
    ? `/hr/employees/${props.employee.id}`
    : `/hr/employees`;

  const options = {
    onSuccess: () => {
      toast.success(isEdit.value ? 'Employee updated' : 'Employee created');
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
      <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 relative">
        <button class="absolute top-2 right-2 text-xl font-bold text-gray-500 hover:text-gray-800" @click="() => emit('close')">×</button>
        <h2 class="text-xl font-semibold mb-4">{{ isEdit ? 'Edit Employee' : 'Create Employee' }}</h2>

        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label>Name</label>
            <input v-model="form.name" type="text" class="form-input w-full" />
            <div v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</div>
          </div>

          <div>
            <label>NIC</label>
            <input v-model="form.nic" type="text" class="form-input w-full" />
            <div v-if="form.errors.nic" class="text-sm text-red-500">{{ form.errors.nic }}</div>
          </div>

          <div>
            <label>Email</label>
            <input v-model="form.email" type="email" class="form-input w-full" />
            <div v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</div>
          </div>

          <div>
            <label>Designation</label>
            <input v-model="form.designation" type="text" class="form-input w-full" />
          </div>

          <div>
            <label>Contact</label>
            <input v-model="form.contact" type="text" class="form-input w-full" />
          </div>

          <div>
            <label>Nationality</label>
            <input v-model="form.nationality" type="text" class="form-input w-full" />
          </div>

          <div>
            <label>Date of Birth</label>
            <input v-model="form.dob" type="date" class="form-input w-full" />
          </div>

          <div>
            <label>Department</label>
            <select v-model="form.department_id" class="form-select w-full">
              <option value="" disabled>Select department</option>
              <option v-for="dept in departments" :key="dept.id" :value="dept.id">{{ dept.title }}</option>
            </select>
            <div v-if="form.errors.department_id" class="text-sm text-red-500">{{ form.errors.department_id }}</div>
          </div>

          <div>
            <label>Gender</label>
            <select v-model="form.gender" class="form-select w-full">
              <option value="">Select</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div>
            <label>Marital Status</label>
            <select v-model="form.marital_status" class="form-select w-full">
              <option value="">Select</option>
              <option value="single">Single</option>
              <option value="married">Married</option>
              <option value="divorced">Divorced</option>
              <option value="widowed">Widowed</option>
            </select>
          </div>

          <div>
            <label>Status</label>
            <select v-model="form.status" class="form-select w-full">
              <option value="active">Active</option>
              <option value="on_leave">On Leave</option>
              <option value="resigned">Resigned</option>
              <option value="terminated">Terminated</option>
            </select>
          </div>

          <div class="md:col-span-2">
            <label>Address</label>
            <textarea v-model="form.address" class="form-textarea w-full"></textarea>
          </div>

          <div class="md:col-span-2 text-right pt-4">
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
