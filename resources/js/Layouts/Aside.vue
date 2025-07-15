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
                class="block py-2 px-4 rounded hover:bg-gray-100"
                :class="{
                    'text-blue-600 font-semibold': route().current('dashboard'),
                }"
                >Dashboard</Link
            >

            <Link
                href="/accounting"
                class="block py-2 px-4 rounded hover:bg-gray-100"
                :class="{
                    'text-blue-600 font-semibold':
                        route().current('accounting'),
                }"
                >Accounting</Link
            >

            <Link
                href="/crm"
                class="block py-2 px-4 rounded hover:bg-gray-100"
                :class="{
                    'text-blue-600 font-semibold': route().current('crm'),
                }"
                >CRM</Link
            >

            <Link
                href="/hr"
                class="block py-2 px-4 rounded hover:bg-gray-100"
                :class="{
                    'text-blue-600 font-semibold': route().current('hr'),
                }"
                >HR</Link
            >

            <!-- Projects Dropdown -->
            <div>
                <button
                    @click="projectsOpen = !projectsOpen"
                    class="flex justify-between w-full py-2 px-4 rounded hover:bg-gray-100 text-left"
                    :class="{ 'text-blue-600 font-semibold': isProjectActive }"
                >
                    <span>Projects</span>
                    <svg
                        class="w-4 h-4 transition-transform"
                        :class="{ 'rotate-180': projectsOpen }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>
                </button>

                <div
                    v-show="projectsOpen"
                    class="pl-6 mt-1 space-y-1 transition-all"
                >
                    <Link
                        href="/projects/new"
                        class="block py-2 px-4 rounded hover:bg-gray-100 text-sm"
                        :class="{
                            'text-blue-600 font-semibold':
                                route().current('projects.new'),
                        }"
                        >New Projects</Link
                    >

                    <Link
                        href="/projects/existing"
                        class="block py-2 px-4 rounded hover:bg-gray-100 text-sm"
                        :class="{
                            'text-blue-600 font-semibold':
                                route().current('projects.existing'),
                        }"
                        >Existing Projects</Link
                    >

                    <Link
                        href="/projects/dealing"
                        class="block py-2 px-4 rounded hover:bg-gray-100 text-sm"
                        :class="{
                            'text-blue-600 font-semibold':
                                route().current('projects.dealing'),
                        }"
                        >Dealing Projects</Link
                    >
                </div>
            </div>
        </nav>
    </aside>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";

const sidebarOpen = ref(true);

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
