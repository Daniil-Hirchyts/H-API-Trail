<script setup lang="ts">
import {Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle} from '@/components/ui/card';
import dayjs from 'dayjs';
import {Badge} from '@/components/ui/badge';
import {SportType} from '@/lib/services/event.service.ts';

defineProps({
  event: {
    type: Object as () => SportType,
    required: true
  }
});
</script>

<template>
  <router-link :to="'/event/' + event.id" class="group">
    <Card class="group-hover:shadow-lg transition-all duration-150">
      <CardHeader>
        <CardTitle>{{ event.name }}</CardTitle>
        <CardDescription>{{ dayjs(event.start_at).format('DD/MM/YYYY HH:mm') }} au
          {{ dayjs(event.end_at).format('DD/MM/YYYY HH:mm') }}
        </CardDescription>
      </CardHeader>
      <CardContent>
        <p>{{ event.description }}</p>
      </CardContent>
      <CardFooter class="gap-1">
        <Badge variant="secondary">{{ event.localisation }}</Badge>
        <Badge variant="secondary">{{ event.sport }}</Badge>
        <Badge variant="secondary">{{ event.max_participants }} participants</Badge>
        <Badge variant="secondary">{{ event.price }}€</Badge>
      </CardFooter>
    </Card>
  </router-link>
</template>