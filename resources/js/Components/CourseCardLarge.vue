<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import {
    BookOpenIcon,
    UserIcon,
    CalendarIcon,
    ArrowRightIcon,
    TagIcon,
} from "@heroicons/vue/24/outline";
import moment from "moment";

// Props
const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
});

const page = usePage();

// Obtener la fecha de inscripción del usuario actual
const enrollmentDate = computed(() => {
    const currentUser = page.props.auth?.user;
    if (!currentUser || !props.course.students) return null;

    const enrollment = props.course.students.find(
        (student) => student.student_info?.user?.id === currentUser.id
    );

    return enrollment?.pivot?.incription_date
        ? moment(enrollment.pivot.incription_date).format("DD/MM/YYYY")
        : null;
});

// Progreso del usuario actual
const userProgress = computed(() => {
    const currentUser = page.props.auth?.user;
    if (!currentUser || !props.course.students) return 0;

    const enrollment = props.course.students.find(
        (student) => student.student_info?.user?.id === currentUser.id
    );

    return enrollment?.pivot?.progress ?? 0;
});

// Badge de progreso
const getProgressBadge = (progress) => {
    if (progress === 0) {
        return { text: "No iniciado", class: "bg-gray-100 text-gray-800" };
    } else if (progress < 50) {
        return { text: "En progreso", class: "bg-blue-100 text-blue-800" };
    } else if (progress < 100) {
        return { text: "Avanzado", class: "bg-indigo-100 text-indigo-800" };
    } else {
        return { text: "Completado", class: "bg-green-100 text-green-800" };
    }
};
</script>

<template>
    <div
        class="relative overflow-hidden transition-all bg-white border border-gray-200 shadow-sm group rounded-xl hover:shadow-lg"
    >
        <div class="flex flex-col md:flex-row">
            <!-- Imagen del curso -->
            <div
                class="relative h-48 overflow-hidden md:w-80 md:h-auto shrink-0"
            >
                <img
                    v-if="course.image"
                    :src="course.image"
                    :alt="course.name"
                    class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110"
                />
                <div
                    v-else
                    class="flex items-center justify-center w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100"
                >
                    <BookOpenIcon class="w-20 h-20 text-indigo-300" />
                </div>

                <!-- Badge de progreso flotante -->
                <div class="absolute top-4 right-4">
                    <span
                        :class="[
                            'px-3 py-1 text-xs font-semibold rounded-full shadow-md backdrop-blur-sm',
                            getProgressBadge(userProgress).class,
                        ]"
                    >
                        {{ getProgressBadge(userProgress).text }}
                    </span>
                </div>
            </div>

            <!-- Contenido -->
            <div class="flex flex-col justify-between flex-1 p-6">
                <!-- Información del curso -->
                <div>
                    <!-- Categoría -->
                    <div
                        v-if="course.category"
                        class="flex items-center gap-2 mb-3"
                    >
                        <TagIcon class="w-4 h-4 text-indigo-600" />
                        <span class="text-sm font-medium text-indigo-600">
                            {{ course.category.name }}
                        </span>
                    </div>

                    <!-- Nombre del curso -->
                    <h3
                        class="mb-3 text-xl font-bold text-gray-900 transition-colors line-clamp-2 group-hover:text-indigo-600"
                    >
                        {{ course.name }}
                    </h3>

                    <!-- Descripción -->
                    <p class="mb-4 text-sm text-gray-600 line-clamp-2">
                        {{ course.description }}
                    </p>

                    <!-- Áreas del curso -->
                    <div
                        v-if="course.areas && course.areas.length"
                        class="flex flex-wrap gap-2 mb-4"
                    >
                        <span
                            v-for="(area, idx) in course.areas.slice(0, 3)"
                            :key="idx"
                            class="px-2 py-1 text-xs font-medium text-indigo-700 rounded-md bg-indigo-50"
                        >
                            {{ area }}
                        </span>
                        <span
                            v-if="course.areas.length > 3"
                            class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md"
                        >
                            +{{ course.areas.length - 3 }}
                        </span>
                    </div>

                    <!-- Barra de progreso -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-gray-600">
                                Progreso del curso
                            </span>
                            <span class="text-xs font-bold text-indigo-600">
                                {{ userProgress }}%
                            </span>
                        </div>
                        <div
                            class="w-full h-2 overflow-hidden bg-gray-200 rounded-full"
                        >
                            <div
                                class="h-full transition-all duration-500 bg-gradient-to-r from-indigo-500 to-purple-500"
                                :style="{ width: `${userProgress}%` }"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Footer con instructor y botón -->
                <div
                    class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-200"
                >
                    <!-- Instructor y fecha -->
                    <div class="space-y-2">
                        <!-- Instructor -->
                        <div
                            v-if="course.instructor"
                            class="flex items-center gap-2"
                        >
                            <UserIcon class="w-4 h-4 text-gray-500" />
                            <span class="text-sm text-gray-700">
                                Instructor:
                                {{
                                    course.instructor.user?.name ||
                                    "Sin instructor"
                                }}
                            </span>
                        </div>

                        <!-- Fecha de inscripción -->
                        <div
                            v-if="enrollmentDate"
                            class="flex items-center gap-2"
                        >
                            <CalendarIcon class="w-4 h-4 text-gray-500" />
                            <span class="text-sm text-gray-600">
                                Inscrito el {{ enrollmentDate }}
                            </span>
                        </div>
                    </div>

                    <!-- Botón ir al curso -->
                    <Link
                        :href="route('courses.students.modules', course.id)"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white transition-all bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Ir al curso
                        <ArrowRightIcon class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
