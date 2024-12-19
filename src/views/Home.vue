<script setup lang="ts">
import EventCard from '@/components/event-card.vue';
import { onMounted } from 'vue';
import { eventStore } from '@/stores/event.store';

onMounted(() => {
  eventStore.loadEvents();
});
</script>

<template>
  <div class="grid grid-cols-3 gap-4 max-lg:grid-cols-2 max-md:grid-cols-1">
    <EventCard v-for="event in eventStore.events" :key="event.id" :event="event"/>
  </div>
</template>
