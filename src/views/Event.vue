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
      <div class="flex items-center gap-1 pt-1">
        <Badge variant="secondary">{{ event.localisation }}</Badge>
        <Badge variant="secondary">{{ event.sport }}</Badge>
        <Badge variant="secondary">{{ event.max_participants }} participants</Badge>
        <Badge variant="secondary">{{ event.price }}€</Badge>
      </div>
      <Button @click="EventService.participate(event.id)" class="absolute top-6 right-6">
        Participate
      </Button>
    </CardHeader>
    <CardContent>
      <p>{{ event.description }}</p>
    </CardContent>
  </Card>
  <p v-else class="text-center">Event not found</p>
</template>
