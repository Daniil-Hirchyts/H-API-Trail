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
import {Tooltip, TooltipContent, TooltipTrigger} from '@/components/ui/tooltip';
import {InfoIcon} from 'lucide-vue-next';

const router = useRouter();
const isLoading = ref(false);
const annuaireLoading = ref(false);
const code = ref('');
const violations = ref<Record<string, string>>({});
const username = ref('');
const email = ref('');

async function getUserData() {
  if (code.value !== '') {
    annuaireLoading.value = true;
    const data = await getAnnuaireUserData(code.value);
    annuaireLoading.value = false;
    if (data) {
      username.value = data.username;
      email.value = data.email;
    }
  }
}

async function handleSubmit(e: Event) {
  e.preventDefault();
  const form = new FormData(e.target as HTMLFormElement);
  const dt = Object.fromEntries(form.entries()) as {
    username: string;
    password: string;
    email: string;
    confirmPassword: string;
  };
  if (dt.password !== dt.confirmPassword) {
    toast.error('Les mots de passe ne correspondent pas');
    return;
  }
  isLoading.value = true;
  const {success, data} = await AuthService.register(dt.username, dt.email, dt.password);
  isLoading.value = false;
  if (success) {
    await router.push('/login');
  } else {
    if (data.violations) {
      for (const value of data.violations) {
        violations.value[value.propertyPath] = value.message;
      }
    }
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
            <Label for="code" class="flex items-center gap-1">
              Code
              <Tooltip>
                <TooltipTrigger>
                  <InfoIcon :size="16"/>
                </TooltipTrigger>
                <TooltipContent class="max-w-[250px]">
                  <p>Aller sur votre profile annuaire -> Modifier -> Copier votre code</p>
                </TooltipContent>
              </Tooltip>
            </Label>
            <Input id="code" :value="code" @input="code = $event.target.value"/>
          </div>
          <Button @click="getUserData" class="w-full" :loading="isLoading || annuaireLoading">
            Récuperer mes informations
          </Button>
        </div>
        <form v-on:submit="handleSubmit" class="grid gap-4">
          <div class="grid gap-2">
            <Label for="username">Username</Label>
            <Input id="username" name="username" required minlength="4" maxlength="30" :value="username"
                   @input="username = $event.target.value"/>
            <span class="text-red-500 text-sm" v-if="violations.login">{{ violations.login }}</span>
          </div>
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
                id="email"
                type="email"
                name="email"
                placeholder="m@example.com"
                required
                :value="email"
                @input="email = $event.target.value"
            />
            <span class="text-red-500 text-sm" v-if="violations.email">{{ violations.email }}</span>
          </div>
          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input id="password" type="password" name="password" minlength="8" maxlength="30"/>
            <span class="text-red-500 text-sm" v-if="violations.plainPassword">{{ violations.plainPassword }}</span>
          </div>
          <div class="grid gap-2">
            <Label for="confirmPassword">Confirm password</Label>
            <Input id="confirmPassword" type="password" name="confirmPassword" minlength="8" maxlength="30"/>
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