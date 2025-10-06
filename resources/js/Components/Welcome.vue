<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    ArrowRightIcon,
    BookOpenIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";
import moment from "moment";
import PrimaryButton from "./PrimaryButton.vue";

const props = defineProps({
    courses: {
        type: Array,
        default: () => [],
    },
    user: {
        type: String,
    },
});

// Obtener los últimos 3 cursos
const recentCourses = computed(() => {
    return [...props.courses]
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        .slice(0, 3);
});

// Verificar si hay más de 3 cursos
const hasMoreCourses = computed(() => props.courses.length > 3);

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

// Formatear fecha
const formatDate = (date) => {
    return new Date(date).toLocaleDateString("es-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

// Calcular estudiantes inscritos en la última semana
const recentStudentsCount = computed(() => {
    const oneWeekAgo = moment().subtract(7, "days");
    let count = 0;

    props.courses.forEach((course) => {
        if (course.students && course.students.length) {
            course.students.forEach((student) => {
                const inscriptionDate = moment(student.pivot.incription_date);
                if (inscriptionDate.isAfter(oneWeekAgo)) {
                    count++;
                }
            });
        }
    });

    return count;
});
</script>

<template>
    <div class="relative overflow-hidden bg-white">

        <section class="relative pt-10 mb-10 overflow-hidden">
            <div aria-hidden="true" class="absolute inset-0">
                <div class="absolute inset-0 mx-auto overflow-hidden">
                    <img
                        src="/images/dashboard.png"
                        alt=""
                        class="object-cover size-full rounded-xl"
                    />
                </div>
                <div class="absolute inset-0 bg-white/85" />
                <div class="absolute" />
            </div>

            <div class="relative mx-16 mb-10">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Perfil del instructor -->
                    <div
                        class="p-6 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md"
                    >
                        <div class="flex items-center gap-4">
                            <img
                                :src="user.profile_photo_url"
                                :alt="user.name"
                                class="object-cover w-16 h-16 border-2 border-indigo-100 rounded-full"
                            />
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-xs font-medium tracking-wide text-gray-500 uppercase"
                                >
                                    Perfil
                                </p>
                                <h3
                                    class="text-lg font-semibold text-gray-900 truncate"
                                >
                                    {{ user.name }}
                                </h3>
                                <p class="text-sm text-gray-600 truncate">
                                    {{ user.email }}
                                </p>
                            </div>
                        </div>
                        <PrimaryButton
                            tag="a"
                            :href="route('profile.show')"
                            class="justify-center w-full mt-4"
                        >
                            Ver perfil
                        </PrimaryButton>
                    </div>

                    <!-- Cursos creados -->
                    <div
                        class="p-6 transition-shadow border border-indigo-200 shadow-sm bg-gradient-to-br from-gray-100 to-white rounded-xl hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-medium tracking-wide text-indigo-600 uppercase"
                                >
                                    Cursos creados
                                </p>
                                <p
                                    class="mt-2 text-4xl font-bold text-indigo-900"
                                >
                                    {{ courses.length }}
                                </p>
                                <p class="mt-1 text-sm text-indigo-700">
                                    Total de cursos
                                </p>
                            </div>
                            <div class="p-3 bg-indigo-200 rounded-full">
                                <BookOpenIcon class="w-8 h-8 text-indigo-700" />
                            </div>
                        </div>
                    </div>

                    <!-- Estudiantes inscritos última semana -->
                    <div
                        class="p-6 transition-shadow border border-green-200 shadow-sm bg-gradient-to-br from-gray-100 to-white rounded-xl hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-medium tracking-wide text-green-600 uppercase"
                                >
                                    Nuevos estudiantes
                                </p>
                                <p
                                    class="mt-2 text-4xl font-bold text-green-900"
                                >
                                    {{ recentStudentsCount }}
                                </p>
                                <p class="mt-1 text-sm text-green-700">
                                    Última semana
                                </p>
                            </div>
                            <div class="p-3 bg-green-200 rounded-full">
                                <svg
                                    class="w-8 h-8 text-green-700"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="mx-16">
            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">
                    Cursos creados recientemente
                </h2>
                <p class="mt-1 text-gray-600">
                    Consulta y gestiona tus cursos recientes, revisa el progreso
                    de tus alumnos y sigue impulsando la educación con EduNova.
                </p>
            </div>

            <!-- Sin cursos -->
            <div v-if="courses.length === 0" class="py-12 text-center">
                <BookOpenIcon class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <p class="text-lg text-gray-500">
                    Aún no has creado ningún curso.
                </p>
                <Link
                    :href="route('courses.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 mt-4 text-white transition bg-indigo-600 rounded-lg hover:bg-indigo-700"
                >
                    Crear mi primer curso
                </Link>
            </div>

            <!-- Grid de cursos recientes -->
            <div v-else>
                <div
                    class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="course in recentCourses"
                        :key="course.id"
                        class="relative overflow-hidden transition-all bg-white border border-gray-200 shadow-sm group rounded-xl hover:shadow-lg"
                    >
                        <!-- Badge de estado -->
                        <div class="absolute z-10 top-3 right-3">
                            <span
                                :class="[
                                    'px-3 py-1 text-xs font-semibold rounded-full',
                                    getAuthBadge(course.authorized).class,
                                ]"
                            >
                                {{ getAuthBadge(course.authorized).text }}
                            </span>
                        </div>

                        <!-- Contenido de la card -->
                        <div class="p-6">
                            <!-- Icono del curso -->
                            <div
                                class="flex items-center justify-center w-12 h-12 mb-4 rounded-lg bg-indigo-50"
                            >
                                <BookOpenIcon class="w-6 h-6 text-indigo-600" />
                            </div>

                            <!-- Nombre del curso -->
                            <h3
                                class="mb-2 text-lg font-semibold text-gray-900 line-clamp-2"
                            >
                                {{ course.name }}
                            </h3>

                            <!-- Descripción -->
                            <p class="mb-4 text-sm text-gray-600 line-clamp-3">
                                {{ course.description }}
                            </p>

                            <!-- Areas del curso -->
                            <div
                                v-if="course.areas && course.areas.length"
                                class="flex flex-wrap gap-2 mb-4"
                            >
                                <span
                                    v-for="(area, idx) in course.areas.slice(
                                        0,
                                        3
                                    )"
                                    :key="idx"
                                    class="px-2 py-1 text-xs font-medium text-indigo-700 bg-indigo-100 rounded"
                                >
                                    {{ area }}
                                </span>
                                <span
                                    v-if="course.areas.length > 3"
                                    class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded"
                                >
                                    +{{ course.areas.length - 3 }}
                                </span>
                            </div>

                            <!-- Fecha de creación -->
                            <p class="mb-4 text-xs text-gray-500">
                                Creado el
                                {{ formatDate(course.created_at) }}
                            </p>

                            <!-- Botón ver detalles -->
                            <Link
                                :href="route('courses.modules', course.id)"
                                class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 transition hover:text-indigo-700"
                            >
                                Ver curso
                                <ArrowRightIcon
                                    class="w-4 h-4 transition-transform group-hover:translate-x-1"
                                />
                            </Link>
                        </div>

                        <div
                            class="h-1 bg-gradient-to-r from-indigo-500 to-purple-500"
                        />
                    </div>
                </div>

                <!-- Botón "Ver más cursos" -->
                <div v-if="hasMoreCourses" class="mt-8 text-center">
                    <Link
                        :href="route('courses.index')"
                        class="inline-flex items-center gap-2 px-6 py-3 font-medium text-white transition bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg"
                    >
                        Ver más cursos
                        <ArrowRightIcon class="w-5 h-5" />
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
