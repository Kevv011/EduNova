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
    not_authorized: 'bg-red-50 text-red-600 ring-1 ring-inset ring-red-300',
    activo: 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-300',
    authorized: 'bg-green-50 text-green-600 ring-1 ring-inset ring-green-300',
  }[props.status];
});

const statusText = computed(() => {
  return {
    not_authorized: 'No autorizado',
    activo: 'Activo',
    authorized: 'Autorizado',
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
