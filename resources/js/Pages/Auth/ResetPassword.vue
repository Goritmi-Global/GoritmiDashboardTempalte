<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
  email: { type: String, required: true },
  token: { type: String, required: true },
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('password.store'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Reset Password" />

  <div class="min-h-screen flex items-center justify-center bg-[#f1f5f9] relative overflow-hidden">
    <!-- 🎨 Background bubbles -->
    <div class="absolute top-0 left-0 w-full h-full z-0 overflow-hidden">
      <div class="absolute top-[-6rem] right-[-6rem] w-[22rem] h-[22rem] bg-gradient-to-br from-cyan-400 to-teal-400 rounded-full opacity-30"></div>
      <div class="absolute bottom-[-6rem] left-[-6rem] w-[22rem] h-[22rem] bg-purple-500 rounded-full opacity-30"></div>

      <!-- Floating dots -->
      <div class="absolute top-[15%] left-[10%] w-4 h-4 bg-cyan-300 rounded-full animate-bounce"></div>
      <div class="absolute top-[20%] right-[15%] w-3 h-3 bg-blue-400 rounded-full animate-bounce delay-200"></div>
      <div class="absolute top-[35%] left-[25%] w-2.5 h-2.5 bg-purple-300 rounded-full animate-bounce delay-500"></div>
      <div class="absolute top-[40%] right-[25%] w-3 h-3 bg-fuchsia-400 rounded-full animate-bounce delay-300"></div>
      <div class="absolute top-[55%] left-[35%] w-3 h-3 bg-teal-300 rounded-full animate-bounce delay-1000"></div>
      <div class="absolute top-[60%] right-[32%] w-4 h-4 bg-rose-300 rounded-full animate-bounce delay-700"></div>
      <div class="absolute top-[75%] left-[20%] w-2.5 h-2.5 bg-indigo-300 rounded-full animate-bounce delay-500"></div>
      <div class="absolute top-[80%] right-[20%] w-3 h-3 bg-sky-400 rounded-full animate-bounce delay-300"></div>
    </div>

    <!-- 🔐 Reset Form Card -->
    <div class="relative z-10 bg-white px-8 py-6 rounded-lg shadow-lg w-full max-w-sm">
      <div class="flex justify-center mb-4">
        <img src="https://goritmi.co.uk/images/logo1.png" alt="Goritmi Logo" class="h-10" />
      </div>

      <h2 class="text-center text-xl font-semibold text-gray-800 mb-4">
        Reset Your Password
      </h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <input
            id="email"
            type="email"
            v-model="form.email"
            placeholder="Email Address"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autofocus
            autocomplete="username"
          />
          <p class="text-red-500 text-sm mt-1" v-if="form.errors.email">{{ form.errors.email }}</p>
        </div>

        <div>
          <input
            id="password"
            type="password"
            v-model="form.password"
            placeholder="New Password"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="new-password"
          />
          <p class="text-red-500 text-sm mt-1" v-if="form.errors.password">{{ form.errors.password }}</p>
        </div>

        <div>
          <input
            id="password_confirmation"
            type="password"
            v-model="form.password_confirmation"
            placeholder="Confirm Password"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="new-password"
          />
          <p class="text-red-500 text-sm mt-1" v-if="form.errors.password_confirmation">
            {{ form.errors.password_confirmation }}
          </p>
        </div>

        <button
          type="submit"
          class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded transition"
          :disabled="form.processing"
        >
          Reset Password
        </button>
      </form>
    </div>
  </div>
</template>
