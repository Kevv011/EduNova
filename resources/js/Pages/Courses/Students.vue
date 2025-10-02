<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import moment from "moment";
import OakTabs from "@/Oak/Navigation/Tabs.vue";
import DialogModal from "@/Components/DialogModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
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

const profileModal = ref(false);

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
        current: false,
    },
    {
        name: "Estudiantes",
        href: route("courses.students", courseData.value.id),
        current: true,
    },
]);

// Manejo del modal
const openProfileModal = () => {
    profileModal.value = true;
};

const closeProfileModal = () => {
    profileModal.value = false;
};
</script>

<template>
    <AppLayout
        :title="courseData?.name ?? 'Curso'"
        subtitle="Detalle del curso"
        :pages="[
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
        ]"
    >
        <div class="flex items-center justify-between mb-6">
            <div></div>

            <div class="flex items-center gap-2">
                <Link
                    v-if="courseId"
                    :href="route('courses.edit', courseId)"
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-blue-700 rounded-lg bg-blue-50 hover:bg-blue-100"
                >
                    <PencilIcon class="w-4 h-4" /> Editar
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <!-- Columna imagen -->
            <div class="md:col-span-1">
                <div class="overflow-hidden bg-white rounded-2xl">
                    <img
                        :src="imageUrl"
                        alt="Imagen del curso"
                        class="object-cover w-full aspect-video"
                    />
                </div>
            </div>

            <!-- Columna info -->
            <div class="md:col-span-3">
                <div class="p-5 bg-white border rounded-2xl">
                    <h1 class="mb-2 text-2xl font-semibold">
                        {{ courseData?.name ?? "—" }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <span
                            class="px-2.5 py-1 text-xs font-medium rounded-lg bg-gray-100 text-gray-700"
                        >
                            Categoría: {{ categoryName }}
                        </span>

                        <span
                            :class="[
                                'px-2.5 py-1 text-xs font-medium rounded-lg',
                                availabilityClass,
                            ]"
                        >
                            {{ availabilityText }}
                        </span>

                        <span
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg"
                            :class="authorizedColor"
                        >
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
                            <span
                                v-for="(area, i) in areas"
                                :key="i"
                                class="px-2 py-0.5 text-xs bg-gray-100 rounded-md text-gray-700"
                            >
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
            </div>

            <!-- Tabs de navegacion -->
            <OakTabs :tabs="tabs" />

            <!-- Bloque Estudiantes -->
            <div
                v-if="
                    Array.isArray(courseData?.students) &&
                    courseData.students.length
                "
                class="col-span-4 p-5 mt-4 bg-white border shadow-sm rounded-2xl"
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">
                        Estudiantes inscritos
                    </h2>
                    <span
                        class="px-2.5 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-full"
                    >
                        {{ courseData.students.length }} estudiante{{
                            courseData.students.length === 1 ? "" : "s"
                        }}
                    </span>
                </div>

                <ul class="divide-y divide-gray-100">
                    <li
                        v-for="s in courseData.students"
                        :key="s.id"
                        class="py-3 first:pt-0 last:pb-0"
                    >
                        <div
                            class="flex items-center gap-4 px-2 -mx-2 transition rounded-xl hover:bg-gray-50"
                        >
                            <!-- Avatar iniciales -->
                            <div
                                class="flex items-center justify-center font-semibold text-indigo-900 rounded-full shrink-0 w-11 h-11 bg-gradient-to-br from-indigo-200 to-indigo-400"
                                :title="s.name ?? 'ID #' + s.id"
                            >
                                <img
                                    :src="s.student_info.user.profile_photo_url"
                                    :alt="s.student_info.user.name"
                                    class="rounded-full"
                                />
                            </div>

                            <!-- Info principal -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p
                                        class="font-medium text-gray-900 truncate"
                                    >
                                        {{
                                            s.student_info.user?.name ??
                                            "ID #" + s.id
                                        }}
                                    </p>

                                    <!-- Chip de estado -->
                                    <span
                                        v-if="s.pivot?.status_progress"
                                        class="px-2 py-0.5 text-[11px] rounded-full"
                                        :class="{
                                            'bg-emerald-100 text-emerald-700':
                                                s.pivot.status_progress ===
                                                'completed',
                                            'bg-amber-100 text-amber-700':
                                                s.pivot.status_progress ===
                                                'in_progress',
                                            'bg-gray-100 text-gray-600': ![
                                                'completed',
                                                'in_progress',
                                            ].includes(s.pivot.status_progress),
                                        }"
                                    >
                                        {{
                                            s.pivot.status_progress ===
                                            "completed"
                                                ? "Completado"
                                                : s.pivot.status_progress ===
                                                  "in_progress"
                                                ? "En progreso"
                                                : s.pivot.status_progress
                                        }}
                                    </span>
                                </div>

                                <!-- Barra de progreso -->
                                <div class="mt-2">
                                    <div
                                        class="h-2 overflow-hidden bg-gray-100 rounded-full"
                                    >
                                        <div
                                            class="h-full transition-all duration-500 ease-out rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600"
                                            :style="{
                                                width:
                                                    (s.pivot?.progress ?? 0) +
                                                    '%',
                                            }"
                                        />
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">
                                        Progreso:
                                        <span class="font-medium text-gray-700"
                                            >{{ s.pivot?.progress ?? 0 }}%</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Ver perfil -->
                            <div class="shrink-0">
                                <button
                                    type="button"
                                    class="px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition"
                                    v-tooltip="'Ver estudiante'"
                                    @click="openProfileModal"
                                >
                                    Ver
                                </button>
                            </div>
                        </div>

                        <!-- Modal para hacer ver perfil de estudiante -->
                        <DialogModal
                            :show="profileModal"
                            @close="profileModal = false"
                            maxWidth="2xl"
                        >
                            <template #title>Perfil de estudiante</template>
                            <template #subtitle
                                >Detalle general e información del
                                estudiante</template
                            >

                            <template #content>
                                <div
                                    class="grid grid-cols-1 gap-6 md:grid-cols-2"
                                >
                                    <!-- Imagen -->
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        <img
                                            :src="
                                                s.student_info.user
                                                    .profile_photo_url
                                            "
                                            :alt="s.student_info.user.name"
                                            class="object-cover w-48 h-48 rounded-full shadow"
                                        />
                                    </div>
                                    <!-- Datos -->
                                    <div class="space-y-5">
                                        <div>
                                            <span
                                                class="block text-xs text-gray-500"
                                                >Nombre</span
                                            >
                                            <span
                                                class="font-semibold text-gray-900"
                                                >{{
                                                    s.student_info.user?.name
                                                }}</span
                                            >
                                        </div>
                                        <div>
                                            <span
                                                class="block text-xs text-gray-500"
                                                >Correo electrónico</span
                                            >
                                            <span class="text-gray-800">{{
                                                s.student_info.user?.email
                                            }}</span>
                                        </div>
                                        <div>
                                            <span
                                                class="block text-xs text-gray-500"
                                                >Nivel académico</span
                                            >
                                            <span class="text-gray-800">{{
                                                s.student_info
                                                    ?.academic_level ?? "—"
                                            }}</span>
                                        </div>
                                        <div>
                                            <span
                                                class="block text-xs text-gray-500"
                                                >Institución educativa</span
                                            >
                                            <span class="text-gray-800">{{
                                                s.student_info?.institution ??
                                                "—"
                                            }}</span>
                                        </div>
                                        <div>
                                            <span
                                                class="block text-xs text-gray-500"
                                                >Intereses</span
                                            >
                                            <div
                                                class="flex flex-wrap gap-1 mt-1"
                                            >
                                                <span
                                                    v-for="(
                                                        interest, idx
                                                    ) in Array.isArray(
                                                        s.student_info
                                                            ?.interests
                                                    )
                                                        ? s.student_info
                                                              .interests
                                                        : []"
                                                    :key="idx"
                                                    class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs"
                                                >
                                                    {{ interest }}
                                                </span>
                                                <span
                                                    v-if="
                                                        !s.student_info
                                                            ?.interests ||
                                                        !s.student_info
                                                            .interests.length
                                                    "
                                                    class="text-gray-400"
                                                    >—</span
                                                >
                                            </div>
                                        </div>
                                        <div>
                                            <span
                                                class="block text-xs text-gray-500"
                                                >Fecha de inscripción</span
                                            >
                                            <span class="text-gray-800">{{
                                                moment(
                                                    s.pivot?.incription_date
                                                ).format(
                                                    "DD MMM YYYY, HH:mm"
                                                ) ?? "—"
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </DialogModal>
                    </li>
                </ul>
            </div>

            <!-- Contenido de modulos -->
            <div
                v-else
                class="flex flex-col items-center justify-center col-span-4 pt-5"
            >
                <ExclamationCircleIcon class="w-10 h-10 mb-3 text-yellow-400" />
                <h2 class="mb-1 text-lg font-semibold text-yellow-800">
                    ¡Aún no hay estudiantes inscritos en este curso!
                </h2>
                <p class="max-w-md mb-4 text-sm text-center text-yellow-700">
                    Por ahora, ningún estudiante se ha inscrito en tu curso.
                    Comparte el enlace o mantente atento: pronto podrás ver aquí
                    a tus primeros estudiantes.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
