<script setup lang="ts">
import dayjs from 'dayjs';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useRoute, useRouter } from 'vue-router';
import { computed, onMounted } from 'vue';
import { eventStore } from '@/stores/event.store';
import { Loader } from "@/components/ui/loader";
import { MapPin, Users, DollarSign, Calendar } from 'lucide-vue-next';

const route = useRoute();
const router = useRouter();

const event = computed(() => eventStore.events.find(e => e.id.toString() === route.params.id.toString()));
const loading = computed(() => eventStore.loading);

onMounted(async () => {
  if (!event.value) {
    const fetchedEvent = await eventStore.getEventById(route.params.id.toString());
    if (!fetchedEvent) {
      router.push('/404');
    }
  }
});
</script>


<template>
  <div v-if="loading" class="flex justify-center items-center h-screen">
    <Loader />
  </div>

  <div v-else-if="event" class="flex flex-col items-center motion-translate-x-in-[0%] motion-translate-y-in-[10%] motion-opacity-in-[0%] motion-blur-in-[8px] motion-duration-[500ms]/opacity">
    <div class="w-full max-w-4xl bg-gradient-to-r from-lime-300 via-lime-200 to-lime-100 rounded-lg shadow-lg p-6 text-white mb-6">
      <div class="flex justify-between items-start">
        <div>
          <h1 class="text-2xl font-bold capitalize text-black">{{ event.name }}</h1>
          <p class="flex items-center gap-2 text-sm text-black">
            <Calendar class="w-4 h-4" />
            {{ dayjs(event.start_at).format('DD/MM/YYYY') }} au
            {{ dayjs(event.end_at).format('DD/MM/YYYY') }}
          </p>
        </div>
        <div class="flex flex-col">
        <div class="flex flex-wrap justify-end gap-1">
          <Badge
              v-for="sport in event.sport"
              :key="sport"
              variant="secondary"
              class="bg-lime-600 text-white hover:bg-lime-700"
          >
            {{ sport }}
          </Badge>
        </div>
        <div class="w-full max-w-4xl flex justify-end mt-6">
          <Button variant="outline" class="text-black">
            Participate
          </Button>
        </div>
        </div>
      </div>
    </div>

    <div class="w-full max-w-4xl bg-white rounded-lg shadow-lg p-6">
      <div class="flex flex-col gap-4">
        <div class="flex items-center gap-4 bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
          <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full text-gray-600">
            <Users class="w-6 h-6" />
          </div>
          <div>
            <p class="text-lg font-semibold text-gray-800">Organized By</p>
            <p class="text-sm text-gray-600">{{ event.organized_by.login }}</p>
            <p class="text-sm text-gray-500">{{ event.organized_by.email }}</p>
          </div>
        </div>

        <p class="text-gray-700 text-lg capitalize">
          {{ event.description }}
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
          <div class="flex flex-col items-center text-center bg-orange-50 border border-orange-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-full text-orange-600">
              <MapPin class="w-6 h-6" />
            </div>
            <p class="mt-2 text-lg font-semibold text-orange-700">Location</p>
            <p class="mt-1 text-sm text-orange-600">{{ event.localisation }}</p>
          </div>

          <div class="flex flex-col items-center text-center bg-violet-50 border border-violet-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-center w-12 h-12 bg-violet-100 rounded-full text-violet-600">
              <Users class="w-6 h-6" />
            </div>
            <p class="mt-2 text-lg font-semibold text-violet-700">Participants</p>
            <p class="mt-1 text-sm text-violet-600">{{ event.max_participants }} participants</p>
          </div>

          <div class="flex flex-col items-center text-center bg-rose-50 border border-rose-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-center w-12 h-12 bg-rose-100 rounded-full text-rose-600">
              <DollarSign class="w-6 h-6" />
            </div>
            <p class="mt-2 text-lg font-semibold text-rose-700">Price</p>
            <p class="mt-1 text-sm text-rose-600">{{ event.price }}€</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <p v-else class="text-center text-gray-700 text-lg">Event not found</p>
</template>
