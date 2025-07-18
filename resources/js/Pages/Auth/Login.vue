<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Login" />

    <div
        class="min-h-screen flex items-center justify-center bg-[#f1f5f9] relative overflow-hidden"
    >
        <!-- 🔵 Animated Circles Background -->
        <!-- Clean, symmetrical background bubbles -->
        <div class="absolute top-0 left-0 w-full h-full z-0 overflow-hidden">
  <!-- Top right bubble -->
  <div
    class="absolute top-[-6rem] right-[-6rem] w-[22rem] h-[22rem] bg-gradient-to-br from-cyan-400 to-teal-400 rounded-full opacity-30"
  ></div>

  <!-- Decorative Floating Dots -->
  <!-- Row 1 -->
  <div class="absolute top-[15%] left-[10%] w-4 h-4 bg-cyan-300 rounded-full animate-bounce"></div>
  <div class="absolute top-[20%] right-[15%] w-3 h-3 bg-blue-400 rounded-full animate-bounce delay-200"></div>

  <!-- Row 2 -->
  <div class="absolute top-[35%] left-[25%] w-2.5 h-2.5 bg-purple-300 rounded-full animate-bounce delay-500"></div>
  <div class="absolute top-[40%] right-[25%] w-3 h-3 bg-fuchsia-400 rounded-full animate-bounce delay-300"></div>

  <!-- Row 3 -->
  <div class="absolute top-[55%] left-[35%] w-3 h-3 bg-teal-300 rounded-full animate-bounce delay-1000"></div>
  <div class="absolute top-[60%] right-[32%] w-4 h-4 bg-rose-300 rounded-full animate-bounce delay-700"></div>

  <!-- Row 4 -->
  <div class="absolute top-[75%] left-[20%] w-2.5 h-2.5 bg-indigo-300 rounded-full animate-bounce delay-500"></div>
  <div class="absolute top-[80%] right-[20%] w-3 h-3 bg-sky-400 rounded-full animate-bounce delay-300"></div>

  <!-- Bottom left bubble -->
  <div
    class="absolute bottom-[-6rem] left-[-6rem] w-[22rem] h-[22rem] bg-purple-500 rounded-full opacity-30"
  ></div>
</div>


        <!-- Login Card -->
        <div
            class="relative bg-white px-8 py-6 rounded-lg shadow-lg w-full max-w-sm z-10"
        >
            <!-- Logo + Title -->
            <div class="flex justify-center items-center mb-4">
                <img
                    src="https://goritmi.co.uk/images/logo.png"
                    alt="Goritmi Logo"
                    class="h-10"
                />
            </div>

            <h2 class="text-center text-xl font-semibold text-gray-800 mb-6">
                Login
            </h2>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="Email Address"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required
                        autofocus
                    />
                    <p
                        class="text-red-500 text-sm mt-1"
                        v-if="form.errors.email"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="Password"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required
                    />
                    <p
                        class="text-red-500 text-sm mt-1"
                        v-if="form.errors.password"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="flex items-center justify-between">
                    <label
                        class="flex items-center space-x-2 text-sm text-gray-600"
                    >
                        <input
                            type="checkbox"
                            v-model="form.remember"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                        />
                        <span>Remember me?</span>
                    </label>

                    <Link
                        :href="route('password.request')"
                        class="text-blue-500 text-sm hover:underline"
                        >Forgot Password?</Link
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 rounded transition"
                    :disabled="form.processing"
                >
                    Login
                </button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Don’t have an account?
                <Link href="/register" class="text-blue-500 hover:underline"
                    >Create Account</Link
                >
            </p>
        </div>
    </div>
</template>
