<script setup>
const props = defineProps({
  column: {
    type: Object,
    required: false,
  },
  isFirst: {
    type: Boolean,
    default: false
  },
  isLast: {
    type: Boolean,
    default: false
  }
});
</script>
<template>
  <td
    scope="col"
    class="whitespace-nowrap py-4 text-sm"
    :class="[column.bold ? 'text-gray-900 font-medium' : 'text-gray-500', {
        'text-left': !column.align || column.align === 'left',
        'text-right': column.align === 'right',
        'text-center': column.align === 'center',
        'pl-4 sm:pl-6 pr-3': isFirst,
        'pr-4 sm:pr-6 pl-3': isLast,
        'px-3': !isFirst && !isLast
    }]"
  >
    <slot :column="column" />
  </td>
</template>
