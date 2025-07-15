<template>
    <aside
        :class="[
            'transition-all duration-300',
            sidebarOpen ? 'w-64' : 'w-0 overflow-hidden',
        ]"
        class="bg-[#296FB6] text-white border-r border-gray-200"
    >
        <!-- Logo -->
        <div class="h-16 flex items-center justify-center border-b">
            <Link :href="route('dashboard')">
                <img
                    src="https://goritmi.co.uk/images/logo1.png"
                    alt="Goritmi Logo"
                    class="block h-16 w-auto"
                />
            </Link>
        </div>

        <!-- Navigation -->
        <nav class="p-4">
            <Link
                href="/dashboard"
                class="flex items-center gap-2 py-2 px-4 rounded transition hover:bg-[#1f5b99]"
                :class="{
                    'bg-[#1f5b99] text-white font-semibold':
                        route().current('dashboard'),
                }"
            >
                <HomeIcon class="w-5 h-5" />
                Dashboard
            </Link>

            <Link
                href="/accounting"
                class="flex items-center gap-2 py-2 px-4 rounded transition hover:bg-[#1f5b99]"
                :class="{
                    'bg-[#1f5b99] text-white font-semibold':
                        route().current('accounting'),
                }"
                ><BanknotesIcon class="w-5 h-5" />Accounting</Link
            >

            <Link
                href="/crm"
                class="flex items-center gap-2 py-2 px-4 rounded transition hover:bg-[#1f5b99]"
                :class="{
                    'bg-[#1f5b99] text-white font-semibold':
                        route().current('crm'),
                }"
                ><UserGroupIcon class="w-5 h-5" />CRM</Link
            >

            <Link
                href="/hr"
                class="flex items-center gap-2 py-2 px-4 rounded transition hover:bg-[#1f5b99]"
                :class="{
                    'bg-[#1f5b99] text-white font-semibold':
                        route().current('hr'),
                }"
                ><BriefcaseIcon class="w-5 h-5" />HR</Link
            >

            <!-- Projects Dropdown -->
            <div>
                <button
                    @click="projectsOpen = !projectsOpen"
                    class="flex justify-between w-full py-2 px-4 rounded hover:bg-[#1f5b99] text-left"
                    :class="{
                        'bg-[#1f5b99] text-white font-semibold':
                            isProjectActive,
                    }"
                >
                    <span class="flex items-center gap-2">
                        <FolderIcon class="w-5 h-5" />
                        Projects
                    </span>
                    <ChevronDownIcon
                        class="w-4 h-4 transition-transform"
                        :class="{ 'rotate-180': projectsOpen }"
                    />
                </button>

                <div
                    v-show="projectsOpen"
                    class="pl-6 mt-1 space-y-1 transition-all"
                >
                    <Link
                        href="/projects/new"
                        class="flex items-center gap-2 py-2 px-4 rounded transition hover:bg-[#1f5b99] text-sm"
                        :class="{
                            'bg-[#1f5b99] text-white font-semibold':
                                route().current('projects.new'),
                        }"
                        ><PlusIcon class="w-5 h-5" />New Projects</Link
                    >

                    <Link
                        href="/projects/existing"
                        class="flex items-center gap-2 py-2 px-4 rounded transition hover:bg-[#1f5b99] text-sm"
                        :class="{
                            'bg-[#1f5b99] text-white font-semibold':
                                route().current('projects.existing'),
                        }"
                    >
                        <DocumentIcon class="w-5 h-5" />Existing Projects</Link
                    >

                    <Link
                        href="/projects/dealing"
                        class="flex items-center gap-2 py-2 px-4 rounded transition hover:bg-[#1f5b99] text-sm"
                        :class="{
                            'bg-[#1f5b99] text-white font-semibold':
                                route().current('projects.dealing'),
                        }"
                    >
                        <HandThumbUpIcon class="w-5 h-5" />Dealing
                        Projects</Link
                    >
                </div>
            </div>
        </nav>
    </aside>
</template>

<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    HomeIcon,
    BanknotesIcon,
    UsersIcon,
    BriefcaseIcon,
    FolderIcon,
    ChevronDownIcon,
    UserGroupIcon,
    PlusIcon,
    DocumentIcon, // ✅ used instead of ClipboardDocumentListIcon
    HandThumbUpIcon, // ✅ used instead of HandshakeIcon
} from "@heroicons/vue/24/outline";

const props = defineProps({
    sidebarOpen: Boolean,
});

const projectsOpen = ref(
    route().current("projects.new") ||
        route().current("projects.existing") ||
        route().current("projects.dealing")
);

const isProjectActive = computed(
    () =>
        route().current("projects") ||
        route().current("projects.new") ||
        route().current("projects.existing") ||
        route().current("projects.dealing")
);
</script>
