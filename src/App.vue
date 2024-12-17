<script setup lang="ts">
import {Toaster} from 'vue-sonner';
import {AuthService} from '@/lib/services/auth.service.ts';

AuthService.refreshToken();
</script>

<template>
  <div id="app">
    <router-view/>
  </div>
  <Toaster position="bottom-right"/>
</template>