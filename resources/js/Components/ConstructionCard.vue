<template>
    <div class="flex items-start justify-between w-full mb-4 rounded bg-gray-50 dark:bg-gray-800 px-6 py-6 shadow">
        <!-- Immagine opzionale -->
        <div v-if="image"
            class="w-[10rem] h-[10rem] mr-6 flex-shrink-0 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <img :src="image" alt="Immagine cantiere" class="object-cover w-full h-full" />
        </div>

        <!-- Testo -->
        <div class="flex flex-col justify-start w-full max-w-[60%] pr-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                {{ name }}
            </h2>
            <p class="text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ description }}
            </p>
            <p class="text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ description2 }}
            </p>
            <p class="text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ dateInEnd }}
            </p>
            <p class="text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ resources }}
            </p>
        </div>

        <!-- Calendario -->
        <div class="overflow-auto" :class="[calendarWidth, calendarHeight]">
            <div class="rounded-lg border border-gray-300 p-2 bg-white dark:bg-gray-900">
                <FullCalendar :options="calendarOptions" class="text-xs leading-tight" />
            </div>
        </div>


<ModalForm
  :show="showModal"
  :showDelete="!!currentEvent.title"
  title="Gestione Task"
  :fields="modalFields"
  v-model="currentEvent"
  @submit="saveEvent"
  @delete="deleteEvent"
  @close="showModal = false"
/>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import ModalForm from '@/Components/ModalForm.vue';

const props = defineProps({
    id: {
      type: [String, Number],
      required: true
    },
    name: String,
    description: String,
    description2: String,
    dateInEnd: String,
    resources: String,
    initialDate: String,
    image: {
        type: String,
        required: false,
        default: null,
    },
    events: {
        type: Array,
        required: false,
        default: () => [],
    },
    calendarHeight: {
        type: String,
        required: false,
        default: 'h-64',
    },
    calendarWidth: {
        type: String,
        required: false,
        default: 'w-[100rem]',
    },
    enableViews: {
        type: Boolean,
        default: false
    },
})
//array per i campi della modale
const modalFields = [
  { id: 'title', name: 'title', label: 'Titolo', elementType: 'input', type: 'text' },
  { id: 'description', name: 'description', label: 'Descrizione', elementType: 'input', type: 'text' },
  { id: 'start', name: 'start', label: 'Inizio', elementType: 'input', type: 'date' },
  { id: 'end', name: 'end', label: 'Fine', elementType: 'input', type: 'date' },
  { id: 'teams', name: 'teams', label: 'Squadre Assegnate', elementType: 'input', type: 'text' },
  { id: 'color', name: 'color', label: 'Colore Evento', elementType: 'input', type: 'color' },
]
// Calcola la data della domenica più recente rispetto ad oggi, azzerando l'orario per evitare problemi di fuso orario
const today = new Date()
// azzera l'orario per evitare problemi di fuso orario
today.setHours(0, 0, 0, 0)

const fromDate = new Date(today)
fromDate.setDate(today.getDate() - today.getDay()) // domenica corrente corretta


// Imposta toDate a 14 giorni dopo fromDate
const toDate = new Date(fromDate)

toDate.setDate(fromDate.getDate() + 14)


const filteredEvents = computed(() => {
    return props.events.filter(event => {
        const start = new Date(event.start)
        return start >= fromDate && start <= toDate
    })
})

const userEvents = ref([])

const storedEvents = sessionStorage.getItem(`calendar-events-${props.id}`)
if (storedEvents) {
  userEvents.value = JSON.parse(storedEvents)
}

const showModal = ref(false)
const currentEvent = ref({
  title: '',
  description: '',
  start: '',
  end: '',
  teams: '',
  color: '',
})

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
  initialView: 'twoWeek',
  views: {
    twoWeek: {
      type: 'dayGrid', // normale dayGrid
      buttonText: '2 settimane'
    }
  },
  visibleRange: {
        start: formatDateToLocalString(fromDate),
        end: formatDateToLocalString(toDate),
    },
    initialDate: formatDateToLocalString(fromDate),
  events: userEvents,
  locale: 'it',
  aspectRatio: 4,
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: props.enableViews
      ? 'twoWeek,dayGridDay,timeGridWeek,timeGridDay,listWeek'
      : 'twoWeek'
  },
  selectable: true,
  select: handleDateSelect,
  eventClick: handleEventClick,
})
console.log(fromDate.toISOString().split('T')[0]);
console.log(fromDate);

function handleDateSelect(info) {
  currentEvent.value = {
    title: '',
    description: '',
    start: info.startStr,
    end: info.endStr,
    teams: '',
    color: '#2563eb' // colore predefinito blu
  }
  showModal.value = true
}

function handleEventClick(info) {
  currentEvent.value = {
    title: info.event.title,
    description: info.event.extendedProps.description || '',
    start: info.event.startStr,
    end: info.event.endStr,
    teams: info.event.extendedProps.teams || '',
    id: info.event.extendedProps.id || '',
    color: info.event.extendedProps.color || '#2563eb',
  }
  showModal.value = true
}

function formatDateToLocalString(date) {
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0') // Mese parte da 0
  const day = String(date.getDate()).padStart(2, '0')
  return `${year}-${month}-${day}`
}

function saveEvent() {
  if (!currentEvent.value.title || !currentEvent.value.start || !currentEvent.value.end) return

  if (!currentEvent.value.id) {
    currentEvent.value.id = currentEvent.value.start + currentEvent.value.title
  }

  const newEvent = {
    title: currentEvent.value.title,
    start: currentEvent.value.start,
    end: currentEvent.value.end,
    id: currentEvent.value.id,
    color: currentEvent.value.color,
    extendedProps: {
        description: currentEvent.value.description,
        teams: currentEvent.value.teams,
        color: currentEvent.value.color,
        id: currentEvent.value.id,
    }
  }

  const index = userEvents.value.findIndex(e => e.start === newEvent.start && e.title === newEvent.title)

  index >=0 ? userEvents.value[index] = newEvent : userEvents.value.push(newEvent);

  sessionStorage.setItem(`calendar-events-${props.id}`, JSON.stringify(userEvents.value))
  showModal.value = false
}

function deleteEvent() {
  const idToDelete = currentEvent.value.start + currentEvent.value.title
  userEvents.value = userEvents.value.filter(e => e.start + e.title !== idToDelete)
  sessionStorage.setItem(`calendar-events-${props.id}`, JSON.stringify(userEvents.value))
  showModal.value = false
}

</script>
<style>
/* Rendi il calendario più compatto */
.fc .fc-daygrid-day-frame {
    padding: 2px !important;
    height: 3.5rem;
}

.fc .fc-daygrid-day-top {
    flex-direction: row;
    justify-content: space-between;
    font-size: 0.7rem;
}

.fc .fc-daygrid-day-events {
    font-size: 0.65rem;
    line-height: 1rem;
}

.fc .fc-scrollgrid {
    border-radius: 8px;
    overflow: hidden;
}

</style>
