<script setup lang="ts">
import dayjs from 'dayjs';
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card';
import {Badge} from '@/components/ui/badge';
import {Button} from '@/components/ui/button';
import {useRoute, useRouter} from 'vue-router';
import {computed, onMounted} from 'vue';
import {eventStore} from '@/stores/event.store';
import {Loader} from '@/components/ui/loader';
import {EventService} from '@/lib/services/event.service.ts';

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
  <div v-if="loading" class="flex justify-center items-center h-80">
    <Loader/>
  </div>
  <Card v-else-if="event" class="group-hover:shadow-lg transition-all duration-150 ">
    <CardHeader class="relative">
      <CardTitle>{{ event.name }}</CardTitle>
      <CardDescription>{{ dayjs(event.start_at).format('DD/MM/YYYY HH:mm') }} au
        {{ dayjs(event.end_at).format('DD/MM/YYYY HH:mm') }}
      </CardDescription>
      <div class="gap-1 flex flex-row items-start justify-between">
        <div class="grid gap-1">
          <Badge variant="secondary">{{ event.localisation }}</Badge>
          <Badge variant="secondary">{{ event.max_participants }} participants</Badge>
          <Badge variant="secondary">{{ event.price }}€</Badge>
        </div>
        <div class="grid gap-2 grid-cols-2">
          <Badge v-for="sport in event.sport" :key="sport" variant="secondary">{{ sport }}</Badge>
        </div>
      </div>
      <Button @click="EventService.participate(event.id)" class="absolute top-6 right-6">
        Participate
      </Button>
    </CardHeader>
    <CardContent>
      <p>{{ event.description }}</p>
      <h2 class="text-lg font-bold py-2">Participants</h2>
      <div class="flex flex-col">
        <div v-for="participation in event.participations" :key="participation.user.id" class="flex gap-8">
          <p>Login: {{ participation.user.login }}</p>
          <p>Email: {{ participation.user.email }}</p>
        </div>
      </div>
    </CardContent>
  </Card>
  <p v-else class="text-center">Event not found</p>
</template>
