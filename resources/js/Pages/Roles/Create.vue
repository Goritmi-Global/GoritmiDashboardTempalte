<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { computed } from "vue";

const props = defineProps({
    role: Object,
    permissions: Array,
    rolePermissions: Array,
});

const isEdit = !!props.role;

const permissionOptions = computed(() =>
    props.permissions.map((perm) => perm.name)
);

const form = useForm({
    name: props.role?.name || "",
    permissions: props.rolePermissions || [],
});

const submit = () => {
    if (isEdit) {
        form.put(`/roles/${props.role.id}`);
    } else {
        form.post("/roles");
    }
};
</script>

<template>
    <Head :title="isEdit ? 'Edit Role' : 'Create Role'" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <!-- Page Header -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3"
            >
                <!-- Header and Breadcrumb -->
                <div>
                    <h1 class="text-2xl font-bold mb-2">
                        {{ isEdit ? "Edit Role" : "Create Role" }}
                    </h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol
                            class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse"
                        >
                            <li class="inline-flex items-center">
                                <Link
                                    href="/"
                                    class="text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white inline-flex items-center"
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
                                <div
                                    class="flex items-center text-gray-500 dark:text-gray-400"
                                >
                                    Roles
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                                 

                <PrimaryLinkButton href="/roles" label="View All">
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

            <!-- Form Card -->
            <div
                class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mx-auto"
            >
                <form @submit.prevent="submit" class="grid grid-cols-12 gap-4">
                    <!-- Role Name -->
                    <div class="col-span-12">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-white mb-1"
                            >Role Name</label
                        >
                        <input
                            type="text"
                            v-model="form.name"
                            placeholder="Enter role name"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-700 dark:text-white"
                            required
                        />
                    </div>

                    <!-- Permission Tags -->
                    <div class="col-span-12">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-white mb-1"
                            >Permissions</label
                        >
                        <Multiselect
                            v-model="form.permissions"
                            :options="permissionOptions"
                            placeholder="Select permissions"
                            :multiple="true"
                            :taggable="true"
                            tag-placeholder="Add permission"
                            class="text-sm"
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="col-span-12 pt-4">
                        <button
                            type="submit"
                            class="bg-primary-600 hover:bg-primary-700 text-white text-sm px-5 py-2 rounded-lg"
                            :disabled="form.processing"
                        >
                            {{ isEdit ? "Update Role" : "Create Role" }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.multiselect {
    min-height: 40px;
}
</style>
