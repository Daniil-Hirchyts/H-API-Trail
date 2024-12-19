<script setup lang="ts">
import EventCard from '@/components/event-card.vue';
import {computed, onMounted} from 'vue';
import {eventStore} from '@/stores/event.store';
import {Loader} from "@/components/ui/loader";

const loading = computed(() => eventStore.loading);

onMounted(() => {
  eventStore.loadEvents();
});
</script>

<template>
  <div v-if="loading" class="flex justify-center items-center h-80">
    <Loader v-if="loading" />
  </div>
  <div v-else class="grid grid-cols-3 gap-4 max-lg:grid-cols-2 max-md:grid-cols-1">
    <EventCard v-for="event in eventStore.events" :key="event.id" :event="event" />
  </div>
</template>
