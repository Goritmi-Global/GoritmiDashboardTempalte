<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import Aside from "@/Layouts/Aside.vue";
import Header from "@/Layouts/Header.vue";

const sidebarOpen = ref(true);
const showScrollTop = ref(false);

function scrollToTop() {
    document.querySelector("main")?.scrollTo({ top: 0, behavior: "smooth" });
}

function handleScroll() {
    const main = document.querySelector("main");
    if (main) {
        showScrollTop.value = main.scrollTop > 300;
    }
}

onMounted(() => {
    const main = document.querySelector("main");
    main?.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    const main = document.querySelector("main");
    main?.removeEventListener("scroll", handleScroll);
});
</script>

<template>
    <div class="flex flex-col min-h-screen bg-gray-100">
        <!-- Header with logo toggle -->
        <Header :sidebar-visible="sidebarOpen" @toggle-sidebar="sidebarOpen = !sidebarOpen" />

        <div class="flex flex-1">
            <!-- Sidebar -->
            <Aside :sidebarOpen="sidebarOpen" />

            <!-- Main content area -->
            <main class="flex-1 p-4 overflow-y-auto">
                <slot />
            </main>
        </div>

        <!-- Scroll to Top Button -->
        <Transition name="fade">
            <button
                v-show="showScrollTop"
                @click="scrollToTop"
                class="fixed bottom-6 right-6 z-50 bg-[#296FB6] hover:bg-primary text-white p-3 rounded-full shadow-lg transition"
                aria-label="Scroll to top"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </Transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
