<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref, watch, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import { useForm } from "@inertiajs/vue3";
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
    TrashIcon,
} from "@heroicons/vue/24/outline";

// Props
const props = defineProps({
    module: { type: [Object, null], required: true },
});

const lessonModal = ref(false);
const editingLesson = ref(false);
const deleteLessonModal = ref(false);

// Normalización de datos
const moduleData = computed(() => props.module?.data ?? props.module ?? null);

// Lecciones normalizadas
const lessons = computed(() => {
    const raw = moduleData.value?.lessons ?? [];
    // Ajusta a tu shape real: position/number/title/etc.
    return [...raw].sort(
        (a, b) =>
            (a.position ?? a.number ?? a.id) - (b.position ?? b.number ?? b.id)
    );
});

// Estado: lección seleccionada (por id)
const selectedLessonId = ref(null);
const selectedLesson = computed(
    () => lessons.value.find((l) => l.id === selectedLessonId.value) ?? null
);

// Inicializa con la primera
const initSelection = () => {
    if (lessons.value.length && !selectedLessonId.value) {
        selectedLessonId.value = lessons.value[0].id;
    }
};
onMounted(initSelection);
watch(lessons, initSelection, { immediate: true });

// Helpers de navegación
const indexOfSelected = computed(() =>
    lessons.value.findIndex((l) => l.id === selectedLessonId.value)
);
const canPrev = computed(() => indexOfSelected.value > 0);
const canNext = computed(
    () =>
        indexOfSelected.value >= 0 &&
        indexOfSelected.value < lessons.value.length - 1
);
const goPrev = () => {
    if (canPrev.value)
        selectedLessonId.value = lessons.value[indexOfSelected.value - 1].id;
};
const goNext = () => {
    if (canNext.value)
        selectedLessonId.value = lessons.value[indexOfSelected.value + 1].id;
};

// Parse de recursos (ajusta a tu backend)
const resources = computed(() => {
    // Soporta varios formatos: selectedLesson.resources, resource_url, content_html, etc.
    const l = selectedLesson.value;
    if (!l) return [];
    // Ejemplos de shape esperado:
    // l.resources = [{ type: 'video'|'pdf'|'text', url, html, title }]
    // Si no tienes esta estructura aún, puedes mapearla acá.
    return Array.isArray(l.resources) ? l.resources : [];
});

// Detección simple de proveedor video
const isYouTube = (url) => /youtu\.?be/.test(url ?? "");
const isVimeo = (url) => /vimeo\.com/.test(url ?? "");

// Sanitizar HTML si lo renderizas (idealmente hazlo en el back o usa una lib)
const safeHtml = (html) => html ?? "";

// Manejo de modal de creacion de Lesson
const openLessonModal = () => {
    form.reset();
    editingLesson.value = null;
    form.module_id = moduleData.value.id;
    lessonModal.value = true;
};

const openEditLessonModal = (lesson) => {
    form.reset();

    form.title = lesson.title;
    form.module_id = moduleData.value.id;
    editingLesson.value = lesson;
    lessonModal.value = true;
};

const closeLessonModal = () => {
    lessonModal.value = false;
};

// Manejo del modal de Eliminacion
const openDeleteLessonModal = (lesson) => {
    editingLesson.value = lesson;
    deleteLessonModal.value = true;
};

const closeDeleteLessonModal = () => {
    deleteLessonModal.value = false;
};

// useForm para envio de datos al backend (LESSON)
const form = useForm({
    title: null,
    module_id: null,
});

const handlerStoreLesson = () => {
    form.post(route("lessons.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            lessonModal.value = false;

            if (lessons.value.length) {
                selectedLessonId.value =
                    lessons.value[lessons.value.length - 1].id;
            }
        },
    });
};

const handlerUpdateLesson = () => {
    form.put(route("lessons.update", editingLesson.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            lessonModal.value = false;
        },
    });
};

// Eliminar una leccion
const handlerDeleteLesson = () => {
    if (!editingLesson.value) return;
    form.delete(route("lessons.destroy", editingLesson.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            deleteLessonModal.value = false;
            editingLesson.value = null;

            if (lessons.value.length) {
                selectedLessonId.value =
                    lessons.value[lessons.value.length - 1].id;
            }
        },
    });
};
</script>

<template>
    <AppLayout
        :title="
            moduleData
                ? `Módulo ${moduleData.number_module ?? ''}: ${
                      moduleData.title
                  }`
                : 'Módulo'
        "
        subtitle="Explora las lecciones y sus recursos. A la derecha tienes el índice, a la izquierda el contenido."
        :pages="[
            {
                name: 'Mis cursos',
                href: route('courses.index'),
                current: false,
            },
            {
                name: moduleData?.course?.name ?? 'Curso',
                href: moduleData?.course?.id
                    ? route('courses.modules', moduleData.course.id)
                    : '#',
                current: false,
            },
            { name: 'Lecciones', href: '#', current: true },
        ]"
    >
        <!-- Layout 2 columnas: contenido (izq) + aside (der) -->
        <div class="grid gap-6 md:grid-cols-12">
            <!-- Contenido -->
            <section class="md:col-span-8 lg:col-span-9">
                <!-- Sin lecciones -->
                <div
                    v-if="!lessons.length"
                    class="flex justify-center p-8 bg-white border rounded-2xl"
                >
                    <img src="/images/emptyLesson.png" alt="" />
                </div>

                <!-- Lección activa -->
                <div
                    v-else-if="selectedLesson"
                    class="overflow-hidden bg-white border rounded-2xl"
                >
                    <!-- Header estilo Udemy -->
                    <div class="p-5 border-b">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p
                                    class="text-xs font-semibold tracking-wide text-gray-500 uppercase"
                                >
                                    Lección {{ indexOfSelected + 1 }} de
                                    {{ lessons.length }}
                                </p>
                                <h1
                                    class="mt-1 text-xl font-semibold text-gray-900 truncate"
                                    :title="selectedLesson.title"
                                >
                                    {{ selectedLesson.title ?? "Lección" }}
                                </h1>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    class="px-3 py-2 text-sm font-medium border rounded-lg disabled:opacity-40 hover:bg-gray-50"
                                    :disabled="!canPrev"
                                    @click="goPrev"
                                >
                                    Anterior
                                </button>
                                <button
                                    class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg disabled:opacity-40 hover:bg-indigo-700"
                                    :disabled="!canNext"
                                    @click="goNext"
                                >
                                    Siguiente
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Media principal (video si existe) -->
                    <div
                        v-if="resources.some((r) => r.type === 'video')"
                        class="bg-black aspect-video"
                    >
                        <template v-for="(r, i) in resources" :key="'vid-' + i">
                            <template v-if="r.type === 'video' && r.url">
                                <!-- YouTube/Vimeo embebido -->
                                <iframe
                                    v-if="isYouTube(r.url) || isVimeo(r.url)"
                                    class="w-full h-full"
                                    :src="
                                        isYouTube(r.url)
                                            ? r.url.replace(
                                                  'watch?v=',
                                                  'embed/'
                                              )
                                            : isVimeo(r.url)
                                            ? r.url.replace(
                                                  'vimeo.com',
                                                  'player.vimeo.com/video'
                                              )
                                            : r.url
                                    "
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen
                                />
                                <!-- MP4/WEBM nativo -->
                                <video
                                    v-else
                                    class="w-full h-full"
                                    controls
                                    :src="r.url"
                                ></video>
                            </template>
                        </template>
                    </div>

                    <!-- Body -->
                    <div class="p-5 space-y-6">
                        <!-- Texto/HTML -->
                        <div
                            v-if="
                                resources.some(
                                    (r) =>
                                        r.type === 'text' || r.type === 'html'
                                )
                            "
                        >
                            <h2
                                class="mb-2 text-sm font-semibold text-gray-700"
                            >
                                Contenido
                            </h2>
                            <div
                                v-for="(r, i) in resources"
                                :key="'txt-' + i"
                                v-if="r.type === 'text' || r.type === 'html'"
                                class="prose-sm prose max-w-none prose-p:leading-relaxed"
                                v-html="safeHtml(r.html ?? r.text)"
                            />
                        </div>

                        <!-- PDFs -->
                        <div
                            v-if="resources.some((r) => r.type === 'pdf')"
                            class="space-y-3"
                        >
                            <h2 class="text-sm font-semibold text-gray-700">
                                Recursos PDF
                            </h2>
                            <div
                                v-for="(r, i) in resources"
                                :key="'pdf-' + i"
                                v-if="r.type === 'pdf' && r.url"
                                class="overflow-hidden border rounded-lg"
                            >
                                <!-- Vista previa -->
                                <div class="aspect-[4/3] bg-gray-50">
                                    <embed
                                        :src="r.url"
                                        type="application/pdf"
                                        class="w-full h-full"
                                    />
                                </div>
                                <div
                                    class="flex items-center justify-between px-3 py-2 text-sm bg-gray-50"
                                >
                                    <span class="truncate">{{
                                        r.title ?? "Documento PDF"
                                    }}</span>
                                    <a
                                        :href="r.url"
                                        target="_blank"
                                        class="font-medium text-indigo-600 hover:text-indigo-700"
                                        >Abrir</a
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Otros archivos -->
                        <div
                            v-if="resources.some((r) => r.type === 'file')"
                            class="space-y-2"
                        >
                            <h2 class="text-sm font-semibold text-gray-700">
                                Archivos adjuntos
                            </h2>
                            <ul class="space-y-1">
                                <li
                                    v-for="(r, i) in resources"
                                    :key="'file-' + i"
                                    v-if="r.type === 'file' && r.url"
                                    class="flex items-center justify-between px-3 py-2 text-sm border rounded-lg"
                                >
                                    <span class="truncate">{{
                                        r.title ?? r.url
                                    }}</span>
                                    <a
                                        :href="r.url"
                                        target="_blank"
                                        class="font-medium text-indigo-600 hover:text-indigo-700"
                                        >Descargar</a
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Loading/placeholder si no hay seleccion aún pero hay lecciones -->
                <div v-else class="p-8 bg-white border rounded-2xl">
                    <div class="w-24 h-4 mb-3 bg-gray-200 rounded"></div>
                    <div class="w-full h-48 bg-gray-100 rounded"></div>
                </div>
            </section>

            <!-- Aside: índice de lecciones (derecha) -->
            <aside class="md:col-span-4 lg:col-span-3">
                <div class="sticky top-4">
                    <div class="p-4 bg-white border rounded-2xl">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-800">
                                Contenido del curso
                            </h3>
                            <span
                                class="px-2 py-0.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-full"
                            >
                                {{ lessons.length }} lección{{
                                    lessons.length === 1 ? "" : "es"
                                }}
                            </span>
                        </div>

                        <ul
                            class="flex flex-col justify-center divide-y divide-gray-100"
                        >
                            <li
                                v-for="(l, idx) in lessons"
                                :key="l.id"
                                class="py-2"
                            >
                                <button
                                    class="flex items-center justify-between w-full gap-3 px-2 py-2 text-left transition rounded-lg hover:bg-gray-50"
                                    :class="
                                        selectedLessonId === l.id
                                            ? 'bg-indigo-50 ring-1 ring-indigo-100'
                                            : ''
                                    "
                                    @click="selectedLessonId = l.id"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="inline-flex items-center justify-center w-6 h-6 mt-0.5 text-[11px] font-semibold rounded bg-indigo-100 text-indigo-700"
                                        >
                                            {{ idx + 1 }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="w-[10rem]">
                                                <p
                                                    class="text-sm font-medium text-gray-900 truncate max-w-1/2"
                                                    :title="l.title"
                                                >
                                                    {{ l.title ?? "Lección" }}
                                                </p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                <!-- Muestra meta si existe -->
                                                <span v-if="l.duration">{{
                                                    l.duration
                                                }}</span>
                                                <span
                                                    v-if="l.duration && l.type"
                                                    class="mx-1"
                                                    >•</span
                                                >
                                                <span v-if="l.type">{{
                                                    l.type
                                                }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="space-x-2">
                                        <PrimaryButton
                                            color="lightblue"
                                            v-tooltip="'Editar'"
                                            @click="openEditLessonModal(l)"
                                            @click.stop="openEditLessonModal(l)"
                                        >
                                            <PencilIcon class="size-4" />
                                        </PrimaryButton>
                                        <PrimaryButton
                                            color="red"
                                            @click.stop="
                                                openDeleteLessonModal(l)
                                            "
                                        >
                                            <TrashIcon class="size-4" />
                                        </PrimaryButton>
                                    </div>
                                </button>
                            </li>
                            <SecondaryButton
                                color="lightblue"
                                class="flex justify-center"
                                @click="openLessonModal"
                            >
                                <PlusIcon class="size-4 me-2" />
                                Agregar lección
                            </SecondaryButton>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>

        <!-- DEBUG opcional -->
        <pre class="mt-6 text-xs">{{
            JSON.stringify(moduleData, null, 2)
        }}</pre>

        <!-- Modal para agregar un modulo -->
        <DialogModal
            :show="lessonModal"
            @close="lessonModal = false"
            maxWidth="2xl"
        >
            <template #title>{{
                editingLesson ? "Editar lección" : "Nueva lección"
            }}</template>
            <template #subtitle>{{
                editingLesson
                    ? "Actualiza la información de tu lección a continuación"
                    : "Detalla las generalidades de tu nueva lección a continuación"
            }}</template>

            <template #content>
                <FormSection2
                    @submitted="
                        editingLesson
                            ? handlerUpdateLesson()
                            : handlerStoreLesson()
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

                            <!-- ID de curso -->
                            <div class="hidden col-span-6">
                                <TextInput
                                    v-model="form.module_id"
                                    type="text"
                                    class="block w-full mt-1"
                                />
                                <InputError
                                    :message="form.errors.module_id"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </template>
                    <template #actions>
                        <div class="space-x-4">
                            <SecondaryButton @click="closeLessonModal">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton
                                :class="{
                                    'opacity-25': form.processing,
                                }"
                                :disabled="form.processing"
                                :loading="form.processing"
                            >
                                {{ editingLesson ? "Actualizar" : "Crear" }}
                            </PrimaryButton>
                        </div>
                    </template>
                </FormSection2>
            </template>
        </DialogModal>

        <!-- Modal para eliminar un modulo -->
        <DialogModal
            :show="deleteLessonModal"
            @close="deleteLessonModal = false"
            maxWidth="2xl"
        >
            <template #title>Eliminar lección</template>
            <template #subtitle>
                ¿Estás seguro de que deseas eliminar esta lección? Esta acción
                no se puede deshacer y el contenido será eliminado
                permanentemente del módulo.
            </template>
            <template #content>
                <FormSection2>
                    <template #form>
                        <div class="space-y-5"></div>
                    </template>
                    <template #actions>
                        <div class="space-x-4">
                            <SecondaryButton @click="closeDeleteLessonModal">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton
                                color="red"
                                @click="handlerDeleteLesson"
                                :class="{
                                    'opacity-25': form.processing,
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
    </AppLayout>
</template>
