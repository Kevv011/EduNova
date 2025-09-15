<script setup lang="ts">
import { EnvelopeIcon } from '@heroicons/vue/24/outline';
defineProps({
  icon: {
    type: Function,
    default: EnvelopeIcon,
  },
});
</script>

<template>
  <div class="relative rounded-md">
    <div
      class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
    >
      <component :is="icon" class="w-5 h-5 text-gray-400" aria-hidden="true" style="position: relative; top: 10px;" />
    </div>
    <slot />
  </div>
</template>
