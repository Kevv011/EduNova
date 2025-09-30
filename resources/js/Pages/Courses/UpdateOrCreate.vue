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
const props = defineProps({
    categories: Object,
    course: Object,
    instructor: Object,
});

// Normalizacion
const courseData = computed(() => props.course?.data ?? props.course ?? null);
const instructorData = computed(
    () => props.instructor?.data ?? props.instructor ?? null
);
const categoriesData = computed(
    () => props.categories?.data ?? props.categories ?? []
);
const courseId = computed(() => courseData.value?.id ?? null);

// UseForm para envio de datos al backend
const form = useForm({
    name: courseData.value?.name ?? null,
    description: courseData.value?.description ?? null,
    category_id: courseData.value?.category?.id ?? null,
    areas: courseData.value?.areas ?? [],
    image: null,
    instructor_id: instructorData.value?.id ?? null,
});

// Foto de curso
const photoPreview = ref(null);
const photoInput = ref(null);

const updatePhotoPreview = () => {
    const file = photoInput.value?.files?.[0];
    if (!file) return;

    // preview
    const reader = new FileReader();
    reader.onload = (e) => (photoPreview.value = e.target.result);
    reader.readAsDataURL(file);

    form.image = file;
};
const selectNewPhoto = () => photoInput.value?.click();

// URL final de la imagen guardada
const savedPhotoUrl = computed(() => {
    return props.course?.data.image ?? "/images/user-default.jpeg";
});

// Categories traidas del backend
const CategoryOptions = computed(() => props.categories.data);

// Metodo de envio de datos al backend
const handlerStoreCourse = () => {
    form.post(route("courses.store"), {
        preserveScroll: true,
        forceFormData: true,
    });
};

const handlerUpdateCourse = () => {
    form.transform((data) => {
        const payload = { ...data, _method: "PUT" };
        if (!(data.image instanceof File)) delete payload.image;
        return payload;
    }).post(route("courses.update", courseId.value), {
        preserveScroll: true,
        forceFormData: true,
        onError: (e) => console.log("Errores:", e),
        onSuccess: () => console.log("Actualización exitosa"),
    });
};

const academicAreas = ref([
    { name: "Programación", value: "programacion" },
    { name: "Aritmetica", value: "aritmetica" },
    { name: "Algoritmos", value: "algoritmos" },
    { name: "Fisica del movimiento", value: "fisica del movimiento" },
]);
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

        <!-- <pre>{{ instructor.data.id }}</pre> -->

        <!-- Formulario -->
        <DoubleFormSection
            @submitted="
                props.course ? handlerUpdateCourse() : handlerStoreCourse()
            "
            left-title="Información general del curso"
            left-subtitle="Completa los datos principales para describir y presentar tu nuevo curso a los estudiantes."
            right-title="Detalles académicos"
            right-subtitle="Indica claramente las expectativas y requisitos académicos para asegurar una experiencia educativa óptima."
        >
            <!-- Lado izquierdo -->
            <template #left-form>
                <!-- Foto del curso -->
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
                    <InputLabel
                        for="photo"
                        value="Foto del curso"
                        class="text-white"
                    />

                    <!-- Foto actual -->
                    <div v-show="!photoPreview" class="flex items-center mt-2">
                        <img
                            :src="savedPhotoUrl"
                            alt="Foto actual"
                            class="object-cover rounded-lg shadow-xl size-48"
                        />
                    </div>

                    <!-- Nueva foto del curso -->
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

                    <InputError :message="form.errors.image" class="mt-2" />
                </div>

                <!-- Nombre del curso -->
                <div class="col-span-6 sm:col-span-3">
                    <InputLabel
                        for="name"
                        value="Nombre del curso"
                        class="text-white"
                    />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="block w-full mt-1 text-black"
                        autocomplete="name"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Categoria -->
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel
                        value="Categoria principal"
                        class="mb-1 text-white"
                    />
                    <Select
                        :options="categoriesData"
                        v-model="form.category_id"
                        valueProp="id"
                        label="name"
                        :searchable="true"
                        placeholder="Seleccionar opción..."
                        class="text-dark"
                    />
                    <InputError
                        :message="form.errors.category_id"
                        class="mt-2"
                    />
                </div>
            </template>

            <!-- Lado derecho (fondo blanco) -->
            <template #right-form>
                <!-- Descripcion -->
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel
                        for="description"
                        value="Descripción del curso"
                    />
                    <Textarea
                        id="description"
                        v-model="form.description"
                        class="block w-full mt-1"
                        rows="4"
                        placeholder="Proporciona una descripción clara y concisa sobre este nuevo curso..."
                    />
                    <InputError
                        :message="form.errors.description"
                        class="mt-2"
                    />
                </div>

                <!-- Areas academicas -->
                <div class="col-span-6 sm:col-span-6">
                    <InputLabel value="Areas academicas" class="mb-1" />
                    <p class="my-3 text-sm text-gray-500">
                        Selecciona cuidadosamente las áreas académicas que este
                        curso abordará. Sé preciso y claro, ya que esta
                        información será utilizada para recomendar el curso a
                        los estudiantes más adecuados. Recuerda que una
                        selección correcta ayuda a mejorar la experiencia
                        educativa y facilita que tu curso llegue a quienes
                        realmente lo necesitan.
                    </p>
                    <Select
                        :options="academicAreas"
                        v-model="form.areas"
                        mode="tags"
                        :multiple="true"
                        :close-on-select="false"
                        label="name"
                        track-by="value"
                        :createTag="true"
                        class="text-black"
                    />
                    <div class="mt-1 text-sm text-gray-500">
                        <p>
                            Si deseas colocar una nueva area academica,
                            escribela y presiona "ENTER" para adicionarla.
                        </p>
                    </div>
                    <InputError :message="form.errors.areas" class="mt-2" />
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

                    <span v-if="form.processing">Enviando solicitud...</span>
                    <span v-else>{{
                        props.course ? "Actualizar" : "Enviar Solicitud"
                    }}</span>
                </button>
            </template>
        </DoubleFormSection>
    </AppLayout>
</template>
