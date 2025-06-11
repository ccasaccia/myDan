<template>
  <div class="px-4 sm:px-6 lg:px-8">
    <!-- Riga con titolo + switch -->
    <div class="flex justify-between items-center mb-0 m-4">
      <h1 class="text-2xl font-bold">
        {{ constructionSite.id ? 'Modifica Cantiere' : 'Crea Nuovo Cantiere' }}
      </h1>
      <div class="flex items-center gap-2">
        <InputField
          id="is_active"
          name="is_active"
          :label="''"
          elementType="checkbox"
          v-model="isActive"
          :switchStyle="true"
        />
      </div>
    </div>

    <!-- Parte sotto: form + calendario -->
    <div class="flex flex-col md:flex-row items-start justify-between w-full h-[calc(100vh-8rem)] pt-2">
      <div class="flex flex-col w-full max-w-[60%] p-6 h-full">
        <FormBuilder
          :fields="fields"
          :errors="errors"
          :submitUrl="submitUrl"
          :method="method"
          class="h-full flex-1"
        />
        <div v-if="image" class="mt-4 w-full">
          <img :src="imageUrl" alt="Preview Immagine" class="rounded-md shadow-md max-h-64 object-cover mx-auto" />
        </div>
      </div>

      <div class="flex flex-col w-[40%] p-6">
        <div class="rounded-lg border border-gray-300 p-2 bg-white dark:bg-gray-900 flex flex-col h-96">
          <FullCalendar :options="calendarOptions" class="flex-1 text-xs leading-tight" />
        </div>
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

<script>
import FormBuilder from '@/Components/FormBuilder.vue';
import InputField from '@/Components/InputField.vue';
import FullCalendar from '@fullcalendar/vue3'; // <--- CORRETTO
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import ModalForm from '@/Components/ModalForm.vue';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export default {
  components: { FormBuilder, InputField, FullCalendar, ModalForm },
  props: {
    constructionSite: {
      type: Object,
      default: () => ({}),
    },
  },
  setup(props) {
    const isActive = ref(props.constructionSite.is_active ?? true);
    const image = ref(null);
    const imageUrl = computed(() => {
      if (image.value && typeof image.value === 'object') {
        return URL.createObjectURL(image.value);
      }
      return null;
    });
    const modalFields = [
  { id: 'title', name: 'title', label: 'Titolo', elementType: 'input', type: 'text' },
  { id: 'description', name: 'description', label: 'Descrizione', elementType: 'input', type: 'text' },
  { id: 'start', name: 'start', label: 'Inizio', elementType: 'input', type: 'date' },
  { id: 'end', name: 'end', label: 'Fine', elementType: 'input', type: 'date' },
  { id: 'teams', name: 'teams', label: 'Squadre Assegnate', elementType: 'input', type: 'text' },
  { id: 'color', name: 'color', label: 'Colore Evento', elementType: 'input', type: 'color' },
]
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const fromDate = new Date(today);
    fromDate.setDate(today.getDate() - today.getDay());
    const toDate = new Date(fromDate);
    toDate.setDate(fromDate.getDate() + 14);

    const userEvents = ref([]);
    const storedEvents = sessionStorage.getItem(`calendar-events-${props.constructionSite.id || 'new'}`);
    if (storedEvents) {
      userEvents.value = JSON.parse(storedEvents);
    }

    const showModal = ref(false);
    const currentEvent = ref({
      title: '',
      description: '',
      start: '',
      end: '',
      teams: '',
      color: '',
    });

    const calendarOptions = ref({
      plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
      initialView: 'dayGridMonth',
      initialDate: formatDateToLocalString(fromDate),
      events: userEvents,
      locale: 'it',
      buttonText: {
        today: 'Oggi',
        month: 'Mese',
        week: 'Settimana',
        day: 'Giorno',
        list: 'Lista'
      },
      editable: true,
      eventDrop: handleEventDrop,
      eventResize: handleEventDrop,
      aspectRatio: 6,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth twoWeek',
      },
      selectable: true,
      select: handleDateSelect,
      eventClick: handleEventClick,
      views: {
        twoWeek: {
          type: 'dayGrid',
          duration: { weeks: 2 },
          buttonText: '2 settimane'
        }
      },
    });

    function formatDateToLocalString(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }

    function handleDateSelect(info) {
      currentEvent.value = {
        title: '',
        description: '',
        start: info.startStr,
        end: info.endStr,
        teams: '',
        color: '#2563eb'
      };
      showModal.value = true;
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
      };
      showModal.value = true;
    }

    function saveEvent() {
      if (!currentEvent.value.title || !currentEvent.value.start || !currentEvent.value.end) return;

      if (!currentEvent.value.id) {
        currentEvent.value.id = currentEvent.value.start + currentEvent.value.title;
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
      };

      const index = userEvents.value.findIndex(e => e.start === newEvent.start && e.title === newEvent.title);

      index >= 0 ? userEvents.value[index] = newEvent : userEvents.value.push(newEvent);

      sessionStorage.setItem(`calendar-events-${props.constructionSite.id || 'new'}`, JSON.stringify(userEvents.value));
      showModal.value = false;
    }

    function deleteEvent() {
      const idToDelete = currentEvent.value.start + currentEvent.value.title;
      userEvents.value = userEvents.value.filter(e => e.start + e.title !== idToDelete);
      sessionStorage.setItem(`calendar-events-${props.constructionSite.id || 'new'}`, JSON.stringify(userEvents.value));
      showModal.value = false;
    }

    function handleEventDrop(info) {
      const event = info.event;
      const index = userEvents.value.findIndex(e => e.id === event.extendedProps.id);
      if (index >= 0) {
        userEvents.value[index].start = event.startStr;
        userEvents.value[index].end = event.endStr;
        sessionStorage.setItem(`calendar-events-${props.constructionSite.id || 'new'}`, JSON.stringify(userEvents.value));
      }
    }

    return {
      isActive,
      image,
      imageUrl,
      calendarOptions,
      showModal,
      currentEvent,
      userEvents,
      formatDateToLocalString,
      handleDateSelect,
      handleEventClick,
      saveEvent,
      deleteEvent,
      modalFields,
    };
  },
  computed: {
    fields() {
      return [
        { name: 'image', label: 'Immagine', elementType: 'file', default: null, row: 1 },
        { name: 'site_name', label: 'Nome Cantiere', default: this.constructionSite.site_name || '', row: 3 },
        { name: 'client_name', label: 'Nome Cliente', default: this.constructionSite.client_name || '', row: 3 },
        { name: 'start_date', label: 'Data Inizio', type: 'date', default: this.constructionSite.start_date || '', row: 3 },
        { name: 'end_date', label: 'Data Fine', type: 'date', default: this.constructionSite.end_date || '', row: 3 },
        { name: 'status', label: 'Stato', elementType: 'select', default: this.constructionSite.status || '', row: 3, options: [
          { value: 'planned', label: 'Pianificato' },
          { value: 'active', label: 'Attivo' },
          { value: 'completed', label: 'Completato' },
          { value: 'on_hold', label: 'In Sospeso' },
        ]},
        { name: 'location', label: 'Località', default: this.constructionSite.location || '', row: 3 },
        { name: 'user_id', label: 'Geometra Responsabile', type: 'number', default: this.constructionSite.user_id || '', row: 3 },
        { name: 'budget', label: 'Budget', type: 'number', default: this.constructionSite.budget || '', row: 3 },
        { name: 'actual_cost', label: 'Costo Effettivo', type: 'number', default: this.constructionSite.actual_cost || '', row: 3 },
        { name: 'description', label: 'Descrizione', elementType: 'textarea', default: this.constructionSite.description || '', row: 1 },
        { name: 'details', label: 'Dettagli', elementType: 'textarea', default: this.constructionSite.details || '', row: 1 },
        { name: 'permit_number', label: 'Numero Permesso', default: this.constructionSite.permit_number || '', row: 2 },
        { name: 'safety_inspection_date', label: 'Data Ispezione Sicurezza', type: 'date', default: this.constructionSite.safety_inspection_date || '', row: 2 },
      ];
    },
    submitUrl() {
      return this.constructionSite.id
        ? `/construction-sites/${this.constructionSite.id}`
        : '/construction-sites';
    },
    method() {
      return this.constructionSite.id ? 'put' : 'post';
    },
    errors() {
      return usePage().props.errors || {};
    },
  },
};
</script>
