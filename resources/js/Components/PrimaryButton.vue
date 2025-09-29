<script setup>
import Loading from "@/Oak/Loading.vue";
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
const props = defineProps({
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
    disabled: {
        type: Boolean,
        default: false,
    },
    internalAs: {
        type: String,
        default: "a",
    },
    loading: {
        type: Boolean,
        default: false,
    },
    color: {
        type: String,
        default: "blue",
    },
});

const colorClasses = {
    blue: "bg-indigo-600 hover:bg-indigo-800 focus:bg-gray-700 active:bg-gray-900 focus:ring-indigo-500",
    orange: "bg-orange-500 hover:bg-orange-600 focus:bg-orange-700 active:bg-orange-800 focus:ring-orange-400",
};

const classBtn = computed(() => {
    return [
        "inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150",
        colorClasses[props.color] || colorClasses.blue,
    ].join(" ");
});
</script>

<template>
    <Link :href="href" v-if="tag === 'link'" :as="internalAs" :class="classBtn">
        <Loading
            class="w-4 h-4 mr-3"
            v-if="loading && internalAs === 'button'"
        />
        <slot />
    </Link>
    <a :href="href" v-else-if="tag === 'a'" :class="classBtn">
        <slot />
    </a>
    <button
        v-else
        :type="type"
        :class="[classBtn, disabled ? 'cursor-not-allowed opacity-35' : '']"
        :disabled="disabled || loading"
    >
        <Loading class="w-4 h-4 mr-3" v-if="loading" />
        <slot />
    </button>
</template>
