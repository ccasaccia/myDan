import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Layout from './Layouts/Layout.vue';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

// Importa gli stili di Vuetify prima per evitare conflitti con PrimeVue
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';


// Importa solo le icone di FontAwesome che ti servono
import { library } from '@fortawesome/fontawesome-svg-core';
import { faGithub } from '@fortawesome/free-brands-svg-icons';
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import { faHouse } from '@fortawesome/free-solid-svg-icons';
import { faUserGroup } from '@fortawesome/free-solid-svg-icons';
import { faFilter } from '@fortawesome/free-solid-svg-icons';
import { faWarehouse } from '@fortawesome/free-solid-svg-icons';
import { faTrashCan } from '@fortawesome/free-solid-svg-icons';
import { faFloppyDisk } from '@fortawesome/free-solid-svg-icons';
import { faRotateLeft } from '@fortawesome/free-solid-svg-icons';
import { faUserTie } from '@fortawesome/free-solid-svg-icons';
import { faPersonDigging } from '@fortawesome/free-solid-svg-icons';
import { faArrowRightFromBracket } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Aggiungi solo le icone richieste a FontAwesome
library.add(
    faGithub, faPlus, faHouse, faUserGroup, faFilter, faWarehouse,
    faTrashCan, faFloppyDisk, faRotateLeft, faUserTie, faPersonDigging, faArrowRightFromBracket
);

// Importa anche il CSS di PrimeIcons
import 'primeicons/primeicons.css';

// Importa un componente di PrimeVue come esempio
import Button from 'primevue/button';

const vuetify = createVuetify({
  components,
  directives,
})

createInertiaApp({
    title: (title) => `Dan Europe ${title}`,
    resolve: async (name) => {
        // Caricamento lazy delle pagine (opzionale, sostituisce eager: true)
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: false });
        const page = (await pages[`./Pages/${name}.vue`]()).default;
        // Escludi il layout per le pagine di autenticazione
        if (name.startsWith('Auth/')) {
            page.layout = null;
        } else {
            page.layout = page.layout || Layout; // Imposta il layout predefinito
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Metodo globale per formattare una data
        app.config.globalProperties.$getDate = function(date) {
            return new Date(date).toLocaleDateString('en-us', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            });
        };

        // Usa i plugin di Inertia, Vuetify, Ziggy e PrimeVue
        app.use(plugin);  // Plugin Inertia
        app.use(ZiggyVue); // Plugin Ziggy per il routing
        app.use(vuetify);  // Vuetify plugin
        app.use(PrimeVue, {
            theme: {
                preset: Aura,  // Imposta il tema di PrimeVue
            },
            zIndex: {
                modal: 1100,        // dialog, drawer
                overlay: 1000,      // select, popover
                menu: 1000,         // overlay menus
                tooltip: 1100       // tooltip
            },
            csp: {
                nonce: '...'       // Nonce per la CSP (se necessario)
            }
        });

        // Registra globalmente il componente FontAwesomeIcon
        app.component('font-awesome-icon', FontAwesomeIcon);

        // Registra i componenti globali di Inertia
        app.component('Head', Head);  // Componente globale <Head>
        app.component('Link', Link);  // Componente globale <Link>

        // Registra il componente Button di PrimeVue globalmente
        app.component('Button', Button);

        // Monta l'app Vue
        app.mount(el);
    },
    progress: {
        color: 'red',
        includeCSS: true,
        showSpinner: true,
    },
});
