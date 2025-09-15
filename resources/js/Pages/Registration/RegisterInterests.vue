<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import FormSection from "@/Components/FormSection.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Select from "@/Oak/Select.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/vue3";
import InterestsRegister from "@/Components/InterestsRegister.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref, computed } from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Steps from "@/Oak/Steps.vue";
import moment from "moment";
import Loading from "@/Oak/Loading.vue";
import {
    CodeBracketIcon,
    PaintBrushIcon,
    ComputerDesktopIcon,
    CalculatorIcon,
    BeakerIcon,
    CogIcon,
} from "@heroicons/vue/24/solid";

const props = defineProps(["disabled-options", "user"]);

const isLoading = ref(false);

// useForm para enviar los datos
const form = useForm({
    interests: [],
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
        status: "Completado",
        message: "Completa tu información como estudiante",
        updated_at: "Pendiente",
    },
    {
        id: 3,
        order: 3,
        name: "Intereses",
        status: "En progreso",
        message: "Marca tus temas y cursos de interes en EduNova",
        updated_at: "Pendiente",
    },
]);

const availableInterests = ref([
    {
        id: "programacion",
        icon: CodeBracketIcon,
        title: "Programación",
        description: "Desarrollo web, móvil y software",
    },
    {
        id: "diseno",
        icon: PaintBrushIcon,
        title: "Diseño",
        description: "UI/UX, gráfico y digital",
    },
    {
        id: "informatica",
        icon: ComputerDesktopIcon,
        title: "Informática",
        description: "Componentes y mantenimiento",
    },
    {
        id: "matematicas",
        icon: CalculatorIcon,
        title: "Matemáticas",
        description: "Álgebra, estadística y operaciones",
    },
    {
        id: "fisica",
        icon: BeakerIcon,
        title: "Física",
        description: "Fundamentos, leyes y demostraciones",
    },
    {
        id: "robotica",
        icon: CogIcon,
        title: "Robótica y automatización",
        description: "Principios, arduino y practica",
    },
]);

// Función para manejar la selección de intereses
const toggleInterest = (interestId) => {
    const index = form.interests.indexOf(interestId);
    if (index > -1) {
        form.interests.splice(index, 1);
    } else {
        form.interests.push(interestId);
    }
};

// Verificar si un interés está seleccionado
const isSelected = (interestId) => {
    return form.interests.includes(interestId);
};

// Envio de datos
const handlerInterests = () => {
    form.put(route("registration.interests-info"));
};
</script>
<template>
    <AppLayout
        title="¡Bienvenido a EduNova!"
        subtitle="Completa tu perfil para acceder a cursos increíbles y comenzar tu viaje de aprendizaje personalizado."
        :disabled-options="true"
    >
        <div class="py-12">
            <FormSection @submitted="handlerInterests()">
                <template #title> Completa tu perfil de estudiante </template>
                <template #description>
                    Cuéntanos sobre tus áreas de interés académico para
                    recomendarte cursos y contenido personalizado que se adapte
                    a tus objetivos de aprendizaje.
                    <div class="mt-7">
                        <Steps
                            :loggers="loggers"
                            campaign="Registro Estudiantil"
                        />
                    </div>
                </template>
                <template #form>
                    <!-- Intereses -->
                    <div class="col-span-6">
                        <InputLabel
                            for="interests"
                            value="Selecciona tus áreas de interés"
                        />
                        <div class="grid grid-cols-1 gap-4 mt-3 md:grid-cols-2">
                            <InterestsRegister
                                v-for="interest in availableInterests"
                                :key="interest.id"
                                :icon="interest.icon"
                                :title="interest.title"
                                :description="interest.description"
                                :selected="isSelected(interest.id)"
                                :selectable="true"
                                size="md"
                                @click="toggleInterest(interest.id)"
                            />
                        </div>
                        <InputError
                            :message="form.errors.interests"
                            class="mt-2"
                        />
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
        </div>
    </AppLayout>
</template>
