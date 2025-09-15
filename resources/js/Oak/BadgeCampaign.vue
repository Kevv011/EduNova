<script setup>
import { computed } from 'vue';

const props = defineProps({
  status: {
    type: String,
    required: true,
  },
});

const colorStatus = computed(() => {
  return {
    draft: 'bg-gray-100 text-gray-600',
    pending_authorization: 'bg-gray-100 text-gray-600',
    pending_arts: 'bg-indigo-100 text-indigo-600',
    pending_payment: 'bg-indigo-100 text-indigo-600',
    publishing: 'bg-blue-100 text-blue-600',
    published: 'bg-green-100 text-green-600',
    cancelled: 'bg-red-100 text-red-500',
    paused: 'bg-orange-100 text-orange-500',
    finished: 'bg-indigo-100 text-indigo-600',
    authorized: 'bg-green-100 text-green-600',
    reserved: 'bg-yellow-100 text-yellow-700'
  }[props.status];
});

const statusText = computed(() => {
  return {
    draft: 'Borrador',
    pending_authorization: 'Pendiente de autorización',
    pending_arts: 'Pendiente de artes',
    pending_payment: 'Pendiente de pago',
    publishing: 'Publicando',
    published: 'Publicado',
    cancelled: 'Cancelado',
    paused: 'Pausado',
    finished: 'Finalizado',
    authorized: 'Autorizado',
    reserved: 'Reservado'
  }[props.status];
});
</script>
<template>
  <span
    :class="[
      'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium',
      colorStatus,
    ]"
    >{{ statusText }}</span
  >
</template>
