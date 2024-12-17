<script setup lang="ts">
import Navbar from '@/components/navbar.vue';
</script>

<template>
  <div>
    <Navbar/>
    <main class="p-6 min-h-screen">
      <router-view/>
    </main>
    <footer class="border-t w-full flex items-center justify-center py-10">
      <p class="text-muted-foreground">&copy; 2024 Hapi Trail</p>
    </footer>
  </div>
</template>

