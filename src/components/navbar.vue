<script setup lang="ts">
import {Medal} from 'lucide-vue-next';
import {Button} from '@/components/ui/button';
import {userStore} from '@/stores/user.store.ts';
import NewEventDialog from '@/components/new-event-dialog.vue';
import {LogoutButton} from "@/components/navigation/logout-button";
import {computed} from "vue";
import {Loader} from "@/components/ui/loader";

const loading = computed(() => userStore.loading);
const initialized = computed(() => userStore.initialized);
</script>

<template>
  <header
      class="sticky top-0 flex h-16 items-center justify-between gap-4 border-b bg-background/30 backdrop-blur-lg px-4 md:px-6"
  >
    <nav class="font-medium flex items-center gap-5 text-sm lg:gap-6">
      <router-link
          to="/"
          class="flex items-center gap-2 text-lg font-semibold md:text-base"
      >
        <Medal class="h-6 w-6" />
        <span class="sr-only">Hapi Trail</span>
      </router-link>
    </nav>
    <div class="flex gap-4 items-center">
      <template v-if="!initialized">
       <Loader />
      </template>
      <template v-else-if="!userStore.isLoggedIn()">
        <router-link to="/login">
          <Button>Login</Button>
        </router-link>
        <router-link to="/register">
          <Button>Register</Button>
        </router-link>
      </template>
      <template v-else>
        <NewEventDialog />
        <span>{{ userStore.user?.login }}</span>
        <LogoutButton />
      </template>
    </div>
  </header>
</template>
