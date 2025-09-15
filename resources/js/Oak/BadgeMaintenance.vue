<script setup>
import { computed } from 'vue';

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
});

const colorClass = computed(() => {
  return (
    {
      preventivo: 'bg-yellow-100 text-yellow-800 ring-1 ring-inset ring-yellow-300',
      correctivo: 'bg-red-100 text-red-800 ring-1 ring-inset ring-red-300',
    }[props.type] ?? 'bg-gray-100 text-gray-700 ring-1 ring-inset ring-gray-300'
  );
});

const label = computed(() => {
  return (
    {
      preventivo: 'Preventivo',
      correctivo: 'Correctivo',
    }[props.type] ?? props.type
  );
});
</script>

<template>
  <span
    :class="[
      'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium',
      colorClass,
    ]"
  >
    {{ label }}
  </span>
</template>
