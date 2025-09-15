<script setup>
import { ArchiveBoxXMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  image: {
    type: String,
    required: false,
  },
});
</script>
<template>
  <div class="text-center">
    <img :src="image" class="h-32 w-32 mx-auto" v-if="image" />
    <ArchiveBoxXMarkIcon v-else class="w-12 h-12 mx-auto text-gray-600" />
    <h3 class="mt-2 text-sm font-semibold text-gray-900">
      <slot name="title" />
    </h3>
    <p class="mt-1 text-sm text-gray-500">
      <slot name="description" />
    </p>
    <div class="mt-6">
      <slot name="actions" />
    </div>
  </div>
</template>
