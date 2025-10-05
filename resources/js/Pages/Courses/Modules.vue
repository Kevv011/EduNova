<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import moment from "moment";
import OakTabs from "@/Oak/Navigation/Tabs.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { CheckBadgeIcon, ChevronUpIcon } from "@heroicons/vue/20/solid";
import DialogModal from "@/Components/DialogModal.vue";
import FormSection2 from "@/Components/FormSection2.vue";
import TextInput from "@/Components/TextInput.vue";
import Textarea from "@/Oak/Textarea.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {
    CheckCircleIcon,
    XCircleIcon,
    ExclamationCircleIcon,
    PencilIcon,
    PlusIcon,
    EllipsisVerticalIcon,
    BookOpenIcon
} from "@heroicons/vue/24/outline";
import SecondaryButton from "@/Components/SecondaryButton.vue";

// Props
const props = defineProps({
    course: { type: [Object, null], required: true },
    modules: { type: [Object, null], required: true },
    success: { type: String },
});

const moduleModal = ref(false);
const deleteModuleModal = ref(false);
const editingModule = ref(null);

// Normalizar datos
const courseData = computed(() => props.course?.data ?? props.course ?? null);
const moduleData = computed(() => props.modules?.data ?? props.modules ?? null);

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

// Manejo del modal de STORE/UPDATE modulo
const openModuleModal = () => {
    form.id_course = courseId.value;
    moduleModal.value = true;
};

const closeModuleModal = () => {
    moduleModal.value = false;
    form.reset();
    editingModule.value = null;
};

const openEditModuleModal = (mod) => {
    editingModule.value = mod;

    form.title = mod.title;
    form.description = mod.description;
    form.number_module = mod.number_module;
    form.id_course = mod.course_id;
    moduleModal.value = true;
};

// Manejo del modal de DELETE modulo
const openDeleteModuleModal = (mod) => {
    editingModule.value = mod;
    deleteModuleModal.value = true;
};

const closeDeleteModuleModal = () => {
    form.reset();
    deleteModuleModal.value = false;
    editingModule.value = null;
};

// useForm para envio de datos al backend
const form = useForm({
    title: null,
    description: null,
    id_course: null,
});

// Metodos de store y update de modulos
const handlerStoreModule = () => {
    form.post(route("modules.store"), {
        preserveScroll: true,

        onSuccess: () => {
            form.reset();
            moduleModal.value = false;
            editingModule.value = null;
        },
    });
};

const handlerUpdateModule = () => {
    form.put(route("modules.update", editingModule.value.id), {
        preserveScroll: true,

        onSuccess: () => {
            form.reset();
            moduleModal.value = false;
            editingModule.value = null;
        },

        onError: () => {
            console.log(form.errors);
        },
    });
};

// Metodo para deshabilitar/habilitar modulo
const handlerDisableModule = () => {
    deleteModuleModal.value = false;

    form.put(
        route("modules.disable", editingModule.value.id),
        { status: 0 },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                editingModule.value = null;
            },
        }
    );
};

const handlerEnableModule = () => {
    deleteModuleModal.value = false;

    form.put(
        route("modules.enable", editingModule.value.id),
        { status: 1 },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                editingModule.value = null;
            },
        }
    );
};

const handlerDeleteModule = () => {
    form.delete(route("modules.destroy", editingModule.value.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            deleteModuleModal.value = false;
            editingModule.value = null;
        },
    });
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
        <!-- Mensaje de exito -->
        <div class="mx-auto mb-4" v-if="success">
            <div
                class="p-3 text-green-800 border border-green-200 rounded-lg shadow-sm bg-green-50"
            >
                <div class="flex items-center">
                    <svg
                        class="w-6 h-6 mr-3 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-green-800 text-md">
                            {{ success }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- <pre>{{ moduleData }}</pre> -->
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

            <div class="col-span-4">
                <div class="flex justify-end" v-if="moduleData.length">
                    <PrimaryButton @click="openModuleModal">
                        <PlusIcon class="w-5 h-5 mr-1" />
                        Agregar módulo
                    </PrimaryButton>
                </div>

                <!-- Contenido de modulos -->
                <div
                    v-if="!moduleData.length"
                    class="flex flex-col items-center justify-center rounded-xl"
                >
                    <ExclamationCircleIcon
                        class="w-10 h-10 mb-3 text-yellow-400"
                    />
                    <h2 class="mb-1 text-lg font-semibold text-yellow-800">
                        ¡Aún no hay módulos en este curso!
                    </h2>
                    <p
                        class="max-w-md mb-4 text-sm text-center text-yellow-700"
                    >
                        Comienza a estructurar el contenido de tu curso creando
                        el primer módulo.
                    </p>
                    <PrimaryButton @click="openModuleModal">
                        <PlusIcon class="w-5 h-5 mr-1" />
                        Crear módulo
                    </PrimaryButton>
                </div>

                <div v-else>
                    <p class="text-2xl font-semibold text-gray-700">
                        Módulos del curso
                    </p>
                    <div
                        class="grid items-center justify-center grid-cols-2 pt-5 space-y-4"
                    >
                        <div
                            v-for="mod in moduleData"
                            :key="mod.id"
                            class="mx-2"
                        >
                            <div>
                                <Disclosure v-slot="{ open }">
                                    <!-- CARD -->
                                    <div
                                        class="transition border border-gray-200 shadow-sm rounded-2xl bg-white/90 hover:shadow-md"
                                    >
                                        <!-- BUTTON -->
                                        <DisclosureButton
                                            :class="[
                                                'flex items-center justify-between w-full gap-4 px-5 py-5 text-left transition outline-none group rounded-2xl',
                                                mod.status
                                                    ? 'border-b-4 border-green-500'
                                                    : 'border-b-4 border-red-500',
                                            ]"
                                        >
                                            <!-- Title + meta -->
                                            <div class="min-w-0">
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <span
                                                        class="inline-flex items-center text-xs font-semibold text-indigo-700 rounded-full ps-3 bg-indigo-50"
                                                    >
                                                        {{
                                                            mod.number_module
                                                                ? `Módulo ${mod.number_module}`
                                                                : "Módulo"
                                                        }}
                                                        <div
                                                            :class="[
                                                                'px-2 py-1 text-white ms-3 rounded-xl',
                                                                mod.status
                                                                    ? 'bg-green-700'
                                                                    : 'bg-red-700',
                                                            ]"
                                                        >
                                                            {{
                                                                mod.status
                                                                    ? "Habilitado"
                                                                    : "Deshabilitado"
                                                            }}
                                                        </div>
                                                    </span>
                                                </div>

                                                <div
                                                    class="flex items-center justify-center gap-2 mt-3"
                                                >
                                                    <h3
                                                        class="text-base font-semibold text-gray-900 truncate group-hover:text-gray-950"
                                                        :title="mod.title"
                                                    >
                                                        {{ mod.title }}
                                                    </h3>
                                                    <ExclamationCircleIcon
                                                        v-if="
                                                            !mod.lessons.length
                                                        "
                                                        class="text-yellow-600 bg-yellow-100 rounded-full size-7"
                                                        v-tooltip="
                                                            'Aún no existen lecciones para este módulo'
                                                        "
                                                    />
                                                    <CheckBadgeIcon
                                                        v-else
                                                        class="text-green-600 bg-green-100 rounded-full size-7"
                                                        v-tooltip="
                                                            mod.lessons.length +
                                                            ' lecciones'
                                                        "
                                                    />
                                                </div>
                                            </div>

                                            <!-- Chevron -->
                                            <div
                                                class="flex items-center justify-center gap-2 p-2 transition bg-white shrink-0 rounded-xl group-hover:border-gray-300"
                                                aria-hidden="true"
                                            >
                                                <Link
                                                    :href="route('modules.lessons', mod.id)"
                                                    class="px-4 py-2 border border-gray-500 rounded-lg"
                                                    color="lightblue"
                                                    v-tooltip="
                                                        'Lecciones'
                                                    "

                                                >
                                                    <BookOpenIcon
                                                        class="mr-1 size-5"
                                                    />
                                                </Link>
                                                <PrimaryButton
                                                    color="lightblue"
                                                    v-tooltip="'Editar'"
                                                    @click="
                                                        openEditModuleModal(mod)
                                                    "
                                                    @click.stop="
                                                        openEditModuleModal(mod)
                                                    "
                                                >
                                                    <PencilIcon
                                                        class="w-4 h-4 mr-1"
                                                    />
                                                </PrimaryButton>
                                                <PrimaryButton
                                                    color="orange"
                                                    v-tooltip="'Acciones'"
                                                    @click="
                                                        openDeleteModuleModal(
                                                            mod
                                                        )
                                                    "
                                                    @click.stop="
                                                        openDeleteModuleModal(
                                                            mod
                                                        )
                                                    "
                                                >
                                                    <EllipsisVerticalIcon
                                                        class="w-4 h-4 mr-1"
                                                    />
                                                </PrimaryButton>
                                            </div>
                                        </DisclosureButton>

                                        <!-- Divider animado -->
                                        <div
                                            class="h-px mx-5 transition-transform duration-300 origin-left scale-x-0 bg-gradient-to-r from-indigo-200 via-indigo-300 to-indigo-200"
                                            :class="open ? 'scale-x-100' : ''"
                                        />

                                        <!-- PANEL -->
                                        <DisclosurePanel class="px-5 pt-4 pb-5">
                                            <div class="space-y-5">
                                                <div>
                                                    <span
                                                        class="block text-xs font-semibold tracking-wide text-gray-500 uppercase"
                                                    >
                                                        Descripción
                                                    </span>
                                                    <p
                                                        class="mt-1 text-sm leading-relaxed text-gray-700"
                                                    >
                                                        {{
                                                            mod.description ??
                                                            "—"
                                                        }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <span
                                                        class="block text-xs font-semibold tracking-wide text-gray-500 uppercase"
                                                    >
                                                        Número de lecciones
                                                    </span>
                                                    <p
                                                        class="mt-1 text-sm leading-relaxed text-gray-700"
                                                    >
                                                        {{
                                                            mod?.lessons
                                                                .length ?? "—"
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </DisclosurePanel>
                                    </div>
                                </Disclosure>
                            </div>

                            <!-- Modal para eliminar un modulo -->
                            <DialogModal
                                :show="deleteModuleModal"
                                @close="deleteModuleModal = false"
                                maxWidth="2xl"
                            >
                                <template #title>Acciones</template>
                                <template #subtitle>
                                    Puedes eliminar este módulo, o bien
                                    habilitarlo o deshabilitarlo según lo
                                    necesites. Recuerda que deshabilitarlo
                                    impedirá el acceso de los estudiantes, pero
                                    conservará su información para futuras
                                    ediciones o reactivaciones.
                                </template>
                                <template #content>
                                    <FormSection2>
                                        <template #form>
                                            <div class="space-y-5"></div>
                                        </template>
                                        <template #actions>
                                            <div class="space-x-4">
                                                <SecondaryButton
                                                    @click="
                                                        closeDeleteModuleModal
                                                    "
                                                >
                                                    Cancelar
                                                </SecondaryButton>
                                                <PrimaryButton
                                                    color="yellow"
                                                    @click="
                                                        handlerDisableModule
                                                    "
                                                    :class="{
                                                        'opacity-25':
                                                            form.processing,
                                                    }"
                                                    :disabled="form.processing"
                                                    :loading="form.processing"
                                                    v-if="
                                                        editingModule &&
                                                        editingModule.status
                                                    "
                                                >
                                                    Deshabilitar
                                                </PrimaryButton>
                                                <PrimaryButton
                                                    color="green"
                                                    @click="handlerEnableModule"
                                                    :class="{
                                                        'opacity-25':
                                                            form.processing,
                                                    }"
                                                    :disabled="form.processing"
                                                    :loading="form.processing"
                                                    v-else
                                                >
                                                    Habilitar
                                                </PrimaryButton>
                                                <PrimaryButton
                                                    color="red"
                                                    @click="handlerDeleteModule"
                                                    :class="{
                                                        'opacity-25':
                                                            form.processing,
                                                    }"
                                                    :disabled="form.processing"
                                                    :loading="form.processing"
                                                >
                                                    Eliminar
                                                </PrimaryButton>
                                            </div>
                                        </template>
                                    </FormSection2>
                                </template>
                            </DialogModal>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para agregar un modulo -->
        <DialogModal
            :show="moduleModal"
            @close="moduleModal = false"
            maxWidth="2xl"
        >
            <template #title>{{
                editingModule ? "Editar módulo" : "Nuevo módulo"
            }}</template>
            <template #subtitle>{{
                editingModule
                    ? "Actualiza la información de tu módulo a continuación"
                    : "Detalla las generalidades de tu nuevo módulo a continuación"
            }}</template>

            <template #content>
                <FormSection2
                    @submitted="
                        editingModule
                            ? handlerUpdateModule()
                            : handlerStoreModule()
                    "
                >
                    <template #form>
                        <div class="space-y-5">
                            <!-- Titulo -->
                            <div class="col-span-6">
                                <InputLabel for="title" value="Titulo" />
                                <TextInput
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    class="block w-full mt-1"
                                    autocomplete="name"
                                />
                                <InputError
                                    :message="form.errors.title"
                                    class="mt-2"
                                />
                            </div>

                            <!-- Descripcion -->
                            <div class="col-span-6">
                                <InputLabel
                                    for="description"
                                    value="Descripción"
                                />
                                <Textarea
                                    id="description"
                                    v-model="form.description"
                                    class="block w-full mt-1"
                                    rows="3"
                                    placeholder="Describe brevemente este modulo..."
                                />
                                <InputError
                                    :message="form.errors.description"
                                    class="mt-2"
                                />
                            </div>

                            <!-- ID de curso -->
                            <div class="hidden col-span-6">
                                <TextInput
                                    v-model="form.id_course"
                                    type="text"
                                    class="block w-full mt-1"
                                    :value="courseData.id"
                                />
                                <InputError
                                    :message="form.errors.id_course"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </template>
                    <template #actions>
                        <div class="space-x-4">
                            <SecondaryButton @click="closeModuleModal">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton
                                :class="{
                                    'opacity-25': form.processing,
                                }"
                                :disabled="form.processing"
                                :loading="form.processing"
                            >
                                {{ editingModule ? "Actualizar" : "Crear" }}
                            </PrimaryButton>
                        </div>
                    </template>
                </FormSection2>
            </template>
        </DialogModal>
    </AppLayout>
</template>
