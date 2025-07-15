<template>
  <div class="flex h-screen bg-gray-100">
    <Aside :sidebar-open="sidebarOpen"/>
    <div class="flex-1 flex flex-col">
    <Header @toggle-sidebar="sidebarOpen = !sidebarOpen" />
      <main class="flex-1 p-4 overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Aside from '@/Layouts/Aside.vue';
import Header from '@/Layouts/Header.vue';

const sidebarOpen = ref(true);
</script>
