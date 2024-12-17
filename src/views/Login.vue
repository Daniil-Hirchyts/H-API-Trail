<script setup lang="ts">
import {Button} from '@/components/ui/button';
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card';
import {Input} from '@/components/ui/input';
import {Label} from '@/components/ui/label';
import {AuthService} from '@/lib/services/auth.service.ts';
import {ref} from 'vue';
import {useRouter} from 'vue-router';

const router = useRouter();
const isLoading = ref(false);

async function handleSubmit(e: Event) {
  e.preventDefault();
  const form = new FormData(e.target as HTMLFormElement);
  const data = Object.fromEntries(form.entries()) as {
    username: string;
    password: string;
  };
  isLoading.value = true;
  const success = await AuthService.login(data.username, data.password);
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
    <Card class="w-full max-w-md">
      <CardHeader>
        <CardTitle class="text-2xl">
          Login
        </CardTitle>
        <CardDescription>
          Enter your email below to login to your account.
        </CardDescription>
      </CardHeader>
      <CardContent>
        <form v-on:submit="handleSubmit" class="grid gap-4">
          <div class="grid gap-2">
            <Label for="username">Username</Label>
            <Input id="username" type="text" name="username" required/>
          </div>
          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input id="password" name="password" type="password" required/>
          </div>
          <Button class="w-full" type="submit" :loading="isLoading">
            Sign in
          </Button>
        </form>
      </CardContent>
    </Card>
    <div class="text-center text-sm">
      Don't have an account?
      <router-link to="/register" class="underline">Register</router-link>
    </div>
  </div>
</template>