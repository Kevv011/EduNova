<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed, ref, watch, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import { useForm, router } from "@inertiajs/vue3";
import FormSection2 from "@/Components/FormSection2.vue";
import TextInput from "@/Components/TextInput.vue";
import Textarea from "@/Oak/Textarea.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import FileUpload from "@/Components/FileUpload.vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import { PencilIcon, PlusIcon, TrashIcon } from "@heroicons/vue/24/outline";
import draggable from "vuedraggable";

// Props
const props = defineProps({
    module: { type: [Object, null], required: true },
});

const lessonModal = ref(false);
const editingLesson = ref(false);
const deleteLessonModal = ref(false);
const createContent = ref(false);
const isSidebarLocked = computed(() => createContent.value);

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

// useForm para actualizar contenido de lessons
const lessonForm = useForm({
    lesson_id: null,
    subtitle: null,
    content: null,
    files: [],
    url_resource: [""],
    remove_media_ids: [],
});

// Media existentes (para mostrarlas) y removidas (IDs reales de Spatie)
const existingMediaForLesson = ref([]);
const removedMediaIds = ref([]);

// Limite maximo de MEDIA y URLs
const MAX_FILES = 2;
const MAX_URLS = 3;

// Evita que el v-model tenga más de 2 archivos en MEDIA
const enforceFileSlotLimit = () => {
    if (lessonForm.files.length > MAX_FILES) {
        lessonForm.files = lessonForm.files.slice(0, MAX_FILES);
    }
};

// Array de URLs aegurado con minimo 1 dato y max 3
const enforceUrlLimit = () => {
    if (
        !Array.isArray(lessonForm.url_resource) ||
        lessonForm.url_resource.length === 0
    ) {
        lessonForm.url_resource = [""];
    }
    if (lessonForm.url_resource.length > MAX_URLS) {
        lessonForm.url_resource = lessonForm.url_resource.slice(0, MAX_URLS);
    }
};

// Cuando cambia la lección seleccionada, detectar cambio
watch(
    () => selectedLesson.value,
    (l) => {
        if (!l) return;
        lessonForm.lesson_id = l.id;
        lessonForm.remove_media_ids = [];
        removedMediaIds.value = [];

        // Actualizar contenido actual
        if (!createContent.value) {
            lessonForm.subtitle = l.subtitle ?? null;
            lessonForm.content = l.content ?? null;

            // Mapeo de URL resources del backend a array simple de strings
            const urls = Array.isArray(l.url_resource) ? l.url_resource : [];
            lessonForm.url_resource = urls.length ? [...urls] : [""];
        }

        // Actualizacion de MEDIA LIBRARY existente
        existingMediaForLesson.value = (l.media ?? [])
            .filter((m) => m.collection_name === "lessons")
            .map((m) => ({
                id: m.id,
                url: m.url ?? m.original_url,
                name: m.file_name,
                size: m.size,
                type: m.mime_type,
            }));

        removedMediaIds.value = [];

        // Limitar slots disponibles según lo existente
        enforceFileSlotLimit();
        enforceUrlLimit();
    },
    { immediate: true }
);

// Añadir/Eliminar campos URL con límites
const addUrlField = () => {
    if (lessonForm.url_resource.length < MAX_URLS) {
        lessonForm.url_resource.push("");
    }
};
const removeUrlField = (idx) => {
    if (lessonForm.url_resource.length > 1) {
        lessonForm.url_resource.splice(idx, 1);
    } else {
        lessonForm.url_resource[0] = "";
    }
};

const startEditingContent = () => {
    createContent.value = true;
    const l = selectedLesson.value;

    lessonForm.lesson_id = l.id;
    lessonForm.subtitle = l.subtitle ?? null;
    lessonForm.content = l.content ?? null;

    lessonForm.url_resource =
        Array.isArray(l.url_resource) && l.url_resource.length
            ? [...l.url_resource]
            : [""];

    lessonForm.files = [];
    removedMediaIds.value = [];

    enforceFileSlotLimit();
    enforceUrlLimit();
};

const handleRemoveExistingFile = (mediaId) => {
    if (!removedMediaIds.value.includes(mediaId)) {
        removedMediaIds.value.push(mediaId);
    }
};

// Cancelar edición: reset de la data actual de la lección
const cancelEditingContent = () => {
    createContent.value = false;
    removedMediaIds.value = [];

    const l = selectedLesson.value;
    if (!l) return;
    lessonForm.subtitle = l.subtitle ?? null;
    lessonForm.content = l.content ?? null;
    const urls = Array.isArray(l.url_resource) ? l.url_resource : [];
    lessonForm.url_resource = urls.length ? [...urls] : [""];
    lessonForm.files = [];
};

// Guardar contenido del LESSON editada
const saveLessonContent = () => {
    // Limpia URLs
    lessonForm.url_resource = (lessonForm.url_resource ?? [])
        .map((u) => (u ?? "").trim())
        .filter((u, i, arr) => u !== "" && arr.indexOf(u) === i)
        .slice(0, MAX_URLS);

    // Sincroniza remove_media_ids
    lessonForm.remove_media_ids = removedMediaIds.value;

    // Envío
    lessonForm.post(route("lessons.content.update", lessonForm.lesson_id), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            createContent.value = false;
            lessonForm.files = [];
            lessonForm.remove_media_ids = [];
            removedMediaIds.value = [];
        },
    });
};

// Manejo de TABS de Headless ui
const videoResources = computed(() => {
    return (selectedLesson.value?.media ?? []).filter((m) =>
        m.type?.startsWith("video/")
    );
});

const imageResources = computed(() => {
    return (selectedLesson.value?.media ?? []).filter((m) =>
        m.type?.startsWith("image/")
    );
});

const documentResources = computed(() => {
    const pdfs = (selectedLesson.value?.media ?? []).filter(
        (m) => m.type === "application/pdf"
    );
    const urls = (selectedLesson.value?.url_resource ?? []).map((url, idx) => ({
        id: `url-${idx}`,
        url,
        name: url,
        type: "url",
    }));
    return [...pdfs, ...urls];
});

const availableTabs = computed(() => {
    const tabs = {};

    if (videoResources.value.length > 0) {
        tabs["Video explicativo"] = videoResources.value;
    }

    if (imageResources.value.length > 0) {
        tabs["Recursos visuales"] = imageResources.value;
    }

    if (documentResources.value.length > 0) {
        tabs["Enlaces y recursos PDF"] = documentResources.value;
    }

    return tabs;
});

// Manejo de ordenamiento de LESSONS con Draggable Vue
const localLessons = ref([]);

// Sincronizacion con las lecciones del backend
watch(
    lessons,
    (newLessons) => {
        localLessons.value = [...newLessons];
    },
    { immediate: true }
);

// Método para guardar el nuevo orden
const saveOrder = () => {
    const ordered = localLessons.value.map((lesson, index) => ({
        id: lesson.id,
        position: index + 1,
    }));

    router.post(
        route("lessons.reorder"),
        { lessons: ordered },
        {
            preserveScroll: true,
        }
    );
};

// Detecta cambios en el orden
const handleDragEnd = () => {
    saveOrder();
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
                                    :disabled="!canPrev || isSidebarLocked"
                                    @click="goPrev"
                                >
                                    Anterior
                                </button>
                                <button
                                    class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg disabled:opacity-40 hover:bg-indigo-700"
                                    :disabled="!canNext || isSidebarLocked"
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
                        <!-- <pre>{{ selectedLesson }}</pre> -->

                        <!-- Si la lección NO tiene contenido -->
                        <div v-if="!selectedLesson.content && !createContent">
                            <div class="flex flex-col items-center">
                                <img
                                    src="/images/emptyContent.png"
                                    alt="empty"
                                    width="600"
                                />
                                <PrimaryButton
                                    class="flex justify-center gap-1"
                                    @click="startEditingContent"
                                >
                                    Crear contenido
                                </PrimaryButton>
                            </div>
                        </div>

                        <!-- Si la lección SÍ tiene contenido y no se creando -->
                        <div
                            v-else-if="selectedLesson.content && !createContent"
                        >
                            <div
                                v-if="selectedLesson?.subtitle"
                                class="prose whitespace-pre-line max-w-none"
                            >
                                <p class="mb-3 text-xl font-medium">
                                    {{ selectedLesson?.subtitle }}
                                </p>
                            </div>
                            <div
                                class="mb-5 prose whitespace-pre-line max-w-none"
                            >
                                {{ selectedLesson.content }}
                            </div>

                            <!-- TABS de navegacion -->
                            <div
                                v-if="Object.keys(availableTabs).length > 0"
                                class="flex justify-center mt-6"
                            >
                                <div class="w-full px-2 sm:px-0">
                                    <TabGroup>
                                        <TabList
                                            class="flex p-1 space-x-1 rounded-xl bg-indigo-900/20"
                                        >
                                            <Tab
                                                v-for="category in Object.keys(
                                                    availableTabs
                                                )"
                                                :key="category"
                                                v-slot="{ selected }"
                                                as="template"
                                            >
                                                <button
                                                    :class="[
                                                        'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
                                                        'ring-white/60 ring-offset-2 ring-offset-indigo-400 focus:outline-none focus:ring-2',
                                                        selected
                                                            ? 'bg-white text-indigo-700 shadow'
                                                            : 'text-indigo-800 hover:bg-white/[0.12] hover:text-white',
                                                    ]"
                                                >
                                                    {{ category }}
                                                    <span
                                                        class="ml-2 text-xs opacity-75"
                                                    >
                                                        ({{
                                                            availableTabs[
                                                                category
                                                            ].length
                                                        }})
                                                    </span>
                                                </button>
                                            </Tab>
                                        </TabList>

                                        <TabPanels class="mt-4">
                                            <!-- Panel: Video explicativo (solo si existe) -->
                                            <TabPanel
                                                v-if="
                                                    availableTabs[
                                                        'Video explicativo'
                                                    ]
                                                "
                                                :class="[
                                                    'rounded-xl bg-white p-4',
                                                    'ring-white/60 ring-offset-2 ring-offset-indigo-400 focus:outline-none focus:ring-2',
                                                ]"
                                            >
                                                <div class="space-y-4">
                                                    <div
                                                        v-for="video in videoResources"
                                                        :key="video.id"
                                                        class="overflow-hidden border rounded-lg"
                                                    >
                                                        <video
                                                            controls
                                                            :src="video.url"
                                                            class="w-full rounded-lg"
                                                        ></video>
                                                        <div
                                                            class="p-3 bg-gray-50"
                                                        >
                                                            <p
                                                                class="text-sm font-medium text-gray-900"
                                                            >
                                                                {{ video.name }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </TabPanel>

                                            <!-- Panel: Recursos visuales (solo si existe) -->
                                            <TabPanel
                                                v-if="
                                                    availableTabs[
                                                        'Recursos visuales'
                                                    ]
                                                "
                                                :class="[
                                                    'rounded-xl bg-white p-4',
                                                    'ring-white/60 ring-offset-2 ring-offset-indigo-400 focus:outline-none focus:ring-2',
                                                ]"
                                            >
                                                <div
                                                    class="grid grid-cols-2 gap-4 md:grid-cols-3"
                                                >
                                                    <div
                                                        v-for="image in imageResources"
                                                        :key="image.id"
                                                        class="overflow-hidden border rounded-lg"
                                                    >
                                                        <img
                                                            :src="image.url"
                                                            :alt="image.name"
                                                            class="object-cover w-full h-48"
                                                        />
                                                        <div
                                                            class="p-2 bg-gray-50"
                                                        >
                                                            <p
                                                                class="text-xs font-medium text-gray-900 truncate"
                                                            >
                                                                {{ image.name }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </TabPanel>

                                            <!-- Panel: Enlaces y recursos PDF (solo si existe) -->
                                            <TabPanel
                                                v-if="
                                                    availableTabs[
                                                        'Enlaces y recursos PDF'
                                                    ]
                                                "
                                                :class="[
                                                    'rounded-xl bg-white p-4',
                                                    'ring-white/60 ring-offset-2 ring-offset-indigo-400 focus:outline-none focus:ring-2',
                                                ]"
                                            >
                                                <ul class="space-y-2">
                                                    <li
                                                        v-for="doc in documentResources"
                                                        :key="doc.id"
                                                        class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50"
                                                    >
                                                        <div
                                                            class="flex items-center min-w-0 gap-3"
                                                        >
                                                            <svg
                                                                v-if="
                                                                    doc.type ===
                                                                    'application/pdf'
                                                                "
                                                                class="w-6 h-6 text-red-600 shrink-0"
                                                                fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                            >
                                                                <path
                                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                                />
                                                            </svg>
                                                            <svg
                                                                v-else
                                                                class="w-6 h-6 text-blue-600 shrink-0"
                                                                fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                            >
                                                                <path
                                                                    d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
                                                                />
                                                            </svg>
                                                            <div
                                                                class="min-w-0"
                                                            >
                                                                <p
                                                                    class="text-sm font-medium text-gray-900 truncate"
                                                                >
                                                                    {{
                                                                        doc.name
                                                                    }}
                                                                </p>
                                                                <p
                                                                    v-if="
                                                                        doc.size
                                                                    "
                                                                    class="text-xs text-gray-500"
                                                                >
                                                                    {{
                                                                        (
                                                                            doc.size /
                                                                            1024 /
                                                                            1024
                                                                        ).toFixed(
                                                                            2
                                                                        )
                                                                    }}
                                                                    MB
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <a
                                                            :href="doc.url"
                                                            target="_blank"
                                                            class="px-3 py-1 text-sm font-medium text-indigo-600 border border-indigo-600 rounded-lg shrink-0 hover:bg-indigo-50"
                                                        >
                                                            Abrir
                                                        </a>
                                                    </li>
                                                </ul>
                                            </TabPanel>
                                        </TabPanels>
                                    </TabGroup>
                                </div>
                            </div>

                            <!-- Mensaje si no hay recursos en absoluto -->
                            <div v-else class="py-8 text-center text-gray-500">
                                No hay recursos disponibles para esta lección.
                                Puedes agregarlos editando el contenido.
                            </div>

                            <PrimaryButton
                                class="mt-4"
                                @click="startEditingContent"
                            >
                                Editar contenido
                            </PrimaryButton>
                        </div>

                        <!-- Contenido de leccion -->
                        <!-- Modo edición / creación de contenido -->
                        <div v-else>
                            <FormSection2 @submitted="saveLessonContent">
                                <template #form>
                                    <div class="space-y-6">
                                        <!-- Subtítulo -->
                                        <div>
                                            <InputLabel value="Subtítulo" />
                                            <TextInput
                                                v-model="lessonForm.subtitle"
                                                class="block w-full mt-1"
                                            />
                                            <InputError
                                                :message="
                                                    lessonForm.errors.subtitle
                                                "
                                                class="mt-2"
                                            />
                                        </div>

                                        <!-- Contenido -->
                                        <div>
                                            <InputLabel value="Contenido" />
                                            <Textarea
                                                v-model="lessonForm.content"
                                                class="block w-full mt-1"
                                                rows="8"
                                                placeholder="Detalla información sobre el contenido de esta lección"
                                            />
                                            <InputError
                                                :message="
                                                    lessonForm.errors.content
                                                "
                                                class="mt-2"
                                            />
                                        </div>

                                        <!-- Recursos de archivos (Media Library) -->
                                        <div>
                                            <InputLabel
                                                value="Recursos de la lección (máx 2 archivos)"
                                            />
                                            <FileUpload
                                                :key="selectedLessonId"
                                                v-model="lessonForm.files"
                                                :existing-files="
                                                    selectedLesson?.media ?? []
                                                "
                                                :max-files="2"
                                                :max-size="1024 * 5"
                                                accept="image/*,application/pdf,video/*"
                                                label="Archivos de la lección"
                                                description="Sube hasta 2 archivos: imágenes, PDFs o videos cortos."
                                                @remove-existing="
                                                    handleRemoveExistingFile
                                                "
                                            />
                                            <InputError
                                                :message="
                                                    lessonForm.errors.files
                                                "
                                                class="mt-2"
                                            />
                                        </div>

                                        <!-- Recursos URL (máx 3) -->
                                        <div>
                                            <InputLabel
                                                value="Recursos por URL (máx 3) (Opcional)"
                                            />
                                            <div class="space-y-2">
                                                <div
                                                    v-for="(
                                                        u, idx
                                                    ) in lessonForm.url_resource"
                                                    :key="'url-' + idx"
                                                    class="flex gap-2"
                                                >
                                                    <TextInput
                                                        v-model="
                                                            lessonForm
                                                                .url_resource[
                                                                idx
                                                            ]
                                                        "
                                                        class="flex-1"
                                                        placeholder="https://..."
                                                    />
                                                    <SecondaryButton
                                                        class="shrink-0"
                                                        @click.prevent="
                                                            removeUrlField(idx)
                                                        "
                                                        v-tooltip="
                                                            'Eliminar URL'
                                                        "
                                                    >
                                                        Quitar
                                                    </SecondaryButton>
                                                </div>
                                                <div
                                                    class="flex items-center justify-between"
                                                >
                                                    <span
                                                        class="text-xs text-gray-500"
                                                    >
                                                        Puedes agregar hasta 3
                                                        URLs (YouTube, Vimeo,
                                                        PDFs públicos, etc.)
                                                    </span>
                                                    <SecondaryButton
                                                        :disabled="
                                                            lessonForm
                                                                .url_resource
                                                                .length >= 3
                                                        "
                                                        @click.prevent="
                                                            addUrlField
                                                        "
                                                    >
                                                        + URL
                                                    </SecondaryButton>
                                                </div>
                                            </div>
                                            <InputError
                                                :message="
                                                    lessonForm.errors
                                                        .url_resource
                                                "
                                                class="mt-2"
                                            />
                                        </div>
                                    </div>
                                </template>

                                <template #actions>
                                    <div class="flex items-center gap-3">
                                        <SecondaryButton
                                            type="button"
                                            @click="cancelEditingContent"
                                            >Cancelar</SecondaryButton
                                        >
                                        <PrimaryButton
                                            type="submit"
                                            :class="{
                                                'opacity-25':
                                                    lessonForm.processing,
                                            }"
                                            :disabled="lessonForm.processing"
                                        >
                                            Guardar contenido
                                        </PrimaryButton>
                                    </div>
                                </template>
                            </FormSection2>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Aside: índice de lecciones -->
            <aside class="md:col-span-4 lg:col-span-3">
                <div class="sticky top-4">
                    <div
                        class="p-4 bg-white border rounded-2xl"
                        :class="
                            isSidebarLocked
                                ? 'pointer-events-none opacity-60 select-none'
                                : ''
                        "
                        :aria-disabled="isSidebarLocked"
                    >
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

                        <p class="mb-3 text-sm text-gray-500">
                            Si necesitas reordenar tus lecciones, <br>
                            <strong>mantén presionado y arrastra</strong>
                            cada elemento hacia el lugar donde desees colocarlo.
                        </p>

                        <draggable
                            v-model="localLessons"
                            item-key="id"
                            class="flex flex-col justify-center divide-y divide-gray-100"
                            ghost-class="opacity-50"
                            @end="handleDragEnd"
                            :disabled="isSidebarLocked"
                            tag="ul"
                        >
                            <template #item="{ element: l, index: idx }">
                                <li class="py-2">
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
                                                        {{
                                                            l.title ?? "Lección"
                                                        }}
                                                    </p>
                                                </div>
                                                <p
                                                    class="text-xs text-gray-500"
                                                >
                                                    <!-- Muestra meta si existe -->
                                                    <span v-if="l.duration">{{
                                                        l.duration
                                                    }}</span>
                                                    <span
                                                        v-if="
                                                            l.duration && l.type
                                                        "
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
                                                @click.stop="
                                                    openEditLessonModal(l)
                                                "
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
                            </template>
                        </draggable>

                        <div class="flex justify-center">
                            <SecondaryButton
                                color="lightblue"
                                class="flex justify-center"
                                @click="openLessonModal"
                            >
                                <PlusIcon class="size-4 me-2" />
                                Agregar lección
                            </SecondaryButton>
                        </div>
                    </div>
                    <p
                        v-if="isSidebarLocked"
                        class="mt-2 text-center text-red-700 text-md"
                    >
                        Estás editando contenido. Usa “Guardar contenido” o
                        “Cancelar” para volver a navegar.
                    </p>
                </div>
            </aside>
        </div>

        <!-- <pre class="mt-6 text-xs">{{
            JSON.stringify(moduleData, null, 2)
        }}</pre> -->

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
