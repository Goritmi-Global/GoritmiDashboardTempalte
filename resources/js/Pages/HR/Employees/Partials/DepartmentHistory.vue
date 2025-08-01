<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({ employeeId: Number });
const history = ref([]);

const fetchHistory = async () => {
  console.log("test");
  const res = await axios.get(`/hr/employees/${props.employeeId}/departments`);
  history.value = res.data;
  console.log(res.data);
};

onMounted(fetchHistory);
</script>

<template>
  <div>
    <h2 class="text-lg font-semibold mb-4">Department History</h2>
    <table class="w-full text-sm text-left">
      <thead class="bg-gray-100 text-xs text-gray-700">
        <tr>
          <th class="px-4 py-2">Department</th>
          <th class="px-4 py-2">Assigned At</th>
          <th class="px-4 py-2">Remarks</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="record in history" :key="record.id" class="border-b">
          <td class="px-4 py-2">{{ record.department.title }}</td>
          <td class="px-4 py-2">{{ record.assigned_at }}</td>
          <td class="px-4 py-2">{{ record.remarks || '-' }}</td>
        </tr>
        <tr v-if="!history.length">
          <td colspan="3" class="text-center py-4 text-gray-400">No department history found.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
