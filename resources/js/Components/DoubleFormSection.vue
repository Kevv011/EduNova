<script setup>
import { computed, useSlots } from "vue";

defineProps({
    leftTitle: {
        type: String,
        default: "",
    },
    rightTitle: {
        type: String,
        default: "",
    },
    leftSubtitle: {
        type: String,
        default: "",
    },
    rightSubtitle: {
        type: String,
        default: "",
    },
});

defineEmits(["submitted"]);

const slots = useSlots();
const hasActions = computed(() => !!slots.actions);
const hasLeftActions = computed(() => !!slots["left-actions"]);
</script>

<template>
    <div class="mt-10 sm:mt-0">
        <form @submit.prevent="$emit('submitted')">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[500px]">
                    <!-- Sección izquierda -->
                    <div
                        class="relative px-8 py-10 text-white bg-gradient-to-br from-slate-800 to-slate-900"
                    >
                        <!-- Patrón decorativo -->
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-indigo-600/20"
                        ></div>

                        <div class="relative z-10">
                            <!-- Título sección izquierda -->
                            <div v-if="leftTitle" class="mb-8">
                                <h3 class="text-xl font-semibold text-white">
                                    {{ leftTitle }}
                                </h3>
                                <p
                                    v-if="leftSubtitle"
                                    class="mt-1 text-sm text-slate-300"
                                >
                                    {{ leftSubtitle }}
                                </p>
                            </div>

                            <!-- Contenido izquierdo -->
                            <div class="space-y-6">
                                <slot name="left-form" />
                            </div>
                        </div>
                    </div>

                    <!-- Sección derecha -->
                    <div class="px-8 py-10 bg-white">
                        <!-- Título sección derecha -->
                        <div v-if="rightTitle" class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900">
                                {{ rightTitle }}
                            </h3>
                            <p
                                v-if="rightSubtitle"
                                class="mt-1 text-sm text-gray-600"
                            >
                                {{ rightSubtitle }}
                            </p>
                        </div>

                        <!-- Contenido derecho -->
                        <div class="space-y-6">
                            <slot name="right-form" />
                        </div>
                    </div>
                </div>

                <!-- Barra de acciones -->
                <div
                    v-if="hasActions || hasLeftActions"
                    class="flex items-center justify-between px-8 py-6 border-t border-gray-200 bg-gray-50"
                >
                    <!-- Acciones izquierda -->
                    <div>
                        <slot name="left-actions" />
                    </div>

                    <!-- Acciones derecha -->
                    <div class="flex space-x-3">
                        <slot name="actions" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
