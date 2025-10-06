<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { Link } from "@inertiajs/vue3";
import {
    ChevronUpIcon,
    BookOpenIcon,
    ClockIcon,
    ArrowRightIcon,
    AcademicCapIcon,
} from "@heroicons/vue/24/outline";

// Props
const props = defineProps({
    course: {
        type: Object,
    },
    modules: {
        type: Object,
    },
});

// Normalizar datos
const courseData = computed(() => props.course?.data ?? props.course ?? null);
const moduleData = computed(() => props.modules?.data ?? props.modules ?? []);
</script>

<template>
    <AppLayout
        :title="`Módulos: ${courseData?.name || 'Curso'}`"
        subtitle="Explora el contenido del curso organizado por módulos y lecciones"
        :pages="[
            {
                name: 'Mis cursos',
                href: route('students.courses'),
                current: false,
            },
            {
                name: courseData.name,
                href: route('courses.students.modules', courseData.id),
                current: true,
            },
        ]"
    >
        <div class="py-5">

            <!-- Sin módulos -->
            <div
                v-if="!moduleData || moduleData.length === 0"
                class="py-12 text-center"
            >
                <BookOpenIcon class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <p class="text-lg text-gray-500">
                    Este curso aún no tiene módulos disponibles.
                </p>
            </div>

            <!-- Lista de módulos con Disclosure -->
            <div v-else class="space-y-4">
                <Disclosure
                    v-for="module in moduleData"
                    :key="module.id"
                    v-slot="{ open }"
                >
                    <div
                        class="overflow-hidden transition-all bg-white border border-gray-200 rounded-xl hover:shadow-md"
                    >
                        <DisclosureButton
                            class="flex items-center justify-between w-full p-6 text-left transition-all hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                        >
                            <!-- Información del módulo (Izquierda) -->
                            <div class="flex items-start flex-1 min-w-0 gap-4">
                                <!-- Número del módulo -->
                                <div
                                    class="flex items-center justify-center w-12 h-12 font-bold text-white bg-indigo-600 rounded-full shrink-0"
                                >
                                    {{ module.number_module }}
                                </div>

                                <!-- Detalles del módulo -->
                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="mb-1 text-lg font-bold text-gray-900"
                                    >
                                        {{ module.title }}
                                    </h3>
                                    <p
                                        v-if="module.description"
                                        class="mb-2 text-sm text-gray-600 line-clamp-2"
                                    >
                                        {{ module.description }}
                                    </p>
                                    <div
                                        class="flex items-center gap-4 text-sm text-gray-500"
                                    >
                                        <div class="flex items-center gap-1">
                                            <BookOpenIcon class="w-4 h-4" />
                                            <span>
                                                {{
                                                    module.lessons?.length || 0
                                                }}
                                                {{
                                                    module.lessons?.length === 1
                                                        ? "lección"
                                                        : "lecciones"
                                                }}
                                            </span>
                                        </div>
                                        <div
                                            v-if="module.status"
                                            class="px-2 py-1 text-xs font-medium rounded-full"
                                            :class="
                                                module.status === 1
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-gray-100 text-gray-700'
                                            "
                                        >
                                            {{
                                                module.status === 1
                                                    ? "Activo"
                                                    : "Inactivo"
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón e icono (Derecha) -->
                            <div class="flex items-center gap-3 ml-4 shrink-0">
                                <!-- Botón Ir al módulo -->
                                <Link
                                    :href="route('modules.lessons', module.id)"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    @click.stop
                                >
                                    Ir al módulo
                                    <ArrowRightIcon class="w-4 h-4" />
                                </Link>

                                <!-- Icono de chevron -->
                                <ChevronUpIcon
                                    :class="open ? 'rotate-180 transform' : ''"
                                    class="w-5 h-5 text-gray-400 transition-transform"
                                />
                            </div>
                        </DisclosureButton>

                        <DisclosurePanel class="px-6 pt-2 pb-6 bg-gray-50">
                            <!-- Sin lecciones -->
                            <div
                                v-if="
                                    !module.lessons ||
                                    module.lessons.length === 0
                                "
                                class="py-8 text-center text-gray-500"
                            >
                                <ClockIcon
                                    class="w-12 h-12 mx-auto mb-2 text-gray-400"
                                />
                                <p>No hay lecciones en este módulo.</p>
                            </div>

                            <!-- Lista de lecciones -->
                            <div v-else class="space-y-3">
                                <div
                                    v-for="lesson in module.lessons"
                                    :key="lesson.id"
                                    class="flex items-start gap-3 p-4 transition-colors bg-white border border-gray-200 rounded-lg hover:border-indigo-300"
                                >
                                    <!-- Posición de la lección -->
                                    <div
                                        class="flex items-center justify-center w-8 h-8 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full shrink-0"
                                    >
                                        {{ lesson.position }}
                                    </div>

                                    <!-- Información de la lección -->
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="mb-1 font-semibold text-gray-900"
                                        >
                                            {{ lesson.title }}
                                        </h4>
                                        <p
                                            v-if="lesson.subtitle"
                                            class="mb-2 text-sm text-gray-600"
                                        >
                                            {{ lesson.subtitle }}
                                        </p>
                                        <p
                                            v-if="lesson.content"
                                            class="text-sm text-gray-500 line-clamp-2"
                                        >
                                            {{ lesson.content }}
                                        </p>

                                        <!-- URL del recurso (si existe) -->
                                        <div
                                            v-if="lesson.url_resource"
                                            class="mt-2 text-xs text-indigo-600"
                                        >
                                            <a
                                                :href="lesson.url_resource"
                                                target="_blank"
                                                class="hover:underline"
                                            >
                                                Ver recurso externo →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </DisclosurePanel>
                    </div>
                </Disclosure>
            </div>
        </div>
    </AppLayout>
</template>
