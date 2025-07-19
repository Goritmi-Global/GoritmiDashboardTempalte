<template>
  <div>
    <h2 class="text-xl font-bold mb-4">Edit Roles for {{ user.name }}</h2>

    <form @submit.prevent="submit">
      <div v-for="role in roles" :key="role.id">
        <label class="flex items-center space-x-2">
          <input
            type="checkbox"
            :value="role.name"
            v-model="form.roles"
          />
          <span>{{ role.name }}</span>
        </label>
      </div>

      <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Update Roles</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

defineProps({ user: Object, roles: Array, userRoles: Array })

const form = useForm({
  roles: [...userRoles],
})

const submit = () => {
  form.put(route('users.roles.update', user.id))
}
</script>
