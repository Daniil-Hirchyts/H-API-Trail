import {get, post} from '@/lib/api.ts';
import {toast} from 'vue-sonner';
import {userStore} from '@/stores/user.store.ts';

export type ApiSportType = {
	id: number,
	name: string,
	localisation: string,
	max_participants: number,
	description: string,
	organized_by: {
		id: number,
		login: string,
		email: string
	},
	price: number,
	start_at: string,
	end_at: string,
	sport: string[],
	participants_visible: boolean,
	participating: boolean,
	participations: {
		user: {
			'id': number,
			'login': string
			'email': string
		}
	}[]
}

export type SportType = {
	id: number,
	name: string,
	localisation: string,
	max_participants: number,
	description: string,
	organized_by: {
		id: number,
		login: string,
		email: string
	},
	price: number,
	start_at: Date,
	end_at: Date,
	sport: string[],
	participants_visible: boolean,
	participating: boolean,
	participations: {
		user: {
			'id': number,
			'login': string
			'email': string
		}
	}[]
}

export class EventService {
	private static parseSport(data: ApiSportType): SportType {
		return {
			id: data.id,
			name: data.name,
			localisation: data.localisation,
			max_participants: data.max_participants,
			description: data.description,
			organized_by: {
				id: data.organized_by.id,
				login: data.organized_by.login,
				email: data.organized_by.email
			},
			price: data.price,
			start_at: new Date(data.start_at),
			end_at: new Date(data.end_at),
			sport: data.sport,
			participations: data.participations,
			participants_visible: data.participants_visible,
			participating: data.participating ?? false
		};
	}

	static async getAll() {
		const {success, data} = await get<{ member: ApiSportType[] }>('events');
		if (!success) {
			toast.error('Erreur lors de la récupération des événements');
			return null;
		}
		return data!.member.map(this.parseSport);
	}

	static async get(id: string) {
		const {success, data} = await get<ApiSportType>(`events/${id}`);
		if (!success) {
			toast.error('Erreur lors de la récupération de l\'événement');
			return null;
		}
		return this.parseSport(data!);
	}

	static async create(eventData: Partial<SportType>) {
		const {success, data} = await post('events', eventData);
		if (success) {
			toast.success('Événement créé avec succès!');
			return this.parseSport(data as ApiSportType);
		}
		toast.error('Erreur lors de la création de l\'événement');
		return null;
	}

	static async participate(id: number) {
		if (!userStore.isLoggedIn()) {
			toast.error('Vous devez être connecté pour participer à un événement');
			return false;
		}
		const {success} = await post('participations', {
			event: `/apiTrail/public/api/events/${id}`
		});
		if (!success) {
			toast.error('Erreur lors de l\'inscription à l\'événement');
		} else {
			toast.success('Inscription réussie');
		}
		return success;
	}
}
