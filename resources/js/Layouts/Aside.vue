<template>
  <aside
    :class="[
      'transition-all duration-300',
      sidebarOpen ? 'w-72' : 'w-0 overflow-hidden',
    ]"
    class="bg-[#296FB6] text-white border-r border-gray-200"
  >
    <nav class="py-10 px-3">
        <!-- Navigation -->
        
    <Link
        href="/dashboard"
        class="sidebar-link"
        :class="{ 'sidebar-link-active': route().current('dashboard') }"
    >
        <HomeIcon class="w-5 h-5" />
        Dashboard
    </Link>

    <Link
        href="/accounting"
        class="sidebar-link"
        :class="{ 'sidebar-link-active': route().current('accounting') }"
    >
        <BanknotesIcon class="w-5 h-5" />
        Accounting
    </Link>

    <Link
        href="/crm"
        class="sidebar-link"
        :class="{ 'sidebar-link-active': route().current('crm') }"
    >
        <UserGroupIcon class="w-5 h-5" />
        CRM
    </Link>

    <!-- Projects Dropdown -->
    <div>
        <button
            @click="projectsOpen = !projectsOpen"
            class="sidebar-link justify-between text-left"
            :class="{ 'sidebar-link-active': isProjectActive }"
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

        <Transition name="sidebar-dropdown">
            <div
                v-show="projectsOpen"
                class="space-y-1 bg-[#296FB6] transition-all"
            >
                <Link
                    href="/projects/new"
                    class="sidebar-link text-sm"
                    :class="{ 'sidebar-link-active': route().current('projects.new') }"
                >
                    <PlusIcon class="w-5 h-5" />
                    New Projects
                </Link>

                <Link
                    href="/projects/existing"
                    class="sidebar-link text-sm"
                    :class="{ 'sidebar-link-active': route().current('projects.existing') }"
                >
                    <BriefcaseIcon class="w-5 h-5" />
                    Existing Projects
                </Link>

                <Link
                    href="/projects/dealing"
                    class="sidebar-link text-sm"
                    :class="{ 'sidebar-link-active': route().current('projects.dealing') }"
                >
                    <HandThumbUpIcon class="w-5 h-5" />
                    Dealing Projects
                </Link>
            </div>
        </Transition>
    </div>

    <Link
        href="/hr"
        class="sidebar-link"
        :class="{ 'sidebar-link-active': route().current('hr') }"
    >
        <DocumentIcon class="w-5 h-5" />
        HR
    </Link>
    <!-- Users Dropdown -->
<div>
    <button
        @click="usersOpen = !usersOpen"
        class="sidebar-link justify-between text-left"
        :class="{ 'sidebar-link-active': isUserSubmenuActive }"
    >
        <span class="flex items-center gap-2">
            <UsersIcon class="w-5 h-5" />
            Users 
        </span>
        <ChevronDownIcon
            class="w-4 h-4 transition-transform"
            :class="{ 'rotate-180': usersOpen }"
        />
    </button>

    <Transition name="sidebar-dropdown">
        <div
            v-show="usersOpen"
            class="space-y-1 bg-[#296FB6] transition-all"
        >
            <Link
                href="/users"
                class="sidebar-link text-sm mt-1"
                :class="{ 'sidebar-link-active': route().current('users.index') }"
            >
                <DocumentIcon class="w-5 h-5" />
                Manage Users
            </Link>

            <Link
                href="/roles-permissions"
                class="sidebar-link text-sm"
                :class="{ 'sidebar-link-active': route().current('roles-permissions') }"
            >
                <DocumentIcon class="w-5 h-5" />
                Roles & Permissions
            </Link>
        </div>
    </Transition>
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
const usersOpen = ref(
    route().current("users.index") ||
    route().current("users") ||
    route().current("roles-permissions")
);

const isUserSubmenuActive = computed(
    () =>
        route().current("users.index") ||
        route().current("users") ||
        route().current("roles-permissions")
);


</script>
