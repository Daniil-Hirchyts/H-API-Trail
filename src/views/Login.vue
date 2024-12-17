<script setup lang="ts">
import {Button} from '@/components/ui/button';
import {Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle} from '@/components/ui/card';
import {Input} from '@/components/ui/input';
import {Label} from '@/components/ui/label';
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
      <CardContent class="grid gap-4">
        <div class="grid gap-2">
          <Label for="email">Email</Label>
          <Input id="email" type="email" placeholder="m@example.com" required/>
        </div>
        <div class="grid gap-2">
          <Label for="password">Password</Label>
          <Input id="password" type="password" required/>
        </div>
      </CardContent>
      <CardFooter>
        <Button class="w-full">
          Sign in
        </Button>
      </CardFooter>
    </Card>
    <div class="text-center text-sm">
      Don't have an account?
      <router-link to="/register" class="underline">Register</router-link>
    </div>
  </div>
</template>