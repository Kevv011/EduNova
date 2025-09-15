<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
defineProps({
    type: {
        type: String,
        default: "submit",
    },
    tag: {
        type: String,
        default: "button",
    },
    href: {
        type: String,
        required: false,
    },
});

const classBtn = computed(() => {
    return "inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150";
});
</script>

<template>
    <Link :href="href" v-if="tag === 'link'" :class="classBtn">
        <slot />
    </Link>
    <a :href="href" v-else-if="tag === 'a'" :class="classBtn">
        <slot />
    </a>
    <button v-else :type="type" :class="classBtn">
        <slot />
    </button>
</template>
