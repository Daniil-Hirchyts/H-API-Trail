<script setup lang="ts">
import {Button} from '@/components/ui/button';
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card';
import {Input} from '@/components/ui/input';
import {Label} from '@/components/ui/label';
import {useRouter} from 'vue-router';
import {ref} from 'vue';
import {AuthService} from '@/lib/services/auth.service.ts';
import {toast} from 'vue-sonner';
import {getAnnuaireUserData} from '@/lib/annuaire-api.ts';

const router = useRouter();
const isLoading = ref(false);
const code = ref('');

async function getUserData() {
  if (code.value !== '') {
    await getAnnuaireUserData(code.value);
  }
}

async function handleSubmit(e: Event) {
  e.preventDefault();
  const form = new FormData(e.target as HTMLFormElement);
  const data = Object.fromEntries(form.entries()) as {
    username: string;
    password: string;
    email: string;
    confirmPassword: string;
  };
  console.log(data);
  if (data.password !== data.confirmPassword) {
    toast.error('Les mots de passe ne correspondent pas');
    return;
  }
  isLoading.value = true;
  const success = await AuthService.register(data.username, data.email, data.password);
  isLoading.value = false;
  if (success) {
    await router.push('/');
  }
}
</script>

<template>
  <div class="w-screen h-screen flex flex-col gap-4 items-center justify-center">
    <Button variant="ghost" class="absolute top-4 left-4">
      <router-link to="/">Retour</router-link>
    </Button>

    <Card class="max-w-md w-full">
      <CardHeader>
        <CardTitle class="text-xl">
          Sign Up
        </CardTitle>
        <CardDescription>
          Enter your information to create an account
        </CardDescription>
      </CardHeader>
      <CardContent>
        <div class="flex flex-col gap-4 pb-8">
          <div class="grid gap-2">
            <Label for="code">Code</Label>
            <Input id="code" :value="code" @input="code = $event.target.value"/>
          </div>
          <Button @click="getUserData" class="w-full" :loading="isLoading">
            Récuperer mes informations
          </Button>
        </div>
        <form v-on:submit="handleSubmit" class="grid gap-4">
          <div class="grid gap-2">
            <Label for="username">Username</Label>
            <Input id="username" name="username" required/>
          </div>
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
                id="email"
                type="email"
                name="email"
                placeholder="m@example.com"
                required
            />
          </div>
          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input id="password" type="password" name="password"/>
          </div>
          <div class="grid gap-2">
            <Label for="password">Confirm password</Label>
            <Input id="password" type="password" name="confirmPassword"/>
          </div>
          <Button type="submit" class="w-full" :loading="isLoading">
            Create an account
          </Button>
        </form>
      </CardContent>
    </Card>
    <div class="text-center text-sm">
      Already have an account?
      <router-link to="/login" class="underline">Login</router-link>
    </div>
  </div>
</template>