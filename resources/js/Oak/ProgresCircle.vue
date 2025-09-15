<script setup>
const props = defineProps({
    max: {
        type: Number,
        required: true,
    },
    value: {
        type: Number,
        required: true,
    },
});
</script>
<template>
    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
        <circle
            class="text-gray-300"
            stroke="currentColor"
            stroke-width="5"
            fill="transparent"
            r="12"
            cx="18"
            cy="18"
        />
        <circle
            class="text-orange-500 transition-all duration-300 ease-out"
            stroke="currentColor"
            stroke-width="5"
            stroke-dasharray="100, 100"
            :stroke-dashoffset="100 - (value / max) * 100"
            stroke-linecap="round"
            fill="transparent"
            r="12"
            cx="18"
            cy="18"
        />
    </svg>
</template>
