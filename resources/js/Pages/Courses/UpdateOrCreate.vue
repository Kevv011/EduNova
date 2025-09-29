<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DoubleFormSection from "@/Components/DoubleFormSection.vue";
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
import AlertWithDescription from "@/Oak/Alerts/WithDescription.vue";

// Props
const props = defineProps("course");

// UseForm para envio de datos al backend
const instructorForm = useForm({
    academic_level: null,
    subject_specialties: [],
    schedule_availability: [],
    subjects: null,
    level_education: [],
    certificates: [],
});

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
</script>
<template>
    <AppLayout
        :title="!props?.course ? 'Crear curso' : 'Editar curso'"
        :subtitle="
            !props?.course
                ? 'Crea un nuevo curso para tus estudiantes. Visualiza el aprendizaje hacia contenidos innovadores'
                : 'Actualiza la información actual de tu curso'
        "
    >
        <AlertWithDescription
            title="¡Aviso importante!"
            class="my-5"
            v-if="!props?.course"
        >
            Antes de enviar tu solicitud de publicación, asegúrate de que el
            curso cumple con los estándares de calidad y respeto de EduNova. Asi
            mismo, recuerda que los contenidos deben ser educativos, apropiados
            y fomentar un ambiente positivo para los estudiantes.
            <br /><br />
            Nuestro equipo revisará cada solicitud y podrá rechazar aquellas que
            no cumplan con estas normas. ¡Contribuye a una comunidad educativa
            sana y enriquecedora!
        </AlertWithDescription>

        <!-- Formulario -->
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
                        v-model="instructorForm.academic_level"
                        label="label"
                        track-by="value"
                        class="text-black"
                    ></Select>
                    <InputError
                        :message="instructorForm.errors.academic_level"
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
                        v-model="instructorForm.subject_specialties"
                        mode="tags"
                        :multiple="true"
                        :close-on-select="false"
                        label="name"
                        track-by="value"
                        class="text-black"
                    ></Select>
                    <InputError
                        :message="instructorForm.errors.subject_specialties"
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
                        v-model="instructorForm.certificates"
                        :max-files="5"
                        :max-size="2048"
                        accept="image/*"
                        label="Subir certificaciones"
                        description="Sube tus títulos, certificados y diplomas (JPG, PNG, JPEG - máx. 2MB c/u)"
                    />
                    <InputError
                        :message="instructorForm.errors.certificates"
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
                    <p class="mb-1 text-sm text-gray-500">
                        Selecciona tu disponibilidad semanal para impartir
                        instructorias o atender consultas. Una vez aprobada tu
                        solicitud, podrás establecer los horarios detallados
                        para cada día.
                    </p>
                    <Select
                        :options="schedules"
                        v-model="instructorForm.schedule_availability"
                        mode="tags"
                        :multiple="true"
                        :close-on-select="false"
                        label="name"
                        track-by="value"
                        class="text-black"
                    ></Select>
                    <InputError
                        :message="instructorForm.errors.schedule_availability"
                        class="mt-2"
                    />
                </div>

                <!-- Materias a impartir -->
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel for="biography" value="Materias que imparte" />
                    <Textarea
                        id="biography"
                        v-model="instructorForm.subjects"
                        class="block w-full mt-1"
                        rows="4"
                        placeholder="Detalla las materias y temas específicos que te sientes capacitado para enseñar..."
                    />
                    <InputError
                        :message="instructorForm.errors.subjects"
                        class="mt-2"
                    />
                </div>

                <!-- Enseñanza destinada a -->
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel value="Enseñanza destinada a" class="mb-1" />
                    <Select
                        :options="education_to"
                        v-model="instructorForm.level_education"
                        mode="tags"
                        :multiple="true"
                        :close-on-select="false"
                        label="name"
                        track-by="value"
                        class="text-black"
                    ></Select>
                    <InputError
                        :message="instructorForm.errors.level_education"
                        class="mt-2"
                    />
                </div>
            </template>

            <!-- Acciones del formulario -->
            <template #actions>
                <button
                    type="submit"
                    :disabled="instructorForm.processing"
                    class="inline-flex items-center justify-center px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg
                        v-if="instructorForm.processing"
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

                    <span v-if="instructorForm.processing"
                        >Enviando solicitud...</span
                    >
                    <span v-else>Enviar Solicitud</span>
                </button>
            </template>
        </DoubleFormSection>
    </AppLayout>
</template>
