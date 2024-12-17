import {get, post} from '@/lib/api.ts';
import {toast} from 'vue-sonner';
import {userStore} from '@/stores/user.store.ts';

export class AuthService {
	static async login(login: string, password: string) {
		const {success, data} = await post<{
			data: {
				login: string,
				roles: string[]
			}
		}>('auth/login', {login, password});
		if (!success) {
			toast.error('Nom d\'utilisateur ou mot de passe incorrect');
		} else {
			userStore.user = data?.data;
			toast.success('Connexion réussie');
		}
		return success;
	}

	static async register(login: string, email: string, password: string) {
		const {success, data} = await post<{
			data: {
				login: string,
				roles: string[]
			}
		}>('auth/register', {login, plainPassword: password, email, roles: []});
		if (!success) {
			toast.error('Erreur lors de l\'inscription');
		} else {
			toast.success('Inscription réussie');
			userStore.user = data?.data;
		}
		return success;
	}

	static async refreshToken() {
		const {success, data} = await get<{
			data: {
				login: string,
				roles: string[]
			}
		}>('auth/refresh-token');
		if (success) {
			userStore.user = data?.data;
		}
		return success;
	}

	static async logout() {
		userStore.user = undefined;
		const {success} = await get('auth/logout');
		if (!success) {
			toast.error('Erreur lors de la déconnexion');
		} else {
			toast.success('Déconnexion réussie');
		}
	}
}