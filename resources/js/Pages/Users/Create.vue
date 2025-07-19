<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { computed } from "vue";
import Multiselect from "vue-multiselect";

const props = defineProps({
    user: Object,
    roles: Array,
    userRoles: Array,
});

const isEdit = computed(() => !!props.user);

const form = useForm({
    name: props.user?.name || "",
    email: props.user?.email || "",
    password: "",
    password_confirmation: "",
    roles: props.user?.roles?.map((r) => r.name) || [],
});

const submit = () => {
    form.roles = form.roles.map((role) => role.name);

    if (isEdit.value) {
        form.put(`/users/${props.user.id}`);
    } else {
        form.post("/users");
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Edit User' : 'Create User'" />
    <AppLayout>
        <div class="p-6 space-y-6 mx-auto">
            <!-- Header + Breadcrumbs -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-2">
                        {{ isEdit ? "Edit User" : "Create User" }}
                    </h1>

                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-2"
                        >
                            <li class="inline-flex items-center">
                                <Link
                                    href="/"
                                    class="text-gray-600 hover:text-blue-600 inline-flex items-center"
                                >
                                    <svg
                                        class="w-4 h-4 mr-1"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M10 3.172L3.172 10H6v6h8v-6h2.828L10 3.172z"
                                        />
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li class="flex items-center text-gray-500">
                                <svg
                                    class="w-4 h-4 mx-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                                Users
                            </li>
                            <li class="flex items-center text-gray-500">
                                <svg
                                    class="w-4 h-4 mx-1"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                                {{ isEdit ? "Edit" : "Create" }}
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Add User Button -->

                <PrimaryLinkButton href="/users" label="View All">
                    <template #icon>
                        <svg
                            class="h-4 w-4 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M3 5h14M3 10h14M3 15h14"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </template>
                </PrimaryLinkButton>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <form @submit.prevent="submit" class="grid grid-cols-12 gap-4">
                    <!-- Name -->
                    <div class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="Enter full name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400"
                        />
                    </div>

                    <!-- Email -->
                    <div class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Email</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="Enter email address"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400"
                        />
                    </div>

                    <!-- Password -->
                    <div v-if="!isEdit" class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Password</label
                        >
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400"
                            placeholder="Enter password"
                        />
                    </div>

                    <!-- Confirm Password -->
                    <div v-if="!isEdit" class="col-span-12 md:col-span-6">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Confirm Password</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400"
                            placeholder="Confirm password"
                        />
                    </div>

                    <!-- Roles -->
                    <div class="col-span-12">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-1"
                            >Assign Roles</label
                        >
                        <Multiselect
                            v-model="form.roles"
                            :options="roles"
                            placeholder="Select roles"
                            :multiple="true"
                            :close-on-select="false"
                            class="text-sm"
                            track-by="name"
                            label="name"
                        />
                    </div>

                    <!-- Submit -->
                    <div class="col-span-12 pt-4">
                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-5 py-2 text-sm font-medium rounded hover:bg-blue-700"
                            :disabled="form.processing"
                        >
                            {{ isEdit ? "Update User" : "Create User" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@import "vue-multiselect/dist/vue-multiselect.css";
</style>
