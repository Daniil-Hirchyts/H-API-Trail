<script setup lang="ts">
import {Button} from '@/components/ui/button';
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card';
import {Input} from '@/components/ui/input';
import {Label} from '@/components/ui/label';
import {useRouter} from 'vue-router';
import {ref} from 'vue';
import {toast} from 'vue-sonner';
import {getAnnuaireUserData} from '@/lib/annuaire-api.ts';
import {Tooltip, TooltipContent, TooltipTrigger} from '@/components/ui/tooltip';
import {InfoIcon} from 'lucide-vue-next';
import {userStore} from "@/stores/user.store.ts";
import {Checkbox} from "@/components/ui/checkbox";

const annuaireLoading = ref(false);
const code = ref('');
const router = useRouter();
const username = ref('');
const email = ref('');
const saving = ref(false);

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
  const formEntries = Object.fromEntries(form.entries());
  if (formEntries['password'] !== formEntries['confirmPassword']) {
    toast.error('Passwords do not match');
    return;
  }
  const roles = formEntries['roleOrga'] === 'true' ? ["ROLE_ORGA"] : [];
  saving.value = true;
  const success = await userStore.register(
      formEntries['username'] as string,
      formEntries['email'] as string,
      formEntries['password'] as string,
      roles
  );
  saving.value = false;
  if (success) await router.push('/login');
}
</script>

<template>
  <div class="w-screen h-screen flex flex-col gap-4 items-center justify-center">
    <Button variant="ghost" class="absolute top-4 left-4">
      <router-link to="/">Retour</router-link>
    </Button>

    <Card class="max-w-md w-full">
      <CardHeader>
        <CardTitle class="text-xl">Sign Up</CardTitle>
        <CardDescription>Enter your information to create an account</CardDescription>
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
          <Button @click="getUserData" class="w-full" :loading="annuaireLoading || saving">
            Récuperer mes informations
          </Button>
        </div>
        <form @submit="handleSubmit" class="grid gap-4">
          <div class="grid gap-2">
            <Label for="username">Username</Label>
            <Input id="username" name="username" required/>
          </div>
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input id="email" name="email" type="email" required/>
          </div>
          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input id="password" name="password" type="password" required/>
          </div>
          <div class="grid gap-2">
            <Label for="confirmPassword">Confirm Password</Label>
            <Input id="confirmPassword" name="confirmPassword" type="password" required/>
          </div>
          <div class="flex items-center space-x-2">
            <input type="hidden" name="roleOrga" value="false"/>
            <Checkbox id="roleOrga" name="roleOrga" value="true"/>
            <Label for="roleOrga">Register as Organizer</Label>
          </div>
          <Button type="submit" :loading="saving" class="w-full">Register</Button>
        </form>
      </CardContent>
    </Card>

    <div class="text-center text-sm">
      Already have an account?
      <router-link to="/login" class="underline">Login</router-link>
    </div>
  </div>
</template>
