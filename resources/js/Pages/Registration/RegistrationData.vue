<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import FormSection from "@/Components/FormSection.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Select from "@/Oak/Select.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, computed } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Steps from "@/Oak/Steps.vue";
import moment from "moment";
import Loading from "@/Oak/Loading.vue";

const props = defineProps(["disabled-options", 'user']);

const isLoading = ref(false);

// useForm para enviar los datos
const form = useForm({
    academic_level: null,
    institution: null,
    date_birth: null,
    user_id: props.user.id,
});

// Pasos del componente STEPS
const loggers = ref([
    {
        id: 1,
        order: 1,
        name: "Datos Personales",
        status: "Completado",
        message: "Completa tu información básica y tu usuario",
        updated_at: "Pendiente",
    },
    {
        id: 2,
        order: 2,
        name: "Información Académica",
        status: "En progreso",
        message: "Completa tu información como estudiante",
        updated_at: "Pendiente",
    },
    {
        id: 3,
        order: 3,
        name: "Intereses",
        status: "Pendiente",
        message: "Marca tus temas y cursos de interes en EduNova",
        updated_at: "Pendiente",
    },
]);

// Options de Select de "Nivel academico"
const academic_levels = [
    { value: "universitario", label: "Universitario" },
    { value: "bachillerato", label: "Bachillerato" },
    { value: "tercer_ciclo", label: "Tercer ciclo" },
    { value: "autodidacta", label: "Estudiante autodidacta" },
];

// Envio de datos
const handlerStore = () => {
    form.post(route('registration.academic-info'));
};
</script>
<template>
    <AppLayout title="¡Bienvenido a EduNova!"
        subtitle="Completa tu perfil para acceder a cursos increíbles y comenzar tu viaje de aprendizaje personalizado."
        :disabled-options="true">
        <div class="py-12">
            <FormSection @submitted="handlerStore()">
                <template #title> Completa tu perfil de estudiante </template>
                <template #description>
                    Proporciona tu información personal para personalizar tu
                    experiencia de aprendizaje y recibir recomendaciones de
                    cursos adaptados a tu perfil.

                    <div class="mt-7">
                        <Steps :loggers="loggers" campaign="Registro Estudiantil" />
                    </div>
                </template>
                <template #form>
                    <!-- Nivel academico -->
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel value="Nivel académico" class="mb-1" />
                        <Select :options="academic_levels" v-model="form.academic_level" label="label"
                            track-by="value"></Select>
                        <InputError :message="form.errors.academic_level" class="mt-2" />
                    </div>

                    <!-- Institucion -->
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel for="name" value="Institución" />
                        <p class="text-sm text-gray-500">
                            Si no pertenece a una institución educativa, no
                            llenar este campo
                        </p>
                        <TextInput id="name" v-model="form.institution" type="text" class="block w-full mt-1"
                            autocomplete="name" />
                        <InputError :message="form.errors.institution" class="mt-2" />
                    </div>

                    <!-- Date birth -->
                    <div class="col-span-6 sm:col-span-6">
                        <InputLabel for="name" value="Fecha de nacimiento" />
                        <VueDatePicker v-model="form.date_birth" :clearable="true" auto-apply
                            :enable-time-picker="false" locale="es" format="dd/MM/yyyy" placeholder="Seleccionar fecha"
                            :max-date="moment()"></VueDatePicker>
                        <InputError :message="form.errors.date_birth" class="mt-2" />
                    </div>
                </template>
                <template #actions>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        :loading="form.processing">
                        Guardar
                    </PrimaryButton>
                </template>
            </FormSection>
        </div>
    </AppLayout>
</template>
