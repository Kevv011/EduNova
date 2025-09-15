<!-- components/InterestsRegister.vue -->
<script setup>
import { computed } from "vue";

const props = defineProps({
    icon: { type: [Object, Function], required: true },
    title: { type: String, required: true },
    description: { type: String, required: true },
    variant: { type: String, default: "neutral" },
    size: { type: String, default: "sm" },
    as: { type: String, default: "div" },
    selected: { type: Boolean, default: false },
    selectable: { type: Boolean, default: false },
});

const emit = defineEmits(["click", "toggle"]);

const pad = computed(() => (props.size === "md" ? "p-4 gap-3" : "p-3 gap-2"));

const iconBox = computed(() => {
    const base = "flex items-center justify-center rounded-xl";
    const s = props.size === "md" ? "w-9 h-9" : "w-8 h-8";

    // Si está seleccionado, usar colores de selección
    if (props.selectable && props.selected) {
        return `${base} ${s} bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300`;
    }

    // Colores normales según variant
    const palette =
        {
            neutral: "bg-gray-100 text-gray-900",
            primary: "bg-indigo-100 text-indigo-600",
            success: "bg-emerald-100 text-emerald-600",
            warning: "bg-amber-100 text-amber-600",
            danger: "bg-rose-100 text-rose-600",
        }[props.variant] ?? "";

    return `${base} ${s} ${palette}`;
});

const titleCls = computed(() =>
    props.size === "md" ? "text-sm font-semibold" : "text-[13px] font-semibold"
);
const descCls = computed(() =>
    props.size === "md"
        ? "text-sm text-gray-500"
        : "text-xs text-gray-600 dark:text-gray-300"
);

// Clases del contenedor principal
const containerClasses = computed(() => {
    const base =
        "flex items-start border rounded-lg transition-all duration-200 p-5";

    if (props.selectable) {
        const cursor = "cursor-pointer";
        const hover = "hover:border-indigo-300 hover:bg-indigo-50";

        if (props.selected) {
            return `${base} ${cursor} border-indigo-500 bg-indigo-50 ${pad}`;
        }

        return `${base} ${cursor} border-gray-200 ${hover} ${pad}`;
    }

    return `${base} border-gray-200 ${pad}`;
});

// Manejar click
const handleClick = () => {
    if (props.selectable) {
        emit("click");
        emit("toggle");
    }
};
</script>

<template>
    <component
        :is="as"
        :class="containerClasses"
        @click="handleClick"
        :role="selectable ? 'checkbox' : undefined"
        :aria-checked="selectable ? selected : undefined"
        :tabindex="selectable ? 0 : undefined"
        @keydown.enter="handleClick"
        @keydown.space.prevent="handleClick"
    >
        <span :class="iconBox" class="me-5">
            <component :is="icon" class="w-8 h-8" aria-hidden="true" />
        </span>

        <div class="flex-1 min-w-0">
            <h3 :class="titleCls" class="truncate">{{ title }}</h3>
            <p :class="descCls" class="mt-0.5 line-clamp-2">
                {{ description }}
            </p>
        </div>

        <!-- Indicador de selección (checkbox visual) -->
        <div v-if="selectable" class="flex items-center ml-3">
            <div
                class="flex items-center justify-center w-5 h-5 transition-all duration-200 border-2 rounded"
                :class="
                    selected
                        ? 'bg-indigo-500 border-indigo-500'
                        : 'border-gray-300 hover:border-indigo-400'
                "
            >
                <!-- Check icon -->
                <svg
                    v-if="selected"
                    class="w-3 h-3 text-white"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                    />
                </svg>
            </div>
        </div>
    </component>
</template>
