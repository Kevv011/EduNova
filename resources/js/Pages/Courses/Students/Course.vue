<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref } from "vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { useForm, usePage } from "@inertiajs/vue3";
import {
    ChevronUpIcon,
    BookOpenIcon,
    UserIcon,
    AcademicCapIcon,
    ClockIcon,
    UsersIcon,
    TagIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import FormSection2 from "@/Components/FormSection2.vue";

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
    user: {
        type: String,
    },
});

const page = usePage();
const inscriptionModal = ref(false);
const selectedCourse = ref(null);

// Normalizar datos
const courseData = computed(() => props.course?.data ?? props.course ?? {});

// Contador de estudiantes inscritos
const enrolledStudents = computed(() => {
    return courseData.value.students?.length ?? 0;
});

// Total de lecciones
const totalLessons = computed(() => {
    return (
        courseData.value.modules?.reduce(
            (total, module) => total + (module.lessons?.length ?? 0),
            0
        ) ?? 0
    );
});

// Verificar si el estudiante ya está inscrito
const isEnrolled = computed(() => {
    const currentUser = page.props.auth?.user;
    if (!currentUser || !courseData.value.students) return false;

    return courseData.value.students.some(
        (student) => student.student_info?.user?.id === currentUser.id
    );
});

// useForm de inscripcion estudiante
const form = useForm({
    course_id: null,
    student_id: null,
});

// Manejo de modal de inscripciones
const openInscriptionModal = () => {
    selectedCourse.value = {
        id: courseData.value.id,
        name: courseData.value.name,
        category: courseData.value.category?.name,
        instructor: courseData.value.instructor?.user?.name,
        image: courseData.value.image,
        modules: courseData.value.modules?.length ?? 0,
        lessons: totalLessons.value,
    };

    form.course_id = courseData.value.id;

    inscriptionModal.value = true;
};

const closeInscriptionModal = () => {
    inscriptionModal.value = false;
    selectedCourse.value = null;
    form.reset();
};

// Enviar inscripcion
const submitInscription = () => {
    form.post(route("courses.students.inscription"), {
        preserveScroll: true,
        onSuccess: () => {
            closeInscriptionModal();
        },
        onError: (errors) => {
            console.error("Error en inscripción:", errors);
        },
    });
};
</script>

<template>
    <AppLayout
        title="Detalles del curso"
        :pages="[
            {
                name: 'Cursos disponibles',
                href: route('courses.students.index'),
                current: false,
            },
            {
                name: courseData.name,
                href: route('courses.students.courses', courseData.id),
                current: true,
            },
        ]"
    >
        <section
            class="relative bg-gradient-to-br from-gray-600 via-gray-800 to-black"
        >
            <div class="container px-6 py-16 mx-auto lg:py-20">
                <div class="grid items-center gap-8 lg:grid-cols-2">
                    <!-- Información del Curso -->
                    <div class="text-white">
                        <!-- Categoría -->
                        <div
                            v-if="courseData.category"
                            class="flex items-center gap-2 mb-4"
                        >
                            <TagIcon class="w-5 h-5 text-indigo-400" />
                            <span class="text-sm font-medium text-indigo-400">
                                {{ courseData.category.name }}
                            </span>
                        </div>

                        <!-- Nombre del Curso -->
                        <h1
                            class="mb-4 text-4xl font-bold leading-tight lg:text-5xl"
                        >
                            {{ courseData.name }}
                        </h1>

                        <!-- Descripción -->
                        <p class="mb-6 text-lg leading-relaxed text-gray-300">
                            {{ courseData.description }}
                        </p>

                        <!-- Áreas del Curso -->
                        <div
                            v-if="courseData.areas && courseData.areas.length"
                            class="mb-6"
                        >
                            <h3
                                class="mb-3 text-sm font-semibold tracking-wide text-gray-400 uppercase"
                            >
                                Áreas de conocimiento
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(area, idx) in courseData.areas"
                                    :key="idx"
                                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-400 rounded-full"
                                >
                                    {{ area }}
                                </span>
                            </div>
                        </div>

                        <!-- Estadísticas -->
                        <div class="flex flex-wrap gap-6">
                            <div class="flex items-center gap-2">
                                <BookOpenIcon class="w-5 h-5 text-indigo-400" />
                                <span class="text-sm text-gray-300">
                                    {{ courseData.modules?.length ?? 0 }}
                                    módulos
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <ClockIcon class="w-5 h-5 text-indigo-400" />
                                <span class="text-sm text-gray-300">
                                    {{ totalLessons }} lecciones
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <UsersIcon class="w-5 h-5 text-indigo-400" />
                                <span class="text-sm text-gray-300">
                                    {{ enrolledStudents }} estudiantes inscritos
                                </span>
                            </div>
                        </div>

                        <!-- Boton de inscripcion -->
                        <div class="mt-5">
                            <PrimaryButton
                                v-if="!isEnrolled"
                                @click="openInscriptionModal"
                            >
                                <PlusIcon class="size-5 me-1" />
                                Inscribirme
                            </PrimaryButton>

                            <div
                                v-else
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-green-700 bg-green-100 border border-green-300 rounded-lg"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                Ya estás inscrito
                            </div>
                        </div>
                    </div>

                    <!-- Imagen del Curso -->
                    <div class="relative lg:ml-8">
                        <div
                            class="overflow-hidden shadow-2xl border-3 rounded-2xl lg:-mb-32 lg:-mt-32"
                        >
                            <img
                                v-if="courseData.image"
                                :src="courseData.image"
                                :alt="courseData.name"
                                class="object-cover w-full h-64 lg:h-96"
                            />
                            <div
                                v-else
                                class="flex items-center justify-center w-full h-64 bg-gradient-to-br from-indigo-100 to-purple-100 lg:h-96"
                            >
                                <BookOpenIcon
                                    class="w-24 h-24 text-indigo-300"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contenido Principal -->
        <section class="py-6">
            <div class="container mx-auto">
                <div class="grid gap-8 lg:grid-cols-4">
                    <!-- Módulos y Lecciones -->
                    <div class="lg:col-span-3">
                        <div class="p-6 bg-white shadow-md rounded-xl">
                            <h2 class="mb-6 text-2xl font-bold text-gray-900">
                                Contenido del curso
                            </h2>

                            <!-- Sin módulos -->
                            <div
                                v-if="
                                    !courseData.modules ||
                                    courseData.modules.length === 0
                                "
                                class="py-12 text-center"
                            >
                                <BookOpenIcon
                                    class="w-16 h-16 mx-auto mb-4 text-gray-400"
                                />
                                <p class="text-gray-500">
                                    Este curso aún no tiene módulos disponibles.
                                </p>
                            </div>

                            <!-- Lista de Módulos -->
                            <div v-else class="space-y-4">
                                <Disclosure
                                    v-for="(module, idx) in courseData.modules"
                                    :key="module.id"
                                    v-slot="{ open }"
                                >
                                    <DisclosureButton
                                        class="flex items-center justify-between w-full px-5 py-4 text-left transition-all border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500"
                                    >
                                        <div
                                            class="flex items-center min-w-0 gap-4"
                                        >
                                            <!-- Número del módulo -->
                                            <div
                                                class="flex items-center justify-center w-10 h-10 font-bold text-white bg-indigo-600 rounded-full shrink-0"
                                            >
                                                {{ module.number_module }}
                                            </div>

                                            <!-- Info del módulo -->
                                            <div class="min-w-0">
                                                <h3
                                                    class="text-lg font-semibold text-gray-900"
                                                >
                                                    {{ module.title }}
                                                </h3>
                                                <p
                                                    class="text-sm text-gray-500 truncate"
                                                >
                                                    {{ module.description }}
                                                </p>
                                                <p
                                                    class="mt-1 text-xs font-medium text-indigo-600"
                                                >
                                                    {{
                                                        module.lessons
                                                            ?.length ?? 0
                                                    }}
                                                    {{
                                                        module.lessons
                                                            ?.length === 1
                                                            ? "lección"
                                                            : "lecciones"
                                                    }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Icono de chevron -->
                                        <ChevronUpIcon
                                            :class="
                                                open
                                                    ? 'rotate-180 transform'
                                                    : ''
                                            "
                                            class="w-5 h-5 text-gray-400 transition-transform shrink-0"
                                        />
                                    </DisclosureButton>

                                    <DisclosurePanel class="px-4 pt-4 pb-2">
                                        <!-- Sin lecciones -->
                                        <div
                                            v-if="
                                                !module.lessons ||
                                                module.lessons.length === 0
                                            "
                                            class="py-8 text-center text-gray-500"
                                        >
                                            No hay lecciones en este módulo.
                                        </div>

                                        <!-- Lista de lecciones -->
                                        <ul v-else class="space-y-2">
                                            <li
                                                v-for="lesson in module.lessons"
                                                :key="lesson.id"
                                                class="flex items-center gap-3 p-3 transition-all border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100"
                                            >
                                                <!-- Posición de la lección -->
                                                <div
                                                    class="flex items-center justify-center w-8 h-8 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full shrink-0"
                                                >
                                                    {{ lesson.position }}
                                                </div>

                                                <!-- Info de la lección -->
                                                <div class="flex-1 min-w-0">
                                                    <h4
                                                        class="font-medium text-gray-900"
                                                    >
                                                        {{ lesson.title }}
                                                    </h4>
                                                    <p
                                                        v-if="lesson.subtitle"
                                                        class="text-sm text-gray-600"
                                                    >
                                                        {{ lesson.subtitle }}
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    </DisclosurePanel>
                                </Disclosure>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Instructor -->
                    <div class="lg:col-span-1">
                        <div
                            class="sticky p-6 bg-white shadow-md top-6 rounded-xl"
                        >
                            <h3 class="mb-4 text-lg font-bold text-gray-900">
                                Instructor
                            </h3>

                            <div v-if="courseData.instructor" class="space-y-4">
                                <!-- Avatar del instructor -->
                                <div
                                    class="flex items-center justify-center w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-indigo-100 to-purple-100"
                                >
                                    <UserIcon
                                        class="w-10 h-10 text-indigo-600"
                                    />
                                </div>

                                <!-- Nombre (desde instructor.user si existe) -->
                                <div class="text-center">
                                    <p class="font-semibold text-gray-900">
                                        {{
                                            courseData.instructor.user?.name ??
                                            "Instructor"
                                        }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Instructor del curso
                                    </p>
                                </div>

                                <!-- Biografía -->
                                <div v-if="courseData.instructor.biography">
                                    <p class="text-sm text-gray-600">
                                        {{ courseData.instructor.biography }}
                                    </p>
                                </div>

                                <!-- Especialidades -->
                                <div
                                    v-if="
                                        courseData.instructor
                                            .subject_specialties &&
                                        courseData.instructor
                                            .subject_specialties.length
                                    "
                                >
                                    <h4
                                        class="mb-2 text-xs font-semibold tracking-wide text-gray-500 uppercase"
                                    >
                                        Especialidades
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            v-for="(
                                                specialty, idx
                                            ) in courseData.instructor
                                                .subject_specialties"
                                            :key="idx"
                                            class="px-2 py-1 text-xs font-medium text-indigo-700 rounded bg-indigo-50"
                                        >
                                            {{ specialty }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Nivel académico -->
                                <div
                                    v-if="courseData.instructor.academic_level"
                                >
                                    <div
                                        class="flex flex-col gap-2 text-sm text-gray-600"
                                    >
                                        <div class="flex items-center gap-1">
                                            <h4
                                                class="mt-2 text-xs font-semibold tracking-wide text-gray-500 uppercase"
                                            >
                                                Areas de enseñanza
                                            </h4>
                                        </div>
                                        <span class="capitalize">
                                            {{
                                                courseData.instructor.level_education.join(
                                                    ", "
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="py-8 text-center text-gray-500">
                                <UserIcon
                                    class="w-12 h-12 mx-auto mb-2 text-gray-400"
                                />
                                <p class="text-sm">
                                    Sin información del instructor
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Modal para eliminar un modulo -->
        <DialogModal
            :show="inscriptionModal"
            @close="inscriptionModal = false"
            maxWidth="2xl"
        >
            <template #title>Inscribirse al curso</template>
            <template #content>
                <FormSection2>
                    <template #form>
                        <!-- Información del curso seleccionado -->
                        <div v-if="selectedCourse" class="space-y-5">
                            <!-- Preview del curso -->
                            <div
                                class="overflow-hidden border border-gray-200 rounded-lg bg-gray-50"
                            >
                                <div class="grid gap-4 p-5 md:grid-cols-3">
                                    <!-- Imagen -->
                                    <div class="md:col-span-1">
                                        <img
                                            v-if="selectedCourse.image"
                                            :src="selectedCourse.image"
                                            :alt="selectedCourse.name"
                                            class="object-cover w-full h-32 rounded-lg"
                                        />
                                        <div
                                            v-else
                                            class="flex items-center justify-center w-full h-32 rounded-lg bg-gradient-to-br from-indigo-100 to-purple-100"
                                        >
                                            <BookOpenIcon
                                                class="w-12 h-12 text-indigo-300"
                                            />
                                        </div>
                                    </div>

                                    <!-- Información -->
                                    <div class="md:col-span-2">
                                        <h3
                                            class="mb-2 text-lg font-bold text-gray-900"
                                        >
                                            {{ selectedCourse.name }}
                                        </h3>

                                        <div
                                            class="space-y-2 text-sm text-gray-600"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <TagIcon
                                                    class="w-4 h-4 text-indigo-600"
                                                />
                                                <span>{{
                                                    selectedCourse.category
                                                }}</span>
                                            </div>

                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <UserIcon
                                                    class="w-4 h-4 text-indigo-600"
                                                />
                                                <span>{{
                                                    selectedCourse.instructor
                                                }}</span>
                                            </div>

                                            <div
                                                class="flex items-center gap-4 mt-3"
                                            >
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <BookOpenIcon
                                                        class="w-4 h-4 text-gray-500"
                                                    />
                                                    <span
                                                        >{{
                                                            selectedCourse.modules
                                                        }}
                                                        módulos</span
                                                    >
                                                </div>
                                                <div
                                                    class="flex items-center gap-1"
                                                >
                                                    <ClockIcon
                                                        class="w-4 h-4 text-gray-500"
                                                    />
                                                    <span
                                                        >{{
                                                            selectedCourse.lessons
                                                        }}
                                                        lecciones</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mensaje informativo -->
                            <div
                                class="p-4 border-l-4 border-indigo-500 rounded bg-indigo-50"
                            >
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg
                                            class="w-5 h-5 text-indigo-600"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-indigo-700">
                                            Al inscribirte, podrás acceder a
                                            todo el contenido del curso y tu
                                            progreso será registrado
                                            automáticamente.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Errores del formulario -->
                            <div
                                v-if="
                                    form.errors.course_id ||
                                    form.errors.student_id
                                "
                                class="p-4 border-l-4 border-red-500 rounded bg-red-50"
                            >
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg
                                            class="w-5 h-5 text-red-600"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            {{
                                                form.errors.course_id ||
                                                form.errors.student_id
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template #actions>
                        <div class="space-x-4">
                            <PrimaryButton
                                @click="submitInscription"
                                :class="{
                                    'opacity-25': form.processing,
                                }"
                                :disabled="form.processing"
                                :loading="form.processing"
                            >
                                Confirmar inscripción
                            </PrimaryButton>
                            <SecondaryButton
                                @click="closeInscriptionModal"
                                :disabled="form.processing"
                            >
                                Cancelar
                            </SecondaryButton>
                        </div>
                    </template>
                </FormSection2>
            </template>
        </DialogModal>
    </AppLayout>
</template>
