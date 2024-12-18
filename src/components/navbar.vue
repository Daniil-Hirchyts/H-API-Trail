<script setup lang="ts">
import {Medal} from 'lucide-vue-next';
import {Button} from '@/components/ui/button';
import {userStore} from '@/stores/user.store.ts';
import {AuthService} from '@/lib/services/auth.service.ts';
import NewEventDialog from '@/components/new-event-dialog.vue';
</script>

<template>
  <header
      class="sticky top-0 flex h-16 items-center justify-between gap-4 border-b bg-background/30 backdrop-blur-lg px-4 md:px-6">
    <nav
        class="font-medium flex items-center gap-5 text-sm lg:gap-6">
      <router-link
          to="/"
          class="flex items-center gap-2 text-lg font-semibold md:text-base"
      >
        <Medal class="h-6 w-6"/>
        <span class="sr-only">Hapi Trail</span>
      </router-link>
    </nav>
    <div class="flex gap-4 items-center">
      <template v-if="!userStore.user">
        <router-link to="/login">
          <Button variant="ghost">Login</Button>
        </router-link>
        <router-link to="/register">
          <Button>Register</Button>
        </router-link>
      </template>
      <template v-else>
        <span class="text-sm text-muted-foreground">
          {{ userStore.user?.login }}
        </span>
        <NewEventDialog/>
        <Button variant="ghost" v-on:click="AuthService.logout">Logout</Button>
      </template>
    </div>
  </header>
</template>