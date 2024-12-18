<script setup lang="ts">
import {Toaster} from 'vue-sonner';
import {AuthService} from '@/lib/services/auth.service.ts';
import {TooltipProvider} from '@/components/ui/tooltip';

AuthService.refreshToken();
</script>

<template>
  <TooltipProvider>
    <div id="app">
      <router-view/>
    </div>
    <Toaster position="bottom-right"/>
  </TooltipProvider>
</template>