import {reactive} from 'vue';
import {EventService, SportType} from '@/lib/services/event.service';

type State = {
    events: SportType[];
    loading: boolean;
};

type Actions = {
    setEvents: (events: SportType[]) => void;
    addEvent: (event: SportType) => void;
    loadEvents: () => Promise<void>;
    getEventById: (id: string) => Promise<SportType | null>;
};

export const eventStore = reactive<State & Actions>({
    events: [],
    loading: false,

    setEvents(events: SportType[]) {
        this.events = events;
    },

    addEvent(event: SportType) {
        this.events.push(event);
    },

    async loadEvents() {
        this.loading = true;
        const data = await EventService.getAll();
        if (data) {
            this.setEvents(data);
        }
        this.loading = false;
    },

    async getEventById(id: string): Promise<SportType | null> {
        this.loading = true;
        const existingEvent = this.events.find(event => event.id.toString() === id);
        if (existingEvent) {
            this.loading = false;
            return existingEvent;
        }

        const event = await EventService.get(id);
        if (event) {
            this.addEvent(event);
        }
        this.loading = false;
        return event;
    }
});
