<template>
    <aside
        :class="[
            'transition-all duration-300',
            sidebarOpen ? 'w-72' : 'w-0 overflow-hidden',
        ]"
        class="bg-[#296FB6] text-white border-r border-gray-200 shadow-lg"
    >
        <!-- Navigation -->
        <nav class="py-6 px-4 space-y-2">
            <NavItem
                icon="home"
                text="Dashboard"
                href="/dashboard"
                :active="route().current('dashboard')"
            />

            <NavItem
                icon="banknotes"
                text="Accounting"
                href="/accounting"
                :active="route().current('accounting')"
            />

            <NavItem
                icon="user-group"
                text="CRM"
                href="/crm"
                :active="route().current('crm')"
            />

            <!-- Grouped Section -->
            <div class="pt-4">
                <p
                    class="text-xs text-white/60 uppercase tracking-widest mb-1 px-2"
                >
                    Projects
                </p>
                <DisclosureItem
                    icon="folder"
                    text="Projects"
                    :open="projectsOpen"
                    @toggle="projectsOpen = !projectsOpen"
                    :active="isProjectActive"
                >
                    <NavItem
                        icon="plus"
                        text="New Projects"
                        href="/projects/new"
                        :active="route().current('projects.new')"
                        small
                    />
                    <NavItem
                        icon="briefcase"
                        text="Existing"
                        href="/projects/existing"
                        :active="route().current('projects.existing')"
                        small
                    />
                    <NavItem
                        icon="hand-thumb-up"
                        text="Dealing"
                        href="/projects/dealing"
                        :active="route().current('projects.dealing')"
                        small
                    />
                </DisclosureItem>
            </div>

            <!-- Users Section -->
            <div class="pt-4">
                <p
                    class="text-xs text-white/60 uppercase tracking-widest mb-1 px-2"
                >
                    Users
                </p>
                <DisclosureItem
                    icon="user-group"
                    text="Users"
                    :open="usersOpen"
                    @toggle="usersOpen = !usersOpen"
                    :active="isUserSubmenuActive"
                >
                    <NavItem
                        icon="document"
                        text="Manage Users"
                        href="/users"
                        :active="route().current('users.index')"
                        small
                    />
                    <NavItem
                        icon="document"
                        text="Roles & Permissions"
                        href="/roles-permissions"
                        :active="route().current('roles-permissions')"
                        small
                    />
                </DisclosureItem>
            </div>
        </nav>
    </aside>
</template>

<script setup>
import { computed, ref } from "vue";

import NavItem from "@/Components/NavItem.vue";
import DisclosureItem from "@/Components/DisclosureItem.vue";

import {
    HomeIcon,
    BanknotesIcon,
    UserGroupIcon,
    FolderIcon,
    BriefcaseIcon,
    PlusIcon,
    HandThumbUpIcon,
    DocumentIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    sidebarOpen: Boolean,
});

const activeDropdown = ref(null);

const projectsOpen = ref(
    route().current("projects.new") ||
        route().current("projects.existing") ||
        route().current("projects.dealing")
);

const usersOpen = ref(
    route().current("users.index") ||
        route().current("users") ||
        route().current("roles-permissions")
);

const isProjectActive = computed(
    () =>
        route().current("projects") ||
        route().current("projects.new") ||
        route().current("projects.existing") ||
        route().current("projects.dealing")
);

const isUserSubmenuActive = computed(
    () =>
        route().current("users.index") ||
        route().current("users") ||
        route().current("roles-permissions")
);
</script>
