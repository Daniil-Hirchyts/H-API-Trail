<script setup lang="ts">
import {userStore} from '@/stores/user.store.ts';
import {Button} from '@/components/ui/button';

async function handleLogout() {
  await userStore.logout();
}
</script>

<template>
  <Button @click="handleLogout" :loading="userStore.loading">
    Logout
  </Button>
</template>
