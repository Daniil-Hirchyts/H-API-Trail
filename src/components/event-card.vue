<script setup lang="ts">
import {Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle} from '@/components/ui/card';
import dayjs from 'dayjs';
import {Badge} from '@/components/ui/badge';

defineProps({
  eventId: String,
  name: String,
  startDate: Date,
  endDate: Date,
  location: String,
  sport: String,
  description: String,
  maxParticipants: Number,
  price: Number
});
</script>

<template>
  <router-link :to="'/event/' + eventId" class="group">
    <Card class="group-hover:shadow-lg transition-all duration-150">
      <CardHeader>
        <CardTitle>{{ name }}</CardTitle>
        <CardDescription>{{ location }} - {{ dayjs(startDate).format('DD/MM/YYYY HH:mm') }} au
          {{ dayjs(endDate).format('DD/MM/YYYY HH:mm') }}
        </CardDescription>
      </CardHeader>
      <CardContent>
        <p>{{ description }}</p>
      </CardContent>
      <CardFooter class="gap-1">
        <Badge variant="secondary">{{ location }}</Badge>
        <Badge variant="secondary">{{ sport }}</Badge>
        <Badge variant="secondary">{{ maxParticipants }} participants</Badge>
        <Badge variant="secondary">{{ price }}€</Badge>
      </CardFooter>
    </Card>
  </router-link>
</template>