<script setup lang="ts">
import {computed, onMounted, ref, watch} from 'vue';
import {Loader} from '@/components/ui/loader';
import {EventCard} from '@/components/events/event-card';
import {SportType} from '@/lib/services/event.service.ts';
import {eventStore} from '@/stores/event.store.ts';
import {userStore} from '@/stores/user.store.ts';

const loading = computed(() => eventStore.loading);
const events = ref<SportType[]>([]);

watch(() => eventStore.events, (newEvents) => {
  if (!userStore.user) {
    return;
  }
  events.value = newEvents.filter((event) => event.participations.some((participation) => participation.user.id === userStore.getUser()?.id));
});

onMounted(() => {
  eventStore.loadEvents();
});
</script>

<template>
  <div v-if="loading" class="flex justify-center items-center h-80">
    <Loader v-if="loading"/>
  </div>
  <div v-else class="grid grid-cols-3 gap-4 max-lg:grid-cols-2 max-md:grid-cols-1">
    <EventCard v-for="event in events" :key="event.id" :event="event"/>
  </div>
</template>
