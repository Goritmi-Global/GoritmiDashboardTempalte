<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { Plus, KeyRound, Pencil, Trash2 } from "lucide-vue-next";
import RoleFormModal from "./RoleFormModal.vue";
import PermissionFormModal from "./PermissionFormModal.vue";

// import PermissionFormModal from '@/Permissions/PermissionFormModal.vue';
// import ConfirmModal from '@/Components/ConfirmModal.vue';
import { toast } from "vue3-toastify";

const props = defineProps({
    roles: Array,
    permissions: Array,
    permissionsPlucked: Array,
});

const activeTab = ref("roles");

// Modals
const showRoleModal = ref(false);
const editingRole = ref(null);

const showPermissionModal = ref(false);
const editingPermission = ref(null);

const confirmDelete = ref(null);

const openRoleModal = (role = null) => {
    editingRole.value = role;
    showRoleModal.value = true;
};

const openPermissionModal = (permission = null) => {
    editingPermission.value = permission;
    showPermissionModal.value = true;
};

// State for deletion
const itemToDelete = ref(null);
const showConfirmModal = ref(false);
const deleteType = ref(null);

// Deletion Methods
const deleteRole = async () => {
    try {
        await axios.delete(`/roles/${itemToDelete.value.id}`);
        toast.success(`${itemToDelete.value.name} deleted successfully`);
        refresh(); // call your reload function after deletion
    } catch (e) {
        if (e.response && e.response.data && e.response.data.message) {
            toast.error(e.response.data.message); // Show backend message
        } else {
            toast.error("Failed to delete role.");
        }
    } finally {
        showConfirmModal.value = false;
        itemToDelete.value = null;
        deleteType.value = null;
    }
};

const deletePermission = async () => {
    try {
        await axios.get(`/permissions/${itemToDelete.value.id}`);
        toast.success(`${itemToDelete.value.name} deleted successfully`);
        refresh();
    } catch (e) {
        if (e.response && e.response.data && e.response.data.message) {
            toast.error(e.response.data.message); // Show backend message
        } else {
            toast.error("Failed to delete permission.");
        }
    } finally {
        showConfirmModal.value = false;
        itemToDelete.value = null;
        deleteType.value = null;
    }
};

const refresh = () => {
    router.reload({ only: [activeTab.value] });
};
</script>

<template>
    <Head title="Roles & Permissions" />
    <AppLayout>
        <div class="p-6 space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Roles & Permissions</h1>
                <div class="flex gap-2">
                    <PrimaryModalButton v-if="activeTab === 'roles'"
                        @click="() => openRoleModal()"
                        label="Add Role"
                    >
                        <template #icon><Plus class="w-4 h-4" /></template>
                    </PrimaryModalButton>

                    <PrimaryModalButton
                        v-if="activeTab === 'permissions'"
                        @click="() => openPermissionModal()"
                        label="Add Permission"
                    >
                        <template #icon><KeyRound class="w-4 h-4" /></template>
                    </PrimaryModalButton>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex border-b space-x-4">
                <button
                    @click="activeTab = 'roles'"
                    :class="[
                        'px-4 py-2 text-sm font-medium border-b-2',
                        activeTab === 'roles'
                            ? 'border-blue-600 text-blue-600'
                            : 'border-transparent text-gray-500',
                    ]"
                >
                    Roles
                </button>
                <button
                    @click="activeTab = 'permissions'"
                    :class="[
                        'px-4 py-2 text-sm font-medium border-b-2',
                        activeTab === 'permissions'
                            ? 'border-blue-600 text-blue-600'
                            : 'border-transparent text-gray-500',
                    ]"
                >
                    Permissions
                </button>
            </div>

            <!-- Roles Table -->
            <div
                v-show="activeTab === 'roles'"
                class="bg-white shadow rounded-lg overflow-hidden"
            >
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                        <tr>
                           <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Role</th>
                            <th class="px-6 py-3">Permissions</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(role, index) in props.roles"
                            :key="role.id"
                            class="border-b hover:bg-gray-50"
                        >
                        <td class="px-6 py-4">{{ index + 1 }}</td>
                            <td class="px-6 py-4 font-semibold">
                                {{ role.name }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    v-for="perm in role.permissions"
                                    :key="perm.id"
                                     class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full mr-1"
                                >
                                    {{ perm.name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                              <div
                                    class="flex items-center justify-center gap-2"
                                >
                                <button
                                    @click="() => openRoleModal(role)"
                                    title="Edit"
                                    class="p-2 rounded-full text-blue-600 hover:bg-blue-100"
                                >
                                    <Pencil class="w-4 h-4" />
                                </button>
                                <ConfirmModal
                                    v-if="role.name !== 'admin'"
                                    :title="'Confirm Delete'"
                                    :message="`Are you sure you want to delete ${role.name}?`"
                                    :showDeleteButton="true"
                                    @confirm="
                                        () => {
                                            itemToDelete = role;
                                            deleteType = 'role';
                                            deleteRole();
                                        }
                                    "
                                    @cancel="() => {}"
                                /><span
                                    v-else
                                    class="text-xs text-gray-400 italic"
                                >
                                    (admin protected)
                                </span>
                                </div>
                                <!-- <button
                                    v-if="role.name !== 'admin'"
                                    @click="
                                        confirmDelete = {
                                            name: role.name,
                                            deleteUrl: `/roles/${role.id}`,
                                        }
                                    "
                                    class="p-2 rounded-full text-red-600 hover:bg-red-100"
                                    title="Delete"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button> -->
                            </td>
                        </tr>
                        <tr v-if="props.roles.length === 0">
                            <td
                                colspan="3"
                                class="px-6 py-4 text-center text-gray-500"
                            >
                                No roles found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Permissions Table -->
            <div
                v-show="activeTab === 'permissions'"
                class="bg-white shadow rounded-lg overflow-hidden"
            >
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-3">#</th>
                            <th class="px-6 py-3">Permission Name</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(permission, index) in props.permissions"
                            :key="permission.id"
                            class="border-b hover:bg-gray-50"
                        >
                            <td class="px-6 py-4">{{ index + 1 }}</td>
                            <td class="px-6 py-4">{{ permission.name }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                              <div
                                    class="flex items-center justify-center gap-2"
                                >
                                <button
                                    @click="
                                        () => openPermissionModal(permission)
                                    "
                                    title="Edit"
                                    class="p-2 rounded-full text-blue-600 hover:bg-blue-100"
                                >
                                    <Pencil class="w-4 h-4" />
                                </button>
                                <ConfirmModal
                                    v-if="permission.name !== 'admin'"
                                    :title="'Confirm Delete'"
                                    :message="`Are you sure you want to delete ${permission.name}?`"
                                    :showDeleteButton="true"
                                    @confirm="
                                        () => {
                                            itemToDelete = permission;
                                            deleteType = 'permission';
                                            deletePermission();
                                        }
                                    "
                                    @cancel="() => {}"
                                />
                                </div>
                                <!-- <button
                                    @click="
                                        confirmDelete = {
                                            name: permission.name,
                                            deleteUrl: `/permissions/${permission.id}`,
                                        }
                                    "
                                    class="p-2 rounded-full text-red-600 hover:bg-red-100"
                                    title="Delete"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button> -->
                            </td>
                        </tr>
                        <tr v-if="props.permissions.length === 0">
                            <td
                                colspan="3"
                                class="text-center py-6 text-gray-500"
                            >
                                No permissions found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modals -->
            <RoleFormModal
                v-if="showRoleModal"
                :permissions="permissionsPlucked"
                :role="editingRole"
                @close="
                    () => {
                        showRoleModal = false;
                        editingRole = null;
                    }
                "
                @submitted="refresh"
            />
            <PermissionFormModal
                v-if="showPermissionModal"
                :permission="editingPermission"
                @close="
                    () => {
                        showPermissionModal = false;
                        editingPermission = null;
                    }
                "
                @submitted="refresh"
            />
            <ConfirmModal
                v-if="confirmDelete"
                :title="'Confirm Delete'"
                :message="`Are you sure you want to delete ${confirmDelete.name}?`"
                @confirm="deleteItem"
                @cancel="() => (confirmDelete = null)"
            />
        </div>
    </AppLayout>
</template>
