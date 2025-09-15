<script setup>
import { computed } from 'vue';

const props = defineProps({
  icon: {
    type: Function,
    required: false,
  },
  type: {
    type: String,
    default: 'orange',
  },
});

const emit = defineEmits(['click']);

const classBtn = computed(() => {
  return {
    orange:
      'bg-orange-500 text-white hover:bg-orange-600 focus:bg-orange-600 focus:ring-orange-500',
    outline:
      'bg-white border text-gray-700 hover:bg-gray-50 focus:ring-blue-500 border-gray-300',
  }[props.type];
});
</script>

<template>
  <button
    type="button"
    @click="$emit('click')"
    :class="[
      classBtn,
      'inline-flex text-sm py-1.5 px-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50',
    ]"
  >
    <component :is="icon" class="w-5 h-5" v-if="icon" />
    <slot v-else />
  </button>
</template>
