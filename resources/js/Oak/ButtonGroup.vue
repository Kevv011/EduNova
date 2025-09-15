<script setup>
import { MinusIcon, PlusIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  adderDisabled: {
    type: Boolean,
    default: false,
  },
  subtracterDisabled: {
    type: Boolean,
    default: false,
  },
});
</script>
<template>
  <div class="flex items-stretch">
    <button
      type="button"
      :disabled="subtracterDisabled"
      @click="$emit('decrement')"
      :class="{ 'opacity-50 cursor-not-allowed': subtracterDisabled }"
      class="px-3 py-2 bg-white border rounded-tl-lg rounded-bl-lg text-2xl font-bold"
    >
      <MinusIcon class="w-4 h-4" />
    </button>
    <slot></slot>
    <button
      type="button"
      :disabled="adderDisabled"
      @click="$emit('increment')"
      :class="{ 'opacity-50 cursor-not-allowed': adderDisabled }"
      class="px-3 py-2 bg-white border rounded-tr-lg rounded-br-lg text-2xl font-bold"
    >
      <PlusIcon class="w-4 h-4" />
    </button>
  </div>
</template>
