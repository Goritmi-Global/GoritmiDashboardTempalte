<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({ employeeId: Number });
const salaries = ref([]);

const fetchSalaries = async () => {
  const res = await axios.get(`/hr/employees/${props.employeeId}/salaries`);
  salaries.value = res.data;
};

onMounted(fetchSalaries);
</script>

<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Salary History</h2>
    <table class="w-full text-sm text-left">
      <thead class="bg-gray-100 text-xs text-gray-700">
        <tr>
          <th class="px-4 py-2">Amount</th>
          <th class="px-4 py-2">Effective From</th>
          <th class="px-4 py-2">Effective To</th>
          <th class="px-4 py-2">Reason</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="salary in salaries" :key="salary.id" class="border-b">
          <td class="px-4 py-2">{{ salary.amount }}</td>
          <td class="px-4 py-2">{{ salary.effective_from }}</td>
          <td class="px-4 py-2">{{ salary.effective_to || '-' }}</td>
          <td class="px-4 py-2">{{ salary.reason || '-' }}</td>
        </tr>
        <tr v-if="!salaries.length">
          <td colspan="4" class="text-center py-4 text-gray-400">No salary records found.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>