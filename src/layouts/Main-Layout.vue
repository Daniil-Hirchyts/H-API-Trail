<script setup lang="ts">
import {NavigationBar} from "@/components/navigation/navigation-bar";
</script>

<template>
  <div class="h-full flex flex-col items-center justify-between relative w-full">
    <NavigationBar/>
    <main class="p-6 pt-20 w-full">
      <router-view/>
    </main>
    <footer class="border-t w-full flex items-center justify-center py-4 ">
      <p class="text-muted-foreground">&copy; 2024 Hapi Trail</p>
    </footer>
  </div>
</template>

