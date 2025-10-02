<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import moment from "moment";
import OakTabs from "@/Oak/Navigation/Tabs.vue";
import {
    CheckCircleIcon,
    XCircleIcon,
    ExclamationCircleIcon,
    PencilIcon,
    ArrowLeftIcon,
} from "@heroicons/vue/24/outline";

// Props
const props = defineProps({
    course: { type: [Object, null], required: true },
});

// Normalizar datos
const courseData = computed(() => props.course?.data ?? props.course ?? null);

// Campos derivados
const categoryName = computed(() => courseData.value?.category?.name ?? "—");
const areas = computed(() => {
    const a = courseData.value?.areas;
    if (!a) return [];
    return Array.isArray(a)
        ? a
        : String(a)
            .split(",")
            .map((s) => s.trim())
            .filter(Boolean);
});
const imageUrl = computed(
    () => courseData.value?.image ?? "/images/user-default.jpeg"
);

const createdAt = computed(() =>
    courseData.value?.created_at
        ? moment(courseData.value.created_at).format("DD MMM YYYY, HH:mm")
        : "—"
);
const updatedAt = computed(() =>
    courseData.value?.updated_at
        ? moment(courseData.value.updated_at).format("DD MMM YYYY, HH:mm")
        : "—"
);

// Badges
const available = computed(() => !!courseData.value?.available_status);
const availabilityText = computed(() =>
    available.value ? "Habilitado" : "No habilitado"
);
const availabilityClass = computed(() =>
    available.value ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700"
);

const authorized = computed(() => courseData.value?.authorized ?? "pending");
const authorizedIcon = computed(() =>
    authorized.value === "authorized"
        ? CheckCircleIcon
        : authorized.value === "rejected"
            ? XCircleIcon
            : ExclamationCircleIcon
);
const authorizedColor = computed(() =>
    authorized.value === "authorized"
        ? "text-green-600 bg-green-100"
        : authorized.value === "rejected"
            ? "text-red-600 bg-red-100"
            : "text-yellow-600 bg-yellow-100"
);

// Rutas
const courseId = computed(() => courseData.value?.id);

// Tabs de navegacion
const tabs = ref([
    {
        name: "Modulos",
        href: route("courses.modules", courseData.value.id),
        current: true,
    },
    {
        name: "Estudiantes",
        href: route("courses.students", courseData.value.id),
        current: false,
    },
]);
</script>

<template>
    <AppLayout :title="courseData?.name ?? 'Curso'" subtitle="Detalle del curso" :pages="[
        {
            name: 'Mis cursos',
            href: route('courses.index'),
            current: false,
        },
        {
            name: courseData?.name ?? 'Curso',
            href: route('courses.show', courseId),
            current: true,
        },
    ]">
        <div class="flex items-center justify-between mb-6">
            <div></div>

            <div class="flex items-center gap-2">
                <Link v-if="courseId" :href="route('courses.edit', courseId)"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-blue-700 rounded-lg bg-blue-50 hover:bg-blue-100">
                <PencilIcon class="w-4 h-4" /> Editar
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Columna imagen -->
            <div class="md:col-span-1">
                <div class="overflow-hidden bg-white border rounded-2xl">
                    <img :src="imageUrl" alt="Imagen del curso" class="object-cover w-full aspect-video" />
                </div>
            </div>

            <!-- Columna info -->
            <div class="md:col-span-2">
                <div class="p-5 bg-white border rounded-2xl">
                    <h1 class="mb-2 text-2xl font-semibold">
                        {{ courseData?.name ?? "—" }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-700">
                            Categoría: {{ categoryName }}
                        </span>

                        <span :class="[
                            'px-2.5 py-1 text-xs font-medium rounded-lg',
                            availabilityClass,
                        ]">
                            {{ availabilityText }}
                        </span>

                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg"
                            :class="authorizedColor">
                            <component :is="authorizedIcon" class="w-4 h-4" />
                            {{
                                authorized === "authorized"
                                    ? "Autorizado"
                                    : authorized === "rejected"
                                        ? "Rechazado"
                                        : "Pendiente"
                            }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <h2 class="mb-1 text-sm font-semibold text-gray-700">
                            Descripción
                        </h2>
                        <p class="text-gray-800 whitespace-pre-line">
                            {{ courseData?.description ?? "—" }}
                        </p>
                    </div>

                    <div class="mb-4" v-if="areas.length">
                        <h2 class="mb-1 text-sm font-semibold text-gray-700">
                            Áreas académicas
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            <span v-for="(area, i) in areas" :key="i"
                                class="px-2 py-0.5 text-xs bg-gray-100 rounded-md text-gray-700">
                                {{ area }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500">
                                Creado
                            </h3>
                            <p class="text-sm text-gray-800">{{ createdAt }}</p>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500">
                                Actualizado
                            </h3>
                            <p class="text-sm text-gray-800">{{ updatedAt }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bloque Estudiantes -->
                <!-- <div v-if="
                    Array.isArray(courseData?.students) &&
                    courseData.students.length
                " class="p-5 mt-4 bg-white border rounded-2xl">
                    <h2 class="mb-2 text-sm font-semibold text-gray-700">
                        Estudiantes
                    </h2>
                    <ul class="space-y-2 text-gray-800">
                        <li v-for="s in courseData.students" :key="s.id" class="flex items-center justify-between">
                            <span class="truncate">{{
                                s.name ?? "ID #" + s.id
                                }}</span>
                            <span class="text-xs text-gray-500">
                                Progreso: {{ s.pivot?.progress ?? 0 }}%
                            </span>
                        </li>
                    </ul>
                </div> -->
            </div>

            <!-- Tabs de navegacion -->
            <OakTabs :tabs="tabs" />
        </div>
    </AppLayout>
</template>
