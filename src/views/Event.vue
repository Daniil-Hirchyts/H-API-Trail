<script setup lang="ts">
import dayjs from 'dayjs';
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card';
import {Badge} from '@/components/ui/badge';
import {Button} from '@/components/ui/button';
import {useRoute, useRouter} from 'vue-router';
import {ref, onMounted} from 'vue';
import {eventStore} from '@/stores/event.store';

const event = ref(null);

const route = useRoute();
const router = useRouter();

onMounted(async () => {
  const fetchedEvent = await eventStore.getEventById(route.params.id.toString());
  if (!fetchedEvent) {
    await router.push('/404');
    return;
  }
  event.value = fetchedEvent;
});
</script>

<template>
  <Card v-if="event" class="group-hover:shadow-lg transition-all duration-150">
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
      <Button class="absolute top-6 right-6">
        Participate
      </Button>
    </CardHeader>
    <CardContent>
      <p>{{ event.description }}</p>
    </CardContent>
  </Card>
</template>
