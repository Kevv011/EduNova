<script setup>
import { computed, useSlots } from 'vue';
import { ArchiveBoxXMarkIcon } from '@heroicons/vue/24/outline';
defineProps({
  title: {
    type: String,
    required: false,
  },
  description: {
    type: String,
    required: false,
  },
});

const hasActions = computed(() => !!useSlots().actions);
</script>
<template>
  <div class="text-center py-10">
    <slot name="image">
      <ArchiveBoxXMarkIcon class="w-12 h-12 mx-auto text-gray-600" />
    </slot>
    <h3
      class="text-sm font-semibold text-gray-900 mt-6"
      v-if="title"
    >
      {{ title }}
    </h3>
    <p class="mt-1 text-sm text-gray-500" v-if="description">
      {{ description }}
    </p>
    <div class="mt-6" v-if="hasActions">
      <slot name="actions" />
    </div>
  </div>
</template>
