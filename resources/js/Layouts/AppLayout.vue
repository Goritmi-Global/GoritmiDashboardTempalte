<template>
  <div class="flex h-screen bg-gray-100">
    <Aside />
    <div class="flex-1 flex flex-col">
      <Header />
      <main class="flex-1 p-4 overflow-y-auto">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import Aside from '@/Layouts/Aside.vue'
import Header from '@/Layouts/Header.vue'
</script>