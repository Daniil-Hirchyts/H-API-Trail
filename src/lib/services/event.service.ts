import {get} from '@/lib/api.ts';
import {toast} from 'vue-sonner';

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
	sport: string[]
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
	sport: string
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
			sport: data.sport[0]
		};
	}

	static async getAll() {
		const {success, data} = await get<{
			member: ApiSportType[]
		}>('events');
		if (!success) {
			toast.error('Erreur lors de la récupération des événements');
			return null;
		} else {
			const sports: SportType[] = [];
			for (const sport of data!.member) {
				sports.push(this.parseSport(sport));
			}
			return sports;
		}
	}

	static async get(id: string) {
		const {success, data} = await get<ApiSportType>(`events/${id}`);
		if (!success) {
			toast.error('Erreur lors de la récupération de l\'événement');
			return null;
		} else {
			return this.parseSport(data!);
		}
	}
}