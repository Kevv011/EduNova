<script setup>
import { ChevronDownIcon } from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
  header: {
    type: Object,
    required: false,
  },
  isFirst: {
    type: Boolean,
    default: false,
  },
  isLast: {
    type: Boolean,
    default: false,
  },
  sorter: {
    type: String,
    required: false,
  },
});

const emits = defineEmits(['changeOrder']);

const sort = () => {
  const direction = props.sorter.includes('-')
    ? props.header.key
    : '-' + props.header.key;

  emits('changeOrder', direction);
};
</script>
<template>
  <th
    scope="col"
    class="relative py-3.5 text-sm text-gray-900"
    :class="{
      'text-left': !header.align || header.align === 'left',
      'text-right': header.align === 'right',
      'text-center': header.align === 'center',
      'pl-4 sm:pl-6 pr-3': isFirst,
      'pr-4 sm:pr-6 pl-3': isLast,
      'px-3': !isFirst && !isLast,
    }"
  >
    <button
      v-if="header.sortable"
      type="button"
      class="inline-flex group focus:outline-none"
      @click="sort"
    >
      <slot :header="header" />
      <span
        class="ml-2 flex-none rounded bg-gray-200 text-gray-900 group-hover:bg-gray-200"
        v-if="sorter && sorter.includes(header.key)"
      >
        <ChevronDownIcon
          :class="[
            'size-5',
            sorter && sorter.includes('-') ? 'rotate-0' : 'rotate-180',
          ]"
          aria-hidden="true"
        />
      </span>
      <span
        v-else
        class="invisible ml-2 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
      >
        <ChevronDownIcon class="size-5" aria-hidden="true" />
      </span>
    </button>
    <span v-else>
      <slot :header="header" />
    </span>
  </th>
</template>
