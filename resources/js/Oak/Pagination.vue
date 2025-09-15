<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';
import { Link } from '@inertiajs/vue3';
defineProps({
  links: {
    type: Object,
    required: true,
  },
  meta: {
    type: Object,
    default: true,
  },
  idPagination: {
    type: String,
    default: 'pagination-1',
  },
});
</script>
<template>
  <div class="flex items-center justify-between">
    <div class="flex flex-1 justify-between sm:hidden">
      <a
        :href="links.prev ? links.prev : ''"
        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
        >Anterior</a
      >
      <a
        :href="links.next ? links.next : ''"
        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
        >Siguiente</a
      >
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Mostrando
          {{ ' ' }}
          <span class="font-medium">{{ meta.from }}</span>
          {{ ' ' }}
          a
          {{ ' ' }}
          <span class="font-medium">{{ meta.to }}</span>
          {{ ' ' }}
          de
          {{ ' ' }}
          <span class="font-medium">{{ meta.total }}</span>
          {{ ' ' }}
          resultados
        </p>
      </div>
      <div>
        <nav
          class="isolate inline-flex -space-x-px rounded-md shadow-sm"
          aria-label="Pagination"
        >
          <template v-for="(link, indexLink) in meta.links">
            <Link
              v-if="indexLink === 0"
              :as="link.url === null ? 'button' : 'a'"
              :href="link.url === null ? '' : link.url"
              :disabled="!link.url"
              class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:cursor-not-allowed"
            >
              <span class="sr-only">Previous</span>
              <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
            </Link>
            <Link
              v-else-if="indexLink === meta.links.length - 1"
              :as="link.url === null ? 'button' : 'a'"
              :href="link.url === null ? '' : link.url"
              :disabled="!link.url"
              class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:cursor-not-allowed"
            >
              <span class="sr-only">Next</span>
              <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
            </Link>
            <Link
              v-else
              :as="link.active ? 'button' : 'a'"
              :href="link.url === null ? '' : link.url"
              :disabled="link.active"
              class="relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20"
              :class="[
                link.active
                  ? 'z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600'
                  : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0',
              ]"
              >{{ link.label }}</Link
            >
          </template>
        </nav>
      </div>
    </div>
  </div>
</template>
