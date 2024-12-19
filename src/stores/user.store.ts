import {reactive} from 'vue';
import {AuthService} from '@/lib/services/auth.service';

type State = {
	user: {
		login: string;
		email: string;
		roles: string[];
	} | undefined;
	loading: boolean;
	saving: boolean;
	initialized: boolean;
};

type Actions = {
	isLoggedIn: () => boolean;
	login: (username: string, password: string) => Promise<boolean>;
	register: (username: string, email: string, password: string, roles: string[]) => Promise<boolean>;
	logout: () => Promise<void>;
	initializeUser: () => Promise<void>;
};

export const userStore = reactive<State & Actions>({
	user: undefined,
	loading: false,
	saving: false,
	initialized: false,

	isLoggedIn() {
		return !!this.user;
	},

	async login(username: string, password: string) {
		this.loading = true;
		const success = await AuthService.login(username, password);
		this.loading = false;
		return success;
	},

	async register(username: string, email: string, password: string, roles: string[]) {
		this.saving = true;
		const { success } = await AuthService.register(username, email, password, roles);
		this.saving = false;
		return success;
	},


	async logout() {
		this.loading = true;
		await AuthService.logout();
		this.user = undefined;
		this.loading = false;
	},

	async initializeUser() {
		this.loading = true;
		const success = await AuthService.refreshToken();
		if (!success) {
			this.user = undefined;
		}
		this.initialized = true;
		this.loading = false;
	}
});
