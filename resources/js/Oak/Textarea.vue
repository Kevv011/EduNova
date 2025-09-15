<script setup>
defineProps({
  modelValue: [String, Number],
});

defineEmits(['update:modelValue']);
</script>
<template>
  <textarea
    class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  >
  </textarea>
</template>
