<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Register" />

  <div class="min-h-screen flex items-center justify-center bg-[#f1f5f9] relative overflow-hidden">
    <!-- 🎨 Background bubbles -->
    <div class="absolute top-0 left-0 w-full h-full z-0 overflow-hidden">
      <!-- Big bubbles -->
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

    <!-- 📋 Register Form Card -->
    <div class="relative z-10 bg-white px-8 py-6 rounded-lg shadow-lg w-full max-w-sm">
      <div class="flex justify-center mb-4">
        <img src="https://goritmi.co.uk/images/logo.png" alt="Goritmi Logo" class="h-10" />
      </div>

      <h2 class="text-center text-xl font-semibold text-gray-800 mb-4">Create Account</h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <input
            id="name"
            v-model="form.name"
            type="text"
            placeholder="Full Name"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="name"
          />
          <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
        </div>

        <div>
          <input
            id="email"
            v-model="form.email"
            type="email"
            placeholder="Email Address"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="username"
          />
          <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
        </div>

        <div>
          <input
            id="password"
            v-model="form.password"
            type="password"
            placeholder="Password"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="new-password"
          />
          <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
        </div>

        <div>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm Password"
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
            required
            autocomplete="new-password"
          />
          <p v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">
            {{ form.errors.password_confirmation }}
          </p>
        </div>

        <button
          type="submit"
          class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded transition"
          :disabled="form.processing"
        >
          Register
        </button>
      </form>

      <p class="mt-4 text-center text-sm text-gray-600">
        Already registered?
        <Link :href="route('login')" class="text-blue-500 hover:underline">Log in</Link>
      </p>
    </div>
  </div>
</template>
