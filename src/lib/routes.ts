// src/router/index.js
import {createRouter, createWebHistory} from 'vue-router';
import HomePage from '@/views/Home.vue';
import LoginPage from '@/views/Login.vue';
import RegisterPage from '@/views/Register.vue';
import RacesPage from '@/views/Races.vue';
import ProfilePage from '@/views/Profile.vue';
import EventPage from '@/views/Event.vue';
import MainLayout from '@/layouts/Main-Layout.vue';
import AuthLayout from '@/layouts/Auth-Layout.vue';

const routes = [
	{
		path: '/',
		component: MainLayout,
		children: [
			{
				path: '',
				name: 'Home',
				component: HomePage
			},
			{
				path: 'races',
				name: 'Races',
				component: RacesPage
			},
			{
				path: 'profile',
				name: 'Profile',
				component: ProfilePage
			},
			{
				path: 'event/:id',
				name: 'Event',
				component: EventPage
			}
		]
	},
	{
		path: '/',
		component: AuthLayout,
		children: [
			{
				path: 'login',
				name: 'Login',
				component: LoginPage
			},
			{
				path: 'register',
				name: 'Register',
				component: RegisterPage
			}]
	}
];

// Création du routeur
const router = createRouter({
	history: createWebHistory(), // Utilise l'historique HTML5
	routes // Ajout des routes
});

export default router;