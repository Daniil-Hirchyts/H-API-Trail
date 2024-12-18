<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger
} from '@/components/ui/dialog';
import {Button} from '@/components/ui/button';
import {Input} from '@/components/ui/input';
import {Label} from '@/components/ui/label';
import {Textarea} from '@/components/ui/textarea';
import DatePicker from '@/components/ui/date-picker.vue';

async function handleSubmit(e: Event) {
  e.preventDefault();
  const form = new FormData(e.target as HTMLFormElement);
  const dt = Object.fromEntries(form.entries());
  console.log(dt);
}
</script>

<template>
  <Dialog>
    <DialogTrigger as-child>
      <Button variant="outline">
        Créer un événement
      </Button>
    </DialogTrigger>
    <DialogContent class="sm:max-w-[425px] overflow-y-auto max-h-[90vh]">
      <DialogHeader>
        <DialogTitle>Nouvel événement</DialogTitle>
        <DialogDescription>
          Créez un nouvel événement en remplissant les champs ci-dessous.
        </DialogDescription>
      </DialogHeader>
      <form class="grid gap-4" v-on:submit="handleSubmit">
        <div class="grid gap-2">
          <Label for="name">Nom</Label>
          <Input id="name" name="name"/>
        </div>
        <div class="grid gap-2">
          <Label for="localisation">Lieu</Label>
          <Input id="localisation" name="localisation"/>
        </div>
        <div class="grid gap-2">
          <Label for="description">Description</Label>
          <Textarea id="description" name="description"/>
        </div>
        <div class="grid gap-2">
          <Label for="start">Dates</Label>
          <DatePicker id="start" name="start"/>
        </div>
        <div class="grid gap-2">
          <Label for="price">Prix</Label>
          <Input id="price" name="price" placeholder="0 €"/>
        </div>
        <div class="grid gap-2">
          <Label for="maxParticipants">Nombre max de participants</Label>
          <Input id="maxParticipants" name="maxParticipants"/>
        </div>
        <Button type="submit">
          Ajouter
        </Button>
      </form>
    </DialogContent>
  </Dialog>
</template>