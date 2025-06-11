<script setup>
import ConstructionCard from '../Components/ConstructionCard.vue';
// import AppLayout from '@/Layouts/AppLayout.vue';

const eventsRoma = [
  { start: '2025-05-21', end: '2025-06-01', title: 'Inizio lavori', content: 'Scavi e fondazioni' },
  { start: '2025-06-10', end: '2025-06-15', title: 'Gettata', content: 'Cemento armato' }
]
const eventsPiazza = [
  { start: '2025-06-01', end: '2025-06-03', title: 'Pavimentazione', content: 'Pietra naturale' }
]
const eventsTigli = [
  { start: '2025-07-15', end: '2025-07-25', title: 'Allargamento strada', content: 'Nuovo asfalto' }
]
const eventsScuola = [
  { start: '2025-08-10', end: '2025-08-20', title: 'Adeguamento sismico', content: 'Interventi strutturali' }
]
const eventsRose = [
  { start: '2025-05-05', end: '2025-05-15', title: 'Reti fognarie', content: 'Demolizione vecchi impianti' }
]
const eventsParco = [
  { start: '2025-06-20', end: '2025-06-30', title: 'Area giochi', content: 'Installazione strutture' }
]

const cantieri = [
  {
    name: 'Cantiere Via Roma',
    description: 'Ristrutturazione completa dell’edificio storico in centro.',
    description2: 'Geometra incaricato: Mario Rossi',
    dateInEnd: 'Inizio Lavori: 2025-05-21 - Fine Lavori: 2025-12-21',
    resources: ' Squadre impegnate: squadra 1, squadra 2, squadra 3',
    initialDate: '2025-04-21',
    image: '/images/Cantiere.jpeg',
    events: eventsRoma,
  },
  {
    name: 'Cantiere Piazza Libertà',
    description: 'Riqualificazione urbana con nuova pavimentazione e area pedonale.',
    description2: 'Geometra incaricato: Luca Bianchi',
    dateInEnd: 'Inizio Lavori: 2025-06-01 - Fine Lavori: 2025-11-30',
    resources: 'Squadre impegnate: squadra A, squadra B',
    initialDate: '2025-04-22',
    image: '/images/piazza-liberta.jpg',
    events: eventsPiazza,
  },
  {
    name: 'Cantiere Viale dei Tigli',
    description: 'Ampliamento della carreggiata e rifacimento del manto stradale.',
    description2: 'Geometra incaricato: Sara Verdi',
    dateInEnd: 'Inizio Lavori: 2025-07-15 - Fine Lavori: 2025-12-01',
    resources: 'Squadre impegnate: squadra 2, squadra 4',
    initialDate: '2025-04-22',
    image: '/images/viale-dei-tigli.jpg',
    events: eventsTigli,
  },
  {
    name: 'Cantiere Scuola Dante Alighieri',
    description: 'Intervento di adeguamento sismico e miglioramento energetico.',
    description2: 'Geometra incaricato: Giovanni Neri',
    dateInEnd: 'Inizio Lavori: 2025-08-10 - Fine Lavori: 2026-02-15',
    resources: 'Squadre impegnate: squadra 1, squadra 5',
    initialDate: '2025-04-22',
    image: '/images/scuola-dante.webp',
    events: eventsScuola,
  },
  {
    name: 'Cantiere Via delle Rose',
    description: 'Sostituzione completa della rete fognaria e dell’impianto idrico.',
    description2: 'Geometra incaricato: Elisa Marchetti',
    dateInEnd: 'Inizio Lavori: 2025-05-05 - Fine Lavori: 2025-09-30',
    resources: 'Squadre impegnate: squadra 3, squadra 6',
    initialDate: '2025-04-22',
    image: '/images/via-delle-rose.png',
    events: eventsRose,
  },
  {
    name: 'Cantiere Parco San Michele',
    description: 'Creazione di nuovi spazi verdi, area giochi e percorso fitness.',
    description2: 'Geometra incaricato: Fabio De Santis',
    dateInEnd: 'Inizio Lavori: 2025-06-20 - Fine Lavori: 2025-10-10',
    resources: 'Squadre impegnate: squadra verde, squadra natura',
    initialDate: '2025-04-22',
    image: '/images/parco-san-michele.webp',
    events: eventsParco,
  },
]
</script>

<template>
    <div class="p-4 rounded-lg dark:border-gray-700">
        <ConstructionCard
          v-for="(cantiere, index) in cantieri"
          :key="index"
          :id="index"
          v-bind="cantiere"
          calendarHeight="h-60"
          calendarWidth="w-[100rem]"
          :enable-views="false"
        />
    </div>
</template>
