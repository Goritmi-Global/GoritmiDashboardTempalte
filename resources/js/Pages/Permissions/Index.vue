<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    permissions: Array,
})

const deletePermission = (id) => {
    if (confirm('Are you sure you want to delete this permission?')) {
        router.delete(`/permissions/${id}`)
    }
}
</script>

<template>
    <Head title="Permissions" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <!-- Page Header and Breadcrumb -->
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Permissions</h1>
                    <nav class="flex text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-2">
                            <li class="inline-flex items-center">
                                <Link href="/" class="text-gray-600 hover:text-blue-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 3.172L3.172 10H6v6h8v-6h2.828L10 3.172z" />
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li class="flex items-center text-gray-500">
                                <svg class="w-4 h-4 mx-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Permissions
                            </li>
                        </ol>
                    </nav>
                </div>

                <Link
                    href="/permissions/create"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
                >
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 010-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Permission
                </Link>
            </div>

            <!-- Permissions Table Card -->
            <div class="overflow-hidden bg-white shadow rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 w-12">#</th>
                                <th class="px-6 py-3">Permission Name</th>
                                <th class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(permission, index) in permissions" :key="permission.id" class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">{{ index + 1 }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ permission.name }}</td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <Link
                                        :href="`/permissions/${permission.id}/edit`"
                                        class="inline-flex items-center px-2 py-1 text-sm text-blue-600 hover:text-blue-800"
                                    >
                                        ✏️ Edit
                                    </Link>
                                    <button
                                        @click="deletePermission(permission.id)"
                                        class="inline-flex items-center px-2 py-1 text-sm text-red-600 hover:text-red-800"
                                    >
                                        🗑 Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="permissions.length === 0">
                                <td colspan="3" class="text-center px-6 py-4 text-gray-500">No permissions found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
