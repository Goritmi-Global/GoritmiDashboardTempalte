<template>
  <Link
    :href="href"
    class="flex items-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-[#296FB6] hover:bg-[#1f5a96] text-white"
  >
    <!-- Use custom icon slot if provided -->
    <slot name="icon">
      <svg
        class="h-4 w-4 mr-2"
        fill="currentColor"
        viewBox="0 0 20 20"
      >
        <path
          clip-rule="evenodd"
          fill-rule="evenodd"
          d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
        />
      </svg>
    </slot>
    <span>{{ label }}</span>
  </Link>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
  href: { type: String, required: true },
  label: { type: String, required: true },
});
</script>
