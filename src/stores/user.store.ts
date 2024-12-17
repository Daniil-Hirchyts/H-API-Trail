import {reactive} from 'vue';

type State = {
	user: {
		login: string,
		email: string
		roles: string[]
	} | undefined
}

type Actions = {
	isLoggedIn: () => boolean,
}

export const userStore = reactive<State & Actions>({
	user: undefined,
	isLoggedIn() {
		return !!this.user;
	}
});