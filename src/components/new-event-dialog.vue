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
import {Checkbox} from "@/components/ui/checkbox";
import {ref} from "vue";
import {post} from '@/lib/api';
import {toast} from 'vue-sonner';
import { eventStore } from '@/stores/event.store';
import { EventService, ApiSportType } from '@/lib/services/event.service';

const dateRange = ref({
  start: null,
  end: null,
});

// Control dialog state
const openDialog = ref(false);

async function handleSubmit(e: Event) {
  e.preventDefault();
  const form = new FormData(e.target as HTMLFormElement);
  const formEntries = Object.fromEntries(form.entries());

  if (!formEntries['start']) {
    console.error('No date range data found.');
    toast.error('Veuillez sélectionner une plage de dates.');
    return;
  }

  try {
    const value = JSON.parse(formEntries['start'] as string);

    if (!value.start || !value.end) {
      console.error('Please select a valid date range before submitting.');
      toast.error('Veuillez sélectionner une plage de dates valide.');
      return;
    }

    const formattedData: any = {
      name: formEntries['name'],
      localisation: formEntries['localisation'],
      description: formEntries['description'],
      start_at: new Date(value.start).toISOString(),
      end_at: new Date(value.end).toISOString(),
      price: parseFloat(formEntries['price'] as string) || 0,
      max_participants: parseInt(formEntries['maxParticipants'] as string, 10) || 0,
      participants_visible: formEntries['showParticipants'] === 'true',
      sport: ["tennis"]
    };

    const {success, data} = await post('events', formattedData);

    if (success && data) {
      toast.success('Événement créé avec succès!');
      // Parse the returned API data into SportType
      const newEvent = EventService['parseSport'](data as ApiSportType);

      // Add the new event to the store
      eventStore.addEvent(newEvent);

      // Close the dialog
      openDialog.value = false;
    } else {
      toast.error('Erreur lors de la création de l\'événement');
      console.error('Error creating event:', data);
    }
  } catch (error) {
    console.error('Invalid date range format:', error);
    toast.error('Format de date invalide.');
  }
}
</script>

<template>
  <Dialog v-model:open="openDialog">
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
      <form class="grid gap-4" @submit="handleSubmit">
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
          <DatePicker id="start" name="start" v-model="dateRange"/>
          <input type="hidden" name="start" :value="JSON.stringify(dateRange)"/>
        </div>
        <div class="grid gap-2">
          <Label for="price">Prix</Label>
          <Input id="price" name="price" placeholder="0 €"/>
        </div>
        <div class="grid gap-2">
          <Label for="maxParticipants">Nombre max de participants</Label>
          <Input id="maxParticipants" name="maxParticipants"/>
        </div>
        <div class="flex items-center space-x-2">
          <input type="hidden" name="showParticipants" value="false"/>
          <Checkbox id="showParticipants" name="showParticipants" value="true"/>
          <Label for="showParticipants">Visibilité des participants</Label>
        </div>
        <Button class="mt-4" type="submit" :disabled="!dateRange.start || !dateRange.end">
          Ajouter
        </Button>
      </form>
    </DialogContent>
  </Dialog>
</template>
