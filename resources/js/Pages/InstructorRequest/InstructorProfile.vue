<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import moment from "moment";

import FormSection from "@/Components/FormSection.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Textarea from "@/Oak/Textarea.vue";
import Select from "@/Oak/Select.vue";
import FileUpload from "@/Components/FileUpload.vue";
import Loading from "@/Oak/Loading.vue";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronUpIcon } from "@heroicons/vue/20/solid";
import DoubleFormSection from "@/Components/DoubleFormSection.vue";

// Prop enviada por en backend
const props = defineProps({
    instructorRequest: { type: Object, default: null },
});

const page = usePage();
const success = computed(() => page.props.flash?.success ?? null);
const isLoading = ref(false);
const secondSubmitted = ref(false);

const pretty = (v) => (v && String(v).trim().length ? v : "—");
const createdAt = computed(() =>
    props.instructorRequest?.created_at
        ? moment(props.instructorRequest.created_at).format(
              "DD MMM YYYY, HH:mm"
          )
        : null
);

// Foto de perfil
const photoPreview = ref(null);
const photoInput = ref(null);

const updatePhotoPreview = () => {
    const file = photoInput.value?.files?.[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => (photoPreview.value = e.target.result);
    reader.readAsDataURL(file);
};
const selectNewPhoto = () => photoInput.value?.click();

// URL final de la imagen guardada
const savedPhotoUrl = computed(() => {
    const p = props.instructorRequest?.profile_photo_path;
    return p ? `/storage/${p}` : "/images/user-default.jpeg";
});

// useForm para envio de datos al backend
const form = useForm({
    name: null,
    email: null,
    phone: null,
    biography: null,
    profile_photo_path: null,
});

// useForm para envio de datos al backend (Segundo form)
const instructorForm = useForm({
    academic_level: null,
    subject_specialties: [],
    schedule_availability: [],
    subjects: null,
    level_education: [],
    certificates: [],
});

// Metodo de envio de datos al backend
const handlerStore = () => {
    if (photoInput.value && photoInput.value.files?.[0]) {
        form.profile_photo_path = photoInput.value.files[0];
    }
    form.post(route("instructor-requests.store"), {
        preserveScroll: true,
        forceFormData: true,
    });
};

// Metodo de envio de datos al backend (Segundo form)
const handlerStoreInstructor = () => {
    isLoading.value = true;
    instructorForm
        .transform((data) => ({
            ...data,
            _method: "PUT",
        }))
        .post(
            route("instructor-requests.update", props.instructorRequest?.id),
            {
                preserveScroll: true,
                forceFormData: true,
                onBefore: () => {
                    console.log("Antes de enviar...");
                },
                onSuccess: () => {
                    secondSubmitted.value = true;
                },
                onFinish: () => {
                    isLoading.value = false;
                },
            }
        );
};

// Options de Select de "Nivel academico"
const academic_levels = [
    { value: "universitario", label: "Universitario" },
    { value: "bachillerato", label: "Bachillerato" },
    { value: "tercer_ciclo", label: "Tercer ciclo" },
];

// Options de areas de especializacion
const specialtyOptions = ref([
    { name: "Tecnologia", value: "tecnologia" },
    { name: "Ciencias e investigación", value: "ciencias e investigacion" },
    { name: "Programación", value: "programacion" },
    { name: "Matemáticas", value: "matematicas" },
]);

// Options de ensenianza destinada a
const education_to = ref([
    { name: "Educación Tercer ciclo", value: "tercer ciclo" },
    { name: "Educación Universitaria", value: "universitaria" },
    { name: "Educación Bachillerato", value: "bachillerato" },
]);

// Options de horarios
const schedules = ref([
    { name: "Domingo", value: "domingo" },
    { name: "Lunes", value: "lunes" },
    { name: "Martes", value: "martes" },
    { name: "Miercoles", value: "miercoles" },
    { name: "Jueves", value: "jueves" },
    { name: "Viernes", value: "viernes" },
    { name: "Sabado", value: "sabado" },
]);
</script>

<template>
    <AppLayout
        :title="'Postulación de instructor'"
        subtitle="Inicia a continuación tu proceso como nuevo instructor en EduNova"
        :disabled-options="true"
    >
        <!-- Mensaje de exito -->
        <div v-if="props.instructorRequest?.email && !props.instructorRequest?.academic_level" class="mx-auto mb-4">
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
                            ¡Información guardada exitosamente!
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="props.instructorRequest?.academic_level"
            class="mx-auto mb-4"
        >
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
                            ¡Solicitud enviada exitosamente!
                        </h3>
                        <p class="mt-1 text-green-700">
                            Tu aplicación para convertirte en instructor ha sido
                            recibida. Revisaremos tu información y te
                            contactaremos pronto.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div
            v-if="isLoading"
            class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-90 backdrop-blur-sm"
        >
            <div class="text-center">
                <Loading class="mx-auto mb-6" color="blue" size="large" />
                <p class="mb-2 text-xl font-semibold text-gray-900">
                    Procesando solicitud...
                </p>
                <p class="text-sm text-gray-600">
                    Por favor espera mientras validamos tu información
                </p>
            </div>
        </div>

        <!-- ==================== Resumen de primer form ==================== -->
        <div v-if="props.instructorRequest" class="w-full pt-5">
            <div
                class="w-full mx-auto bg-white rounded-2xl ring-1 ring-gray-100"
            >
                <Disclosure v-slot="{ open }" :default-open="true">
                    <DisclosureButton
                        class="flex items-center justify-between w-full px-5 py-3 text-sm font-medium text-left text-purple-900 rounded-xl bg-purple-50 hover:bg-purple-100 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75"
                    >
                        <div class="flex items-center gap-2">
                            <span>Información personal enviada</span>
                            <span
                                v-if="createdAt"
                                class="text-xs text-purple-600"
                                >· {{ createdAt }}</span
                            >
                        </div>
                        <ChevronUpIcon
                            :class="open ? 'rotate-180 transform' : ''"
                            class="w-5 h-5 text-purple-500"
                        />
                    </DisclosureButton>

                    <DisclosurePanel class="px-5 pt-5 pb-4">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Avatar -->
                            <div class="flex flex-col items-center gap-3">
                                <img
                                    :src="photoPreview || savedPhotoUrl"
                                    alt="Foto de perfil"
                                    class="object-cover rounded-full shadow size-40 ring-1 ring-gray-200"
                                />
                                <p class="text-xs text-gray-500">
                                    Foto de perfil
                                </p>
                            </div>

                            <!-- Datos -->
                            <div class="md:col-span-2">
                                <div
                                    class="grid grid-cols-1 gap-4 sm:grid-cols-2"
                                >
                                    <div class="p-3 rounded-lg bg-gray-50">
                                        <p class="text-xs text-gray-500">
                                            Nombre completo
                                        </p>
                                        <p
                                            class="mt-0.5 font-medium text-gray-900"
                                        >
                                            {{
                                                pretty(
                                                    props.instructorRequest.name
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div class="p-3 rounded-lg bg-gray-50">
                                        <p class="text-xs text-gray-500">
                                            Correo electrónico
                                        </p>
                                        <p
                                            class="mt-0.5 break-words font-medium text-gray-900"
                                        >
                                            {{
                                                pretty(
                                                    props.instructorRequest
                                                        .email
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div class="p-3 rounded-lg bg-gray-50">
                                        <p class="text-xs text-gray-500">
                                            Teléfono
                                        </p>
                                        <p
                                            class="mt-0.5 font-medium text-gray-900"
                                        >
                                            {{
                                                pretty(
                                                    props.instructorRequest
                                                        .phone
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <div
                                        class="p-3 rounded-lg sm:col-span-2 bg-gray-50"
                                    >
                                        <p class="text-xs text-gray-500">
                                            Biografía
                                        </p>
                                        <p class="mt-0.5 text-gray-800">
                                            {{
                                                pretty(
                                                    props.instructorRequest
                                                        .biography
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </DisclosurePanel>
                </Disclosure>

                <!-- ==================== Segundo form ==================== -->
                <div v-show="!props.instructorRequest?.academic_level">
                    <Disclosure v-slot="{ open }" :default-open="true">
                        <DisclosureButton
                            class="flex items-center justify-between w-full px-5 py-3 mt-3 text-sm font-medium text-left text-purple-900 rounded-xl bg-purple-50 hover:bg-purple-100 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75"
                        >
                            <div class="flex items-center gap-2">
                                <span>Información personal enviada</span>
                            </div>
                            <ChevronUpIcon
                                :class="open ? 'rotate-180 transform' : ''"
                                class="w-5 h-5 text-purple-500"
                            />
                        </DisclosureButton>

                        <DisclosurePanel class="pt-2">
                            <div class="">
                                <DoubleFormSection
                                    @submitted="handlerStoreInstructor"
                                    left-title="Perfil académico y profesional"
                                    left-subtitle="Formación del tutor"
                                    right-title="Preferencias"
                                    right-subtitle="Datos adicionales relevantes del tutor"
                                >
                                    <!-- Lado izquierdo -->
                                    <template #left-form>
                                        <!-- Nivel academico alcanzado -->
                                        <div class="col-span-6 sm:col-span-6">
                                            <InputLabel
                                                value="Nivel académico"
                                                class="mb-1 text-white"
                                            />
                                            <Select
                                                :options="academic_levels"
                                                v-model="
                                                    instructorForm.academic_level
                                                "
                                                label="label"
                                                track-by="value"
                                                class="text-black"
                                            ></Select>
                                            <InputError
                                                :message="
                                                    instructorForm.errors
                                                        .academic_level
                                                "
                                                class="mt-2"
                                            />
                                        </div>

                                        <!-- Areas de especializacion -->
                                        <div class="col-span-6 sm:col-span-6">
                                            <InputLabel
                                                value="Areas de especialización"
                                                class="mb-1 text-white"
                                            />
                                            <Select
                                                :options="specialtyOptions"
                                                v-model="
                                                    instructorForm.subject_specialties
                                                "
                                                mode="tags"
                                                :multiple="true"
                                                :close-on-select="false"
                                                label="name"
                                                track-by="value"
                                                class="text-black"
                                            ></Select>
                                            <InputError
                                                :message="
                                                    instructorForm.errors
                                                        .subject_specialties
                                                "
                                                class="mt-2"
                                            />
                                        </div>

                                        <!-- Certificaciones y titulos -->
                                        <div class="col-span-6 sm:col-span-6">
                                            <InputLabel
                                                value="Certificaciones y títulos"
                                                class="mb-2 text-white"
                                            />
                                            <FileUpload
                                                v-model="
                                                    instructorForm.certificates
                                                "
                                                :max-files="5"
                                                :max-size="2048"
                                                accept="image/*"
                                                label="Subir certificaciones"
                                                description="Sube tus títulos, certificados y diplomas (JPG, PNG, JPEG - máx. 2MB c/u)"
                                            />
                                            <InputError
                                                :message="
                                                    instructorForm.errors
                                                        .certificates
                                                "
                                                class="mt-2 text-red-300"
                                            />
                                        </div>
                                    </template>

                                    <!-- Lado derecho (fondo blanco) -->
                                    <template #right-form>
                                        <!-- Disponibilidad de horarios -->
                                        <div class="col-span-6 sm:col-span-6">
                                            <InputLabel
                                                value="Disponibilidad de horario"
                                                class="mb-1"
                                            />
                                            <p
                                                class="mb-1 text-sm text-gray-500"
                                            >
                                                Selecciona tu disponibilidad
                                                semanal para impartir
                                                instructorias o atender
                                                consultas. Una vez aprobada tu
                                                solicitud, podrás establecer los
                                                horarios detallados para cada
                                                día.
                                            </p>
                                            <Select
                                                :options="schedules"
                                                v-model="
                                                    instructorForm.schedule_availability
                                                "
                                                mode="tags"
                                                :multiple="true"
                                                :close-on-select="false"
                                                label="name"
                                                track-by="value"
                                                class="text-black"
                                            ></Select>
                                            <InputError
                                                :message="
                                                    instructorForm.errors
                                                        .schedule_availability
                                                "
                                                class="mt-2"
                                            />
                                        </div>

                                        <!-- Materias a impartir -->
                                        <div class="col-span-6 sm:col-span-6">
                                            <InputLabel
                                                for="biography"
                                                value="Materias que imparte"
                                            />
                                            <Textarea
                                                id="biography"
                                                v-model="
                                                    instructorForm.subjects
                                                "
                                                class="block w-full mt-1"
                                                rows="4"
                                                placeholder="Detalla las materias y temas específicos que te sientes capacitado para enseñar..."
                                            />
                                            <InputError
                                                :message="
                                                    instructorForm.errors
                                                        .subjects
                                                "
                                                class="mt-2"
                                            />
                                        </div>

                                        <!-- Enseñanza destinada a -->
                                        <div class="col-span-6 sm:col-span-6">
                                            <InputLabel
                                                value="Enseñanza destinada a"
                                                class="mb-1"
                                            />
                                            <Select
                                                :options="education_to"
                                                v-model="
                                                    instructorForm.level_education
                                                "
                                                mode="tags"
                                                :multiple="true"
                                                :close-on-select="false"
                                                label="name"
                                                track-by="value"
                                                class="text-black"
                                            ></Select>
                                            <InputError
                                                :message="
                                                    instructorForm.errors
                                                        .level_education
                                                "
                                                class="mt-2"
                                            />
                                        </div>
                                    </template>

                                    <!-- Acciones del formulario -->
                                    <template #actions>
                                        <button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <svg
                                                v-if="form.processing"
                                                class="w-4 h-4 mr-3 -ml-1 text-white animate-spin"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                            >
                                                <circle
                                                    class="opacity-25"
                                                    cx="12"
                                                    cy="12"
                                                    r="10"
                                                    stroke="currentColor"
                                                    stroke-width="4"
                                                ></circle>
                                                <path
                                                    class="opacity-75"
                                                    fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                ></path>
                                            </svg>

                                            <span v-if="form.processing"
                                                >Enviando solicitud...</span
                                            >
                                            <span v-else>Enviar Solicitud</span>
                                        </button>
                                    </template>
                                </DoubleFormSection>
                            </div>
                        </DisclosurePanel>
                    </Disclosure>
                </div>

                <!-- ==================== Resumen del segundo form ==================== -->
                <Disclosure
                    v-if="props.instructorRequest?.academic_level"
                    v-slot="{ open }"
                    :default-open="true"
                    as="div"
                    class="mt-3"
                >
                    <DisclosureButton
                        class="flex items-center justify-between w-full px-5 py-3 text-sm font-medium text-left text-purple-900 rounded-xl bg-purple-50 hover:bg-purple-100 focus:outline-none focus-visible:ring focus-visible:ring-purple-500/75"
                    >
                        <div class="flex items-center gap-2">
                            <span
                                >Resumen de perfil académico y
                                preferencias</span
                            >
                            <span class="text-xs text-purple-600"
                                >· confirmado</span
                            >
                        </div>
                        <ChevronUpIcon
                            :class="open ? 'rotate-180 transform' : ''"
                            class="w-5 h-5 text-purple-500"
                        />
                    </DisclosureButton>

                    <DisclosurePanel class="px-5 pt-5 pb-4">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <!-- Nivel académico -->
                            <div class="p-3 rounded-lg bg-gray-50">
                                <p class="text-xs text-gray-500">
                                    Nivel académico
                                </p>
                                <p class="mt-0.5 font-medium text-gray-900">
                                    {{
                                        props.instructorRequest
                                            ?.academic_level || "—"
                                    }}
                                </p>
                            </div>

                            <!-- Disponibilidad -->
                            <div class="p-3 rounded-lg bg-gray-50">
                                <p class="text-xs text-gray-500">
                                    Disponibilidad
                                </p>
                                <div class="mt-1 flex flex-wrap gap-1.5">
                                    <span
                                        v-for="(d, i) in Array.isArray(
                                            props.instructorRequest
                                                ?.schedule_availability
                                        )
                                            ? props.instructorRequest
                                                  .schedule_availability
                                            : []"
                                        :key="'sc-' + i"
                                        class="px-2 py-0.5 text-xs rounded-full bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200"
                                        >{{ d }}</span
                                    >
                                    <span
                                        v-if="
                                            !Array.isArray(
                                                props.instructorRequest
                                                    ?.schedule_availability
                                            ) ||
                                            props.instructorRequest
                                                .schedule_availability
                                                .length === 0
                                        "
                                        >—</span
                                    >
                                </div>
                            </div>

                            <!-- Áreas de especialización -->
                            <div
                                class="p-3 rounded-lg bg-gray-50 sm:col-span-2"
                            >
                                <p class="text-xs text-gray-500">
                                    Áreas de especialización
                                </p>
                                <div class="mt-1 flex flex-wrap gap-1.5">
                                    <span
                                        v-for="(s, i) in Array.isArray(
                                            props.instructorRequest
                                                ?.subject_specialties
                                        )
                                            ? props.instructorRequest
                                                  .subject_specialties
                                            : []"
                                        :key="'sp-' + i"
                                        class="px-2 py-0.5 text-xs rounded-full bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200"
                                        >{{ s }}</span
                                    >
                                    <span
                                        v-if="
                                            !Array.isArray(
                                                props.instructorRequest
                                                    ?.subject_specialties
                                            ) ||
                                            props.instructorRequest
                                                .subject_specialties.length ===
                                                0
                                        "
                                        >—</span
                                    >
                                </div>
                            </div>

                            <!-- Materias que imparte -->
                            <div
                                class="p-3 rounded-lg bg-gray-50 sm:col-span-2"
                            >
                                <p class="text-xs text-gray-500">
                                    Materias que imparte
                                </p>
                                <p
                                    class="mt-0.5 text-gray-800 whitespace-pre-line"
                                >
                                    {{
                                        pretty(
                                            props.instructorRequest?.subjects
                                        )
                                    }}
                                </p>
                            </div>

                            <!-- Enseñanza destinada a -->
                            <div
                                class="p-3 rounded-lg bg-gray-50 sm:col-span-2"
                            >
                                <p class="text-xs text-gray-500">
                                    Enseñanza destinada a
                                </p>
                                <div class="mt-1 flex flex-wrap gap-1.5">
                                    <span
                                        v-for="(lvl, i) in Array.isArray(
                                            props.instructorRequest
                                                ?.level_education
                                        )
                                            ? props.instructorRequest
                                                  .level_education
                                            : []"
                                        :key="'lv-' + i"
                                        class="px-2 py-0.5 text-xs rounded-full bg-orange-50 text-orange-700 ring-1 ring-orange-200"
                                        >{{ lvl }}</span
                                    >
                                    <span
                                        v-if="
                                            !Array.isArray(
                                                props.instructorRequest
                                                    ?.level_education
                                            ) ||
                                            props.instructorRequest
                                                .level_education.length === 0
                                        "
                                        >—</span
                                    >
                                </div>
                            </div>

                            <!-- Certificaciones -->
                            <div
                                class="p-3 rounded-lg bg-gray-50 sm:col-span-2"
                            >
                                <p class="text-xs text-gray-500">
                                    Certificaciones y títulos
                                </p>
                                <ul
                                    class="mt-1 list-disc list-inside text-gray-800 space-y-0.5"
                                >
                                    <li
                                        v-for="(c, i) in Array.isArray(
                                            props.instructorRequest
                                                ?.certificates
                                        )
                                            ? props.instructorRequest
                                                  .certificates
                                            : []"
                                        :key="'cf-' + i"
                                    >
                                        <!-- Soporta tanto string como objeto { name, url } -->
                                        <template v-if="typeof c === 'string'">
                                            {{ c }}
                                        </template>
                                        <template v-else>
                                            <a
                                                :href="c.url"
                                                target="_blank"
                                                rel="noopener"
                                                class="underline hover:no-underline"
                                            >
                                                {{
                                                    c.name ||
                                                    c.file_name ||
                                                    "certificado"
                                                }}
                                            </a>
                                        </template>
                                    </li>
                                    <li
                                        v-if="
                                            !Array.isArray(
                                                props.instructorRequest
                                                    ?.certificates
                                            ) ||
                                            props.instructorRequest.certificates
                                                .length === 0
                                        "
                                    >
                                        —
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </DisclosurePanel>
                </Disclosure>
            </div>
        </div>

        <!-- ==================== FORM de informacion personal ==================== -->
        <FormSection @submitted="handlerStore" v-else>
            <template #description>
                <!-- Profile Photo -->
                <div class="flex flex-col items-center">
                    <!-- File input -->
                    <input
                        id="photo"
                        ref="photoInput"
                        type="file"
                        class="hidden"
                        accept="image/*"
                        @change="updatePhotoPreview"
                    />
                    <InputLabel for="photo" value="Foto de pérfil" />

                    <!-- Current Profile Photo (preview o default) -->
                    <div v-show="!photoPreview" class="flex items-center mt-2">
                        <img
                            :src="savedPhotoUrl"
                            alt="Foto actual"
                            class="object-cover rounded-full shadow-xl size-48"
                        />
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div v-show="photoPreview" class="mt-2">
                        <span
                            class="block bg-center bg-no-repeat bg-cover rounded-full size-48"
                            :style="`background-image: url('${photoPreview}')`"
                        />
                    </div>

                    <SecondaryButton
                        class="mt-7 me-2"
                        type="button"
                        @click.prevent="selectNewPhoto"
                    >
                        Seleccionar foto
                    </SecondaryButton>

                    <InputError
                        :message="form.errors.profile_photo_path"
                        class="mt-2"
                    />
                </div>
            </template>

            <template #form>
                <div class="col-span-6">
                    <p class="text-xl font-medium">Información personal</p>
                    <p class="text-gray-500 text-md">Presentación</p>
                </div>

                <!-- Nombre -->
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="name" value="Nombre completo" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="block w-full mt-1"
                        autocomplete="name"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Correo -->
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="email" value="Correo electrónico" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="block w-full mt-1"
                        autocomplete="email"
                    />
                    <InputError :message="form.errors.email" class="mt-2" />
                </div>

                <!-- Teléfono -->
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel for="phone" value="Teléfono (Opcional)" />
                    <TextInput
                        id="phone"
                        v-model="form.phone"
                        type="text"
                        placeholder="+503 25005700"
                        class="block w-full mt-1"
                        autocomplete="tel"
                    />
                    <InputError :message="form.errors.phone" class="mt-2" />
                </div>

                <!-- Biografía -->
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="biography" value="Biografía" />
                    <Textarea
                        id="biography"
                        v-model="form.biography"
                        class="block w-full mt-1"
                        rows="5"
                        placeholder="Cuéntanos sobre tu experiencia educativa, logros y por qué quieres ser instructor..."
                    />
                    <InputError :message="form.errors.biography" class="mt-2" />
                </div>
            </template>

            <template #actions>
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    :loading="form.processing"
                >
                    Guardar
                </PrimaryButton>
            </template>
        </FormSection>
    </AppLayout>
</template>
