<script setup lang="ts">
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card';
import {Input} from '@/components/ui/input';
import {Label} from '@/components/ui/label';
import {Button} from '@/components/ui/button';
import {ref, computed} from 'vue';
import {userStore} from '@/stores/user.store';
import {Eye, EyeOff} from "lucide-vue-next";

const email = ref('');
const password = ref('');
const isPasswordVisible = ref(false);

const updatedData = computed(() => {
  const data: { login?: string; email?: string; plainPassword?: string } = {};
  if (email.value.trim()) data.email = email.value.trim();
  if (password.value.trim()) data.plainPassword = password.value.trim();
  return data;
});

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value;
};

async function handleUpdate(e: Event) {
  e.preventDefault();
  const success = await userStore.updateProfile(updatedData.value);
  if (success) {
    email.value = '';
    password.value = '';
  }
}

async function handleDelete() {
  await userStore.deleteProfile();
}
</script>

<template>
  <div class="w-full mx-auto">
    <h1 class="text-2xl font-bold mb-4">Profile</h1>
    <Card>
      <CardHeader>
        <CardTitle>Edit Profile</CardTitle>
        <CardDescription>Update your account information below.</CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit="handleUpdate" class="grid grid-cols-2 gap-4">
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input id="email" name="email" type="email" v-model="email" placeholder="Leave blank to keep current email"/>
          </div>
          <div class="grid gap-2 relative">
            <Label for="password">Password</Label>
            <div class="relative h-fit">
              <Input
                  id="password"
                  name="password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  placeholder="Leave blank to keep current password"
                  v-model="password"
              />
              <button
                  type="button"
                  class="absolute right-2 top-1/2 flex items-center justify-center  text-muted-foreground/80 hover:text-foreground -translate-y-1/2"
                  @click="togglePasswordVisibility"
                  :aria-label="isPasswordVisible ? 'Hide password' : 'Show password'"
                  :aria-pressed="isPasswordVisible.toString()"
              >
                <component :is="isPasswordVisible ? EyeOff : Eye" class="w-5 h-5" aria-hidden="true"/>
              </button>
            </div>
          </div>
          <Button type="submit" :loading="userStore.saving" class="w-full col-span-2">Update Profile</Button>
        </form>
      </CardContent>
    </Card>

    <div class="mt-6">
      <Card>
        <CardHeader>
          <CardTitle>Danger Zone</CardTitle>
          <CardDescription>Delete your account permanently.</CardDescription>
        </CardHeader>
        <CardContent>
          <Button variant="destructive" @click="handleDelete" :loading="userStore.loading">Delete Account</Button>
        </CardContent>
      </Card>
    </div>
  </div>
</template>
