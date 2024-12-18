<script setup lang="ts">
import {EventService, SportType} from '@/lib/services/event.service.ts';
import EventCard from '@/components/event-card.vue';
import {ref} from 'vue';

const events = ref<SportType[]>([]);

EventService.getAll().then(data => events.value = data ?? []);
</script>

<template>
  <div class="grid grid-cols-3 gap-4 max-lg:grid-cols-2 max-md:grid-cols-1">
    <EventCard v-for="event in events" :key="event.id" :event="event"/>
  </div>
</template>