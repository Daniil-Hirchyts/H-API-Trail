<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Profile</h1>
    <Card>
      <CardHeader>
        <CardTitle>Edit Profile</CardTitle>
        <CardDescription>Update your account information below.</CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit="handleUpdate" class="grid gap-4">
          <div class="grid gap-2">
            <Label for="username">Username</Label>
            <Input id="username" name="username" v-model="username" required />
          </div>
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input id="email" name="email" type="email" v-model="email" required />
          </div>
          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input id="password" name="password" type="password" placeholder="Leave blank to keep current password" />
          </div>
          <Button type="submit" :loading="saving" class="w-full">Update Profile</Button>
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
          <Button variant="destructive" @click="handleDelete" :loading="deleting">Delete Account</Button>
        </CardContent>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import {Card, CardContent, CardDescription, CardHeader, CardTitle} from '@/components/ui/card';
import {Input} from '@/components/ui/input';
import {Label} from '@/components/ui/label';
import {Button} from '@/components/ui/button';
import {toast} from 'vue-sonner';
import {computed, ref} from 'vue';
import {useRouter} from 'vue-router';
import {userStore} from '@/stores/user.store';
import {patch, del} from '@/lib/api';

const router = useRouter();
const saving = ref(false);
const deleting = ref(false);
const password = ref('');

const username = computed({
  get: () => userStore.user?.login || '',
  set: (value: string) => {
    if (userStore.user) userStore.user.login = value;
  },
});

const email = computed({
  get: () => userStore.user?.email || '',
  set: (value: string) => {
    if (userStore.user) userStore.user.email = value;
  },
});

interface UpdatedData {
  login: string;
  email: string;
  plainPassword?: string;
}

async function handleUpdate(e: Event) {
  e.preventDefault();

  saving.value = true;

  try {
    const updatedData: UpdatedData = {
      login: username.value,
      email: email.value,
    };

    if (password.value) {
      updatedData.plainPassword = password.value;
    }

    const {success} = await patch(`utilisateurs/${userStore.user?.login}`, updatedData);

    if (success) {
      toast.success('Profile updated successfully!');
      userStore.user = {...userStore.user, ...updatedData};
    } else {
      toast.error('Failed to update profile. Please try again.');
    }
  } catch (error) {
    console.error('Error updating profile:', error);
    toast.error('An unexpected error occurred.');
  } finally {
    saving.value = false;
  }
}

async function handleDelete() {
  deleting.value = true;

  try {
    const {success} = await del(`utilisateurs/${userStore.user?.login}`);

    if (success) {
      toast.success('Account deleted successfully.');
      await userStore.logout();
      await router.push('/');
    } else {
      toast.error('Failed to delete account. Please try again.');
    }
  } catch (error) {
    console.error('Error deleting account:', error);
    toast.error('An unexpected error occurred.');
  } finally {
    deleting.value = false;
  }
}
</script>
