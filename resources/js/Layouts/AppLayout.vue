<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar (Part 1) -->
        <aside
            :class="[
                'transition-all duration-300',
                sidebarOpen ? 'w-64' : 'w-0 overflow-hidden',
            ]"
            class="bg-white border-r border-gray-200"
        >
            <div class="h-16 flex items-center justify-center border-b">
                <Link :href="route('dashboard')">
                    <!-- <img
        src="https://goritmi.co.uk/images/logo1.png"
        alt="Goritmi Logo"
        class="block h-9 w-auto"
    /> -->
                    <img
                        src="https://goritmi.co.uk/images/logo.png"
                        alt="Goritmi Logo"
                        class="block h-16 w-auto"
                    />
                </Link>
            </div>
            <nav class="p-4">
                <Link
                    href="/dashboard"
                    class="block py-2 px-4 rounded hover:bg-gray-100"
                    :class="{
                        'text-blue-600 font-semibold':
                            route().current('dashboard'),
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
                <Link
                    href="/projects"
                    class="block py-2 px-4 rounded hover:bg-gray-100"
                    :class="{
                        'text-blue-600 font-semibold':
                            route().current('projects'),
                    }"
                    >Projects</Link
                >
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar (Part 2) -->
            <header
                class="bg-white shadow border-b h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8"
            >
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none"
                    >
                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold">
                        {{ $page.component.split("/").pop() }}
                    </h1>
                </div>
                <div class="flex items-center">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 hover:text-gray-700 focus:outline-none"
                                >
                                    {{ $page.props.auth.user.name }}
                                    <svg
                                        class="-me-0.5 ms-2 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')"
                                >Profile</DropdownLink
                            >
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                >Log Out</DropdownLink
                            >
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

const sidebarOpen = ref(true);
</script>
