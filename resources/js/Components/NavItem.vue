<template>
  <Link
    :href="href"
    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all duration-200"
    :class="[
      active
        ? 'bg-white/20 font-semibold'
        : 'hover:bg-white/10 text-white/90',
      small ? 'pl-8 text-sm' : '',
    ]"
  >
    <component :is="iconMap[icon]" class="w-5 h-5" />
    <span>{{ text }}</span>
  </Link>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import {
  HomeIcon,
  BanknotesIcon,
  UserGroupIcon,
  FolderIcon,
  ChevronDownIcon,
  BriefcaseIcon,
  PlusIcon,
  HandThumbUpIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
  icon: String,
  text: String,
  href: String,
  active: Boolean,
  small: Boolean,
});

const iconMap = {
  home: HomeIcon,
  banknotes: BanknotesIcon,
  "user-group": UserGroupIcon,
  folder: FolderIcon,
  "chevron-down": ChevronDownIcon,
  briefcase: BriefcaseIcon,
  plus: PlusIcon,
  "hand-thumb-up": HandThumbUpIcon,
};
</script>
