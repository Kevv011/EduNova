<script setup>
import { computed } from 'vue';

const props = defineProps({
  maxContent: {
    type: Number,
    required: false,
  },
});

const maxClass = computed(() => {
  return {
    50: 'max-h-[50px]',
    100: 'max-h-[100px]',
    400: 'max-h-[400px]',
    500: 'max-h-[500px]',
    600: 'max-h-[600px]',
    1000: 'max-h-[1000px]',
  }[props.maxContent];
});
</script>
<template>
  <div
    class="divide-y divide-gray-700 overflow-hidden rounded-lg bg-white border border-gray-700 px-4 sm:px-6"
  >
    <div class="py-5">
      <slot name="header" />
    </div>
    <div
      :class="[
        'py-5 sm:py-6',
        maxContent ? maxClass + ' overflow-y-auto' : '',
      ]"
    >
      <slot name="content" />
    </div>
    <div class="py-4">
      <slot name="footer" />
    </div>
  </div>
</template>
