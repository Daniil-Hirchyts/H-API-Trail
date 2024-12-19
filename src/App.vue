<script setup lang="ts">
import {Toaster} from 'vue-sonner';
import {TooltipProvider} from '@/components/ui/tooltip';
import {userStore} from "@/stores/user.store.ts";

userStore.initializeUser();

</script>

<template>
  <TooltipProvider>
    <div id="app">
      <router-view class="motion-opacity-in-[0%] motion-blur-in-[5px]"/>
    </div>
    <Toaster position="bottom-right"/>
  </TooltipProvider>
</template>