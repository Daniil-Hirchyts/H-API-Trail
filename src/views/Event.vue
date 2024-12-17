<script setup lang="ts">
import dayjs from 'dayjs';
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card';
import {Badge} from '@/components/ui/badge';
import {Button} from '@/components/ui/button';

const location = 'Paris';
const startDate = new Date('2022-01-01T12:00:00Z');
const endDate = new Date('2022-01-01T14:00:00Z');
const description = 'The Ultra-Trail du Mont-Blanc is a single-stage mountain ultramarathon. It takes place once a year in the Alps, across France, Italy and Switzerland.';
const sport = 'Trail';
const maxParticipants = 1500;
const price = 500;
</script>

<template>
  <Card class="group-hover:shadow-lg transition-all duration-150">
    <CardHeader class="relative">
      <CardTitle>Event {{ $route.params.id }}</CardTitle>
      <CardDescription>{{ location }} - {{ dayjs(startDate).format('DD/MM/YYYY HH:mm') }} au
        {{ dayjs(endDate).format('DD/MM/YYYY HH:mm') }}
      </CardDescription>
      <div class="flex items-center gap-1 pt-1">
        <Badge variant="secondary">{{ location }}</Badge>
        <Badge variant="secondary">{{ sport }}</Badge>
        <Badge variant="secondary">{{ maxParticipants }} participants</Badge>
        <Badge variant="secondary">{{ price }}€</Badge>
      </div>
      <Button class="absolute top-6 right-6">
        Participate
      </Button>
    </CardHeader>
    <CardContent>
      <p>{{ description }}</p>
    </CardContent>
  </Card>
</template>
