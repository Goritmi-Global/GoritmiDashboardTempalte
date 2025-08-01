<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';

const props = defineProps({ employeeId: Number });
const joining = ref(null);
const showModal = ref(false);

const form = useForm({
  joining_date: '',
  starting_salary: '',
  probation_from: '',
  probation_to: '',
  contract_type: 'full_time',
  notes: ''
});

const fetchJoining = async () => {
  try {
    const res = await axios.get(`/hr/employees/${props.employeeId}/joining`);
    joining.value = res.data?.[0] || null;

    if (joining.value) {
      form.joining_date = joining.value.joining_date;
      form.starting_salary = joining.value.starting_salary;
      form.probation_from = joining.value.probation_from;
      form.probation_to = joining.value.probation_to;
      form.contract_type = joining.value.contract_type;
      form.notes = joining.value.notes;
    }
  } catch (err) {
    joining.value = null;
  }
};

const openModal = () => {
  showModal.value = true;
};

const submit = () => {
  form.post(`/hr/employees/${props.employeeId}/joining`, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Joining saved.');
      showModal.value = false;
      fetchJoining();
    },
    onError: () => toast.error('Failed to save.')
  });
};

onMounted(fetchJoining);
</script>
<template>
  <div>
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold">Joining Information</h2>
      <button @click="openModal" class="bg-blue-600 text-white px-4 py-2 rounded">
        {{ joining ? 'Edit' : '+ Add' }} Joining
      </button>
    </div>

    <!-- Card View -->
    <div v-if="joining" class="rounded-xl shadow p-6 bg-white border border-gray-200">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p class="text-gray-500 text-sm">Joining Date</p>
          <p class="text-base font-medium text-gray-800">{{ joining.joining_date }}</p>
        </div>
        <div>
          <p class="text-gray-500 text-sm">Starting Salary</p>
          <p class="text-base font-medium text-gray-800">Rs {{ joining.starting_salary }}</p>
        </div>
        <div>
          <p class="text-gray-500 text-sm">Probation Period</p>
          <p class="text-base font-medium text-gray-800">
            {{ joining.probation_from ?? '—' }} to {{ joining.probation_to ?? '—' }}
          </p>
        </div>
        <div>
          <p class="text-gray-500 text-sm">Contract Type</p>
          <p class="text-base font-medium text-gray-800 capitalize">
            {{ joining.contract_type.replace('_', ' ') }}
          </p>
        </div>
        <div class="md:col-span-2">
          <p class="text-gray-500 text-sm">Notes</p>
          <p class="text-base font-medium text-gray-800">{{ joining.notes || '—' }}</p>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <transition name="fade">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
          <button class="absolute top-2 right-2 text-gray-600 text-xl" @click="showModal = false">×</button>
          <h2 class="text-lg font-bold mb-4">Joining Form</h2>

          <form @submit.prevent="submit" class="grid gap-4 md:grid-cols-2">
            <div>
              <label>Joining Date</label>
              <input v-model="form.joining_date" type="date" class="form-input w-full" />
              <div class="text-red-500 text-sm">{{ form.errors.joining_date }}</div>
            </div>

            <div>
              <label>Starting Salary</label>
              <input v-model="form.starting_salary" type="number" class="form-input w-full" />
              <div class="text-red-500 text-sm">{{ form.errors.starting_salary }}</div>
            </div>

            <div>
              <label>Probation From</label>
              <input v-model="form.probation_from" type="date" class="form-input w-full" />
            </div>

            <div>
              <label>Probation To</label>
              <input v-model="form.probation_to" type="date" class="form-input w-full" />
            </div>

            <div>
              <label>Contract Type</label>
              <select v-model="form.contract_type" class="form-select w-full">
                <option value="full_time">Full Time</option>
                <option value="part_time">Part Time</option>
                <option value="contract">Contract</option>
                <option value="internship">Internship</option>
              </select>
            </div>

            <div class="md:col-span-2">
              <label>Notes</label>
              <textarea v-model="form.notes" class="form-textarea w-full"></textarea>
            </div>

            <div class="md:col-span-2 text-right">
              <button type="button" @click="showModal = false" class="bg-gray-200 px-4 py-2 rounded mr-2">Cancel</button>
              <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-4 py-2 rounded">
                Save
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>
  </div>
</template>
