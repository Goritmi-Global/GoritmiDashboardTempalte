<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    permission: Object,
});

const isEdit = !!props.permission;

const form = useForm({
    name: props.permission?.name || '',
});
</script>

<template>
    <Head :title="isEdit ? 'Edit Permission' : 'Create Permission'" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                <!-- Header and Breadcrumb -->
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">
                            {{ isEdit ? 'Edit Permission' : 'Create Permission' }}
                        </h1>

                        <nav class="flex text-sm" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
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
                                    <div class="flex items-center">
                                        <svg
                                            class="w-3 h-3 text-gray-400 mx-1 rtl:rotate-180"
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
                                        <span class="text-gray-500 dark:text-gray-400">Permissions</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <Link
                    href="/permissions"
                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
                >
                    <svg
                        class="h-4 w-4 mr-2"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M4 6h12a1 1 0 100-2H4a1 1 0 100 2zm0 5h12a1 1 0 100-2H4a1 1 0 100 2zm0 5h12a1 1 0 100-2H4a1 1 0 100 2z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    View List
                </Link>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6  mx-auto">
                <form
                    @submit.prevent="isEdit ? form.put(`/permissions/${props.permission.id}`) : form.post('/permissions')"
                    class="grid grid-cols-12 gap-4"
                >
                    <!-- Name Field -->
                    <div class="col-span-12">
                        <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                            Permission Name
                        </label>
                        <input
                            type="text"
                            v-model="form.name"
                            placeholder="Enter permission name"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm dark:bg-gray-700 dark:text-white"
                            required
                        />
                    </div>

                    <!-- Submit Button -->
                    <div class="col-span-12 pt-4">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-primary-600 hover:bg-primary-700 text-white text-sm px-5 py-2 rounded-lg"
                        >
                            {{ isEdit ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
