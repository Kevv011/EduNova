<script setup>
import {
  CheckCircleIcon,
  ExclamationCircleIcon,
  ExclamationTriangleIcon,
} from '@heroicons/vue/20/solid';
import { computed } from 'vue';

const props = defineProps({
  type: {
    type: String,
    default: 'warning',
  },
  title: {
    type: String,
    required: true,
  },
});

const icon = computed(() => {
  return {
    warning: ExclamationTriangleIcon,
    success: CheckCircleIcon,
    error: ExclamationCircleIcon,
  }[props.type];
});
</script>
<template>
  <div class="rounded-md bg-yellow-50 p-4">
    <div class="flex">
      <div class="shrink-0">
        <component
          :is="icon"
          :class="[
            'size-5',
            {
              'text-yellow-500': type === 'warning',
              'text-green-500': type === 'success',
              'text-red-500': type === 'error',
            },
          ]"
          aria-hidden="true"
        />
      </div>
      <div class="ml-3">
        <h3
          :class="[
            'text-sm font-medium',
            {
              'text-yellow-800': type === 'warning',
              'text-green-800': type === 'success',
              'text-red-800': type === 'error',
            },
          ]"
        >
          {{ title }}
        </h3>
        <div
          :class="[
            'mt-2 text-sm',
            {
              'text-yellow-700': type === 'warning',
              'text-green-700': type === 'success',
              'text-red-600': type === 'error',
            },
          ]"
        >
          <p>
            <slot />
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
