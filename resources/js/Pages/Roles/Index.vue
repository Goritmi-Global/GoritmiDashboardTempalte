<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { Plus, KeyRound } from 'lucide-vue-next';
import { ref } from 'vue'

const props = defineProps({
  roles: Array,
  permissions: Array
})

const activeTab = ref('roles')

const deletePermission = (id) => {
  if (confirm('Are you sure you want to delete this permission?')) {
    router.delete(`/permissions/${id}`)
  }
}
</script>

<template>
  <Head title="Roles & Permissions" />
  <AppLayout>
    <div class="p-6 space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Roles & Permissions</h1>
        <div class="flex gap-2">

          <PrimaryLinkButton v-if="activeTab === 'roles'" href="/roles/create" label="Add Role">
  <template #icon>
    <Plus class="w-4 h-4" />
  </template>
</PrimaryLinkButton>

<PrimaryLinkButton v-if="activeTab === 'permissions'" href="/permissions/create" label="Add Permission">
  <template #icon>
    <KeyRound class="w-4 h-4" />
  </template>
</PrimaryLinkButton>

        </div>
      </div>

      <!-- Tabs -->
      <div class="flex border-b space-x-4">
        <button
          @click="activeTab = 'roles'"
          :class="[
            'px-4 py-2 text-sm font-medium border-b-2',
            activeTab === 'roles' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'
          ]"
        >
          Roles
        </button>
        <button
          @click="activeTab = 'permissions'"
          :class="[
            'px-4 py-2 text-sm font-medium border-b-2',
            activeTab === 'permissions' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'
          ]"
        >
          Permissions
        </button>
      </div>

      <!-- Roles Table -->
      <div v-show="activeTab === 'roles'" class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left text-gray-600">
          <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
            <tr>
              <th class="px-6 py-3">Role</th>
              <th class="px-6 py-3">Permissions</th>
              <th class="px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="role in props.roles" :key="role.id" class="border-b hover:bg-gray-50">
              <td class="px-6 py-4 font-semibold">{{ role.name }}</td>
              <td class="px-6 py-4">
                <span
                  v-for="perm in role.permissions"
                  :key="perm.id"
                  class="inline-block bg-blue-100 text-blue-800 text-xs font-medium mr-1 px-2.5 py-0.5 rounded"
                >
                  {{ perm.name }}
                </span>
              </td>
              <td class="px-6 py-4 text-right space-x-2">
                <Link
                  :href="`/roles/${role.id}/edit`"
                  class="text-blue-600 hover:underline"
                >Edit</Link>
                <button
                  v-if="role.name !== 'admin'"
                  @click="$emit('delete', role.id)"
                  class="text-red-600 hover:underline"
                >Delete</button>
              </td>
            </tr>
            <tr v-if="roles.length === 0">
              <td colspan="3" class="px-6 py-4 text-center text-gray-500">No roles found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Permissions Table -->
      <div v-show="activeTab === 'permissions'" class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left text-gray-600">
          <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
            <tr>
              <th class="px-6 py-3">#</th>
              <th class="px-6 py-3">Permission Name</th>
              <th class="px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(permission, index) in props.permissions" :key="permission.id" class="border-b hover:bg-gray-50">
              <td class="px-6 py-4">{{ index + 1 }}</td>
              <td class="px-6 py-4">{{ permission.name }}</td>
              <td class="px-6 py-4 text-right space-x-2">
                <Link
                  :href="`/permissions/${permission.id}/edit`"
                  class="text-blue-600 hover:underline"
                >Edit</Link>
                <button
                  @click="deletePermission(permission.id)"
                  class="text-red-600 hover:underline"
                >Delete</button>
              </td>
            </tr>
            <tr v-if="permissions.length === 0">
              <td colspan="3" class="text-center py-6 text-gray-500">No permissions found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>
