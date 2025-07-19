<script setup>
console.log('PrimaryModalButton mounted');
const props = defineProps({
    label: { type: String, required: true },
});
</script>

<template>
  <button
    type="button"
    @click="$emit('click')"
    class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-600 hover:bg-blue-700"
  >
    <slot name="icon" />
    <span>{{ label }}</span>
  </button>
</template>

