<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import axios from "axios";
import { ref, computed } from "vue";
import { toast } from "vue3-toastify";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    users: Object,
    roles: Object,
});

const showFilter = ref(false);
const search = ref("");
const selectedRoles = ref([]);

const toggleFilter = () => (showFilter.value = !showFilter.value);

const deleteUser = async (user) => {
    try {
        await axios.delete(`/users/${user.id}`);
        toast.success(`${user.name} deleted successfully`);

        router.reload();
    } catch (error) {
        toast.error("Failed to delete user."); 
    }
};

const isAdmin = (user) => user.roles.some((role) => role.name === "Admin");

const filteredUsers = computed(() => {
    return props.users.data.filter((user) => {
        const matchesSearch =
            user.name.toLowerCase().includes(search.value.toLowerCase()) ||
            user.email.toLowerCase().includes(search.value.toLowerCase());

        const matchesRole =
            selectedRoles.value.length === 0 ||
            user.roles.some((role) => selectedRoles.value.includes(role.name));

        return matchesSearch && matchesRole;
    });
});

const showModal = ref(false);
const userToDelete = ref(null);
const askDelete = (user) => {
    userToDelete.value = user;
    showModal.value = true;
};

const confirmDelete = () => {
    axios.delete(`/users/${userToDelete.value.id}`);
    showModal.value = false;
};
</script>

<template>
    <Head title="User Management" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-2">User Management</h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-2"
                        >
                            <li class="inline-flex items-center">
                                <Link
                                    href="/"
                                    class="text-gray-700 hover:text-blue-600 inline-flex items-center"
                                >
                                    <svg
                                        class="w-3 h-3 mr-2"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                                        />
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg
                                        class="w-3 h-3 text-gray-400 mx-1"
                                        fill="none"
                                        viewBox="0 0 6 10"
                                    >
                                        <path
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m1 9 4-4-4-4"
                                        />
                                    </svg>
                                    <span class="text-gray-500">Users</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <PrimaryLinkButton href="/users/create" label="Add New User" />
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div
                    class="flex flex-col px-4 py-3 space-y-3 md:flex-row md:items-center md:justify-between md:space-y-0"
                >
                    <div class="relative w-full md:w-1/3">
                        <div
                            class="absolute inset-y-0 left-0 flex items-center pl-3"
                        >
                            <svg
                                class="w-5 h-5 text-gray-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                />
                            </svg>
                        </div>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search by name or email"
                            class="pl-10 p-2 border border-gray-300 rounded-lg text-sm w-full"
                        />
                    </div>

                    <div class="relative">
                        <button
                            @click="toggleFilter"
                            class="flex items-center gap-1 px-3 py-2 text-sm border rounded-md"
                        >
                            <svg
                                class="w-4 h-4"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                />
                            </svg>
                            Filter
                        </button>
                        <div
                            v-show="showFilter"
                            class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20 p-3"
                        >
                            <h6 class="mb-2 text-sm font-medium">
                                Filter by Role
                            </h6>
                            <ul class="space-y-2 text-sm">
                                <li
                                    v-for="role in props.roles"
                                    :key="role.id"
                                    class="flex items-center"
                                >
                                    <input
                                        type="checkbox"
                                        :id="role.name"
                                        :value="role.name"
                                        v-model="selectedRoles"
                                        class="w-4 h-4 text-blue-600 rounded"
                                    />
                                    <label
                                        :for="role.name"
                                        class="ml-2 text-sm"
                                        >{{ role.name }}</label
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-700 font-semibold">
                        <tr>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Roles</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="user in filteredUsers"
                            :key="user.id"
                            class="border-b hover:bg-gray-50"
                        >
                            <td class="px-4 py-3 flex items-center space-x-2">
                                <img
                                    :src="`https://i.pravatar.cc/40?u=${user.id}`"
                                    class="w-10 h-10 rounded-full"
                                    alt="User Avatar"
                                />
                                <span class="font-medium text-gray-800">{{
                                    user.name
                                }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ user.email }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    v-for="role in user.roles"
                                    :key="role.id"
                                    class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded mr-1"
                                >
                                    {{ role.name }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <!-- Edit Button (use <Link> instead of <a>) -->
                                    <Link
                                        :href="`/users/${user.id}/edit`"
                                        class="inline-flex items-center justify-center p-2.5 rounded-full text-blue-600 hover:bg-blue-100"
                                        title="Edit"
                                    >
                                        <svg
                                            class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l10.414-10.414a1 1 0 000-1.414l-3.536-3.536a1 1 0 00-1.414 0L4.343 14.343A1 1 0 004 15.05V20z"
                                            />
                                        </svg>
                                    </Link>

                                    <!-- Delete Button inside ConfirmModal -->
                                    <ConfirmModal
                                        v-if="!isAdmin(user)"
                                        :title="'Confirm Delete'"
                                        :message="`Are you sure you want to delete ${user.name}?`"
                                        :showDeleteButton="true"
                                        @confirm="() => deleteUser(user)"
                                        @cancel="() => {}"
                                    />

                                    <!-- Admin Protected -->
                                    <span
                                        v-else
                                        class="text-xs text-gray-400 italic"
                                    >
                                        (admin protected)
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
