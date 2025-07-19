<template>
  <div>
    <!-- Main Toggle Button -->
    <button
      @click="$emit('toggle')"
      class="flex items-center justify-between w-full px-3 py-2 rounded-lg hover:bg-white/10 transition"
      :class="{ 'bg-white/20 font-semibold': active }"
    >
      <div class="flex items-center gap-3">
        <component :is="iconMap[icon]" class="w-5 h-5" />
        <span>{{ text }}</span>
      </div>
      <ChevronDownIcon
        class="w-4 h-4 transform transition-transform duration-500"
        :class="{ 'rotate-180': open }"
      />
    </button>

    <!-- Dropdown Content with Transition -->
    <transition
      name="dropdown"
      enter-active-class="transition-all duration-1000 ease-in-out"
      leave-active-class="transition-all duration-1000 ease-in-out"
    >
      <div v-show="open" class="pl-2 pt-2 space-y-1 overflow-hidden">
        <slot />
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ChevronDownIcon } from "@heroicons/vue/24/outline";
import { FolderIcon, UserGroupIcon } from "@heroicons/vue/24/outline";

defineProps({
  icon: String,
  text: String,
  open: Boolean,
  active: Boolean,
});

const iconMap = {
  folder: FolderIcon,
  "user-group": UserGroupIcon,
};
</script>

<style scoped>
/* Optional: control max-height for smoother dropdown effect */
.dropdown-enter-from,
.dropdown-leave-to {
  max-height: 0;
  opacity: 0;
}
.dropdown-enter-to,
.dropdown-leave-from {
  max-height: 500px;
  opacity: 1;
}
.dropdown-enter-active,
.dropdown-leave-active {
  overflow: hidden;
}
</style>
