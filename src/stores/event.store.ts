import {reactive} from 'vue'
import {EventService, SportType} from '@/lib/services/event.service'

type State = {
    events: SportType[]
}

type Actions = {
    setEvents: (events: SportType[]) => void
    addEvent: (event: SportType) => void
    loadEvents: () => Promise<void>
}

export const eventStore = reactive<State & Actions>({
    events: [],
    setEvents(events: SportType[]) {
        this.events = events
    },
    addEvent(event: SportType) {
        this.events.push(event)
    },
    async loadEvents() {
        const data = await EventService.getAll()
        if (data) {
            this.setEvents(data)
        }
    },
    async getEventById(id: string): Promise<SportType | null> {
        const existingEvent = this.events.find(event => event.id.toString() === id);
        if (existingEvent) {
            return existingEvent;
        }

        const event = await EventService.get(id);
        if (event) {
            this.addEvent(event);
            return event;
        }
        return null;
    }
});
