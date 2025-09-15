<script setup>
import { ChevronDownIcon } from '@heroicons/vue/16/solid';
import { Link } from '@inertiajs/vue3';
const props = defineProps({
  tabs: {
    type: Array,
    default: () => [],
  },
  buttons: {
    type: Boolean,
    default: false,
  },
});
</script>
<template>
  <div>
    <div class="grid grid-cols-1 sm:hidden">
      <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
      <select
        aria-label="Select a tab"
        class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
      >
        <option v-for="tab in tabs" :key="tab.name" :selected="tab.current">
          {{ tab.name }}
        </option>
      </select>
      <ChevronDownIcon
        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500"
        aria-hidden="true"
      />
    </div>
    <div class="hidden sm:block">
      <nav class="flex space-x-4" aria-label="Tabs">
        <template v-if="buttons">
          <button
            type="button"
            v-for="tab in tabs"
            :key="tab.name"
            @click="$emit('select-tab', tab)"
            :class="[
              tab.current
                ? 'bg-gray-300 text-gray-800'
                : 'text-gray-600 hover:text-gray-800',
              'rounded-md px-3 py-2 text-sm font-medium cursor-pointer',
            ]"
            :aria-current="tab.current ? 'page' : undefined"
          >
            {{ tab.name }}
          </button>
        </template>
        <template v-else>
          <Link
            v-for="tab in tabs"
            :key="tab.name"
            :href="tab.href"
            :class="[
              tab.current
                ? 'bg-gray-300 text-gray-800'
                : 'text-gray-600 hover:text-gray-800',
              'rounded-md px-3 py-2 text-sm font-medium cursor-pointer',
            ]"
            :aria-current="tab.current ? 'page' : undefined"
            >{{ tab.name }}</Link
          >
        </template>
      </nav>
    </div>
  </div>
</template>
