<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronUpIcon, BookOpenIcon } from "@heroicons/vue/24/outline";
import { computed } from "vue";
import PrimaryButton from "./PrimaryButton.vue";

const props = defineProps({
    category: {
        type: Object,
        required: true,
    },
    courses: {
        type: Array,
        default: () => [],
    },
});

// Filtra los cursos de esta categoría
const categoryCourses = computed(() => {
    return props.courses.filter(
        (course) => course.category_id === props.category.id
    );
});

// Cuenta de cursos en esta categoría
const courseCount = computed(() => categoryCourses.value.length);

// Badge de estado de autorización
const getAuthBadge = (status) => {
    const badges = {
        authorized: {
            text: "Autorizado",
            class: "bg-green-100 text-green-800",
        },
        pending_authorization: {
            text: "Pendiente",
            class: "bg-yellow-100 text-yellow-800",
        },
        rejected: { text: "Rechazado", class: "bg-red-100 text-red-800" },
    };
    return badges[status] || badges["pending_authorization"];
};
</script>

<template>
    <div class="w-full">
        <Disclosure v-slot="{ open }" :defaultOpen="false">
            <DisclosureButton
                class="flex items-center justify-between w-full px-5 py-4 text-left transition-all bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md hover:border-indigo-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
            >
                <div class="flex items-center min-w-0 gap-4">
                    <div
                        class="flex items-center justify-center w-12 h-12 rounded-lg bg-indigo-50 shrink-0"
                    >
                        <BookOpenIcon class="w-6 h-6 text-indigo-600" />
                    </div>

                    <!-- Info de categoría -->
                    <div class="min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ category.name }}
                        </h3>
                        <p class="text-sm text-gray-500 truncate">
                            {{ category.description }}
                        </p>
                        <p class="mt-1 text-xs font-medium text-indigo-600">
                            {{ courseCount }}
                            {{ courseCount === 1 ? "curso" : "cursos" }}
                        </p>
                    </div>
                </div>

                <ChevronUpIcon
                    :class="open ? 'rotate-180 transform' : ''"
                    class="w-5 h-5 text-gray-400 transition-transform shrink-0"
                />
            </DisclosureButton>

            <DisclosurePanel class="px-4 pt-4 pb-2">
                <div
                    v-if="categoryCourses.length === 0"
                    class="py-8 text-center text-gray-500"
                >
                    No hay cursos en esta categoría.
                </div>

                <ul v-else class="space-y-3">
                    <li
                        v-for="course in categoryCourses"
                        :key="course.id"
                        class="p-4 transition-all border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 hover:border-indigo-200"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-900">
                                    {{ course.name }}
                                </h4>
                                <p
                                    class="mt-1 text-sm text-gray-600 line-clamp-2"
                                >
                                    {{ course.description }}
                                </p>

                                <!-- Áreas del curso -->
                                <div
                                    v-if="course.areas && course.areas.length"
                                    class="flex flex-wrap gap-1 mt-2"
                                >
                                    <span
                                        v-for="(area, idx) in course.areas"
                                        :key="idx"
                                        class="px-2 py-1 text-xs font-medium text-indigo-700 bg-indigo-100 rounded"
                                    >
                                        {{ area }}
                                    </span>
                                </div>
                            </div>

                            <!-- Badge de estado -->
                            <span
                                :class="[
                                    'px-2 py-1 text-xs font-semibold rounded-full shrink-0',
                                    getAuthBadge(course.authorized).class,
                                ]"
                            >
                                {{ getAuthBadge(course.authorized).text }}
                            </span>
                        </div>

                        <PrimaryButton
                            class="mt-5"
                            tag="a"
                            :href="route('courses.modules', course.id)"
                        >
                            Ir al curso
                        </PrimaryButton>
                    </li>
                </ul>
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>
