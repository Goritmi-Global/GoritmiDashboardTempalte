<template>
  <header class="bg-white shadow border-b h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-4">
        <!-- Logo - Hidden when sidebar is collapsed -->
      <Link :href="route('dashboard')" class="block">
        <img
          src="https://goritmi.co.uk/images/logo.png"
          alt="Goritmi Logo"
          class="h-10"
        />
      </Link>
      <button
        @click="emit('toggle-sidebar')"
        class="text-gray-500 hover:text-gray-700 focus:outline-none mx-36"
      >
        <!-- Hamburger Icon -->
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      
      

      <!-- <h1 class="text-lg font-semibold">
        {{ $page.component.split("/").pop() }}
      </h1> -->
    
        </div>
        <div class="flex items-center">
            <!-- Notification Dropdown -->
            <div class="relative">
                <button
                    @click="showNotifications = !showNotifications"
                    class="relative focus:outline-none"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5"
                        />
                    </svg>

                    <span
                        class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1.5"
                        >3</span
                    >
                </button>

                <div
                    v-show="showNotifications"
                    class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-xl shadow-lg z-50 border border-gray-200 dark:border-gray-700"
                >
                    <div
                        class="flex items-center justify-between px-4 py-3 border-b dark:border-gray-700"
                    >
                        <h3
                            class="text-base font-semibold text-gray-800 dark:text-white"
                        >
                            Notifications
                        </h3>
                        <button class="text-sm text-blue-500 hover:underline">
                            Mark all read
                        </button>
                    </div>

                    <div
                        class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700"
                    >
                        <!-- Today -->
                        <div
                            class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400"
                        >
                            Today
                        </div>

                        <div class="px-4 py-3 flex gap-3">
                            <img
                                src="https://randomuser.me/api/portraits/women/44.jpg"
                                class="w-10 h-10 rounded-full"
                            />
                            <div class="flex-1">
                                <div
                                    class="flex justify-between text-sm font-semibold text-gray-800 dark:text-white"
                                >
                                    <span>UI/UX Design</span>
                                    <span class="text-xs text-gray-500"
                                        >2 min ago</span
                                    >
                                </div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300"
                                >
                                    Lorem Ipsum has been the industry's standard
                                    dummy text...
                                </p>
                            </div>
                        </div>

                        <div class="px-4 py-3 flex gap-3">
                            <img
                                src="https://randomuser.me/api/portraits/men/32.jpg"
                                class="w-10 h-10 rounded-full"
                            />
                            <div class="flex-1">
                                <div
                                    class="flex justify-between text-sm font-semibold text-gray-800 dark:text-white"
                                >
                                    <span>Message</span>
                                    <span class="text-xs text-gray-500"
                                        >1 hour ago</span
                                    >
                                </div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300"
                                >
                                    Lorem Ipsum has been the industry's standard
                                    dummy text since 1500.
                                </p>
                            </div>
                        </div>

                        <!-- Yesterday -->
                        <div
                            class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400"
                        >
                            Yesterday
                        </div>

                        <div class="px-4 py-3 flex gap-3">
                            <img
                                src="https://randomuser.me/api/portraits/women/48.jpg"
                                class="w-10 h-10 rounded-full"
                            />
                            <div class="flex-1">
                                <div
                                    class="flex justify-between text-sm font-semibold text-gray-800 dark:text-white"
                                >
                                    <span>Forms</span>
                                    <span class="text-xs text-gray-500"
                                        >2 hours ago</span
                                    >
                                </div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300"
                                >
                                    Dummy text of the printing and typesetting
                                    industry.
                                </p>
                            </div>
                        </div>

                        <div class="px-4 py-3 flex gap-3">
                            <img
                                src="https://randomuser.me/api/portraits/men/41.jpg"
                                class="w-10 h-10 rounded-full"
                            />
                            <div class="flex-1">
                                <div
                                    class="flex justify-between text-sm font-semibold text-gray-800 dark:text-white"
                                >
                                    <span>Challenge Invitation</span>
                                    <span class="text-xs text-gray-500"
                                        >12 hours ago</span
                                    >
                                </div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300 mb-2"
                                >
                                    Jonny invites you to join the challenge.
                                </p>
                                <div class="flex gap-2">
                                    <button
                                        class="text-xs px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                    >
                                        Decline
                                    </button>
                                    <button
                                        class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                    >
                                        Accept
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="text-center py-2 text-sm text-red-600 hover:underline border-t dark:border-gray-700 cursor-pointer"
                    >
                        Clear all Notifications
                    </div>
                </div>
            </div>

            <div class="relative mx-4">
                <button
                    @click="showMessages = !showMessages"
                    class="relative focus:outline-none"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z"
                        />
                    </svg>

                    <span
                        class="absolute -top-1 -right-1 bg-green-600 text-white text-xs rounded-full px-1.5"
                        >2</span
                    >
                </button>

                <div
                    v-show="showMessages"
                    class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-xl shadow-lg z-50 border border-gray-200 dark:border-gray-700"
                >
                    <div
                        class="flex items-center justify-between px-4 py-3 border-b dark:border-gray-700"
                    >
                        <h3
                            class="text-base font-semibold text-gray-800 dark:text-white"
                        >
                            Messages
                        </h3>
                        <button class="text-sm text-blue-500 hover:underline">
                            Mark all read
                        </button>
                    </div>

                    <div
                        class="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700"
                    >
                        <!-- Today -->
                        <div
                            class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400"
                        >
                            Today
                        </div>

                        <div class="px-4 py-3 flex gap-3">
                            <img
                                src="https://randomuser.me/api/portraits/women/44.jpg"
                                class="w-10 h-10 rounded-full"
                            />
                            <div class="flex-1">
                                <div
                                    class="flex justify-between text-sm font-semibold text-gray-800 dark:text-white"
                                >
                                    <span>UI/UX Design</span>
                                    <span class="text-xs text-gray-500"
                                        >2 min ago</span
                                    >
                                </div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300"
                                >
                                    Lorem Ipsum has been the industry's standard
                                    dummy text...
                                </p>
                            </div>
                        </div>

                        <div class="px-4 py-3 flex gap-3">
                            <img
                                src="https://randomuser.me/api/portraits/men/32.jpg"
                                class="w-10 h-10 rounded-full"
                            />
                            <div class="flex-1">
                                <div
                                    class="flex justify-between text-sm font-semibold text-gray-800 dark:text-white"
                                >
                                    <span>Message</span>
                                    <span class="text-xs text-gray-500"
                                        >1 hour ago</span
                                    >
                                </div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300"
                                >
                                    Lorem Ipsum has been the industry's standard
                                    dummy text since 1500.
                                </p>
                            </div>
                        </div>

                        <!-- Yesterday -->
                        <div
                            class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400"
                        >
                            Yesterday
                        </div>

                        <div class="px-4 py-3 flex gap-3">
                            <img
                                src="https://randomuser.me/api/portraits/women/48.jpg"
                                class="w-10 h-10 rounded-full"
                            />
                            <div class="flex-1">
                                <div
                                    class="flex justify-between text-sm font-semibold text-gray-800 dark:text-white"
                                >
                                    <span>Forms</span>
                                    <span class="text-xs text-gray-500"
                                        >2 hours ago</span
                                    >
                                </div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300"
                                >
                                    Dummy text of the printing and typesetting
                                    industry.
                                </p>
                            </div>
                        </div>

                        <div class="px-4 py-3 flex gap-3">
                            <img
                                src="https://randomuser.me/api/portraits/men/41.jpg"
                                class="w-10 h-10 rounded-full"
                            />
                            <div class="flex-1">
                                <div
                                    class="flex justify-between text-sm font-semibold text-gray-800 dark:text-white"
                                >
                                    <span>Challenge Invitation</span>
                                    <span class="text-xs text-gray-500"
                                        >12 hours ago</span
                                    >
                                </div>
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-300 mb-2"
                                >
                                    Jonny invites you to join the challenge.
                                </p>
                                <div class="flex gap-2">
                                    <button
                                        class="text-xs px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                    >
                                        Decline
                                    </button>
                                    <button
                                        class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700"
                                    >
                                        Accept
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="text-center py-2 text-sm text-red-600 hover:underline border-t dark:border-gray-700 cursor-pointer"
                    >
                        Clear all Messages
                    </div>
                </div>
            </div>

            <Dropdown align="right" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            {{ $page.props.auth.user.name }}
                            <svg
                                class="-me-0.5 ms-2 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </span>
                </template>
                <template #content>
                    <DropdownLink :href="route('profile.edit')"
                        >Profile</DropdownLink
                    >
                    <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                        >Log Out</DropdownLink
                    >
                </template>
            </Dropdown>
        </div>
    </header>
</template>

<script setup>
import { ref, computed } from "vue";
const emit = defineEmits(["toggle-sidebar"]);
import { Link } from "@inertiajs/vue3";

import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

const showNotifications = ref(false);
const showMessages = ref(false);

// optional: pass sidebar state from parent if needed for v-show logo
const props = defineProps({
  sidebarVisible: {
    type: Boolean,
    default: true,
  },
});
const showLogo = computed(() => props.sidebarVisible);
</script>
