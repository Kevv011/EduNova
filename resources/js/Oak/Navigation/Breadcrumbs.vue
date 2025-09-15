<script setup>
import { HomeIcon } from '@heroicons/vue/20/solid';
import { ref } from 'vue';

const props = defineProps({
  pages: {
    type: Array,
    required: false,
  },
  disabledBreadcrumb: {
    type: Boolean,
    value: false,
  },
});


</script>
<template>
  <nav class="flex" aria-label="Breadcrumb">
    <ol role="list" class="flex items-center space-x-4">
      <li>
        <div v-if="!props.disabledBreadcrumb">
          <a :href="route('home')" class="flex items-center gap-1 text-gray-500 hover:text-gray-500">
            <HomeIcon class="size-5 shrink-0" aria-hidden="true" />
            <span class="text-sm font-medium">Inicio</span>
          </a>
        </div>
      </li>
      <li v-for="page in pages" :key="page.name">
        <div class="flex items-center">
          <svg
            class="text-gray-300 size-5 shrink-0"
            fill="currentColor"
            viewBox="0 0 20 20"
            aria-hidden="true"
          >
            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
          </svg>
          <a
            :href="page.href"
            class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
            :aria-current="page.current ? 'page' : undefined"
            >{{ page.name }}</a
          >
        </div>
      </li>
    </ol>
  </nav>
</template>
