<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  icon: {
    type: Function,
    required: false,
  },
});
</script>
<template>
  <div
    class="relative overflow-hidden rounded-lg bg-white px-4 py-2 shadow-sm sm:px-6 sm:py-6"
  >
    <dt>
      <slot name="icon">
        <div class="absolute rounded-md bg-orange-600 p-3">
          <component :is="icon" class="size-6 text-white" aria-hidden="true" />
        </div>
      </slot>
      <p class="ml-16 truncate text-sm font-medium text-gray-500">
        {{ title }}
      </p>
    </dt>
    <dd class="ml-16 flex items-baseline">
      <p class="text-2xl font-semibold text-gray-900">
        <slot />
      </p>
    </dd>
  </div>
</template>
