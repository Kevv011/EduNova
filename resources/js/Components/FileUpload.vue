<!-- resources/js/Components/FileUpload.vue -->
<script setup>
import { ref, computed, onMounted } from "vue";
import {
    PlusIcon,
    XMarkIcon,
    DocumentIcon,
    PhotoIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    accept: {
        type: String,
        default: "image/*",
    },
    multiple: {
        type: Boolean,
        default: true,
    },
    maxFiles: {
        type: Number,
        default: 5,
    },
    maxSize: {
        type: Number,
        default: 2048,
    },
    label: {
        type: String,
        default: "Subir archivos",
    },
    description: {
        type: String,
        default: "Imágenes (JPG, PNG) hasta 2MB cada uno",
    },
    existingFiles: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(["update:modelValue", "remove-existing"]);

const fileInput = ref(null);
const files = ref([]);
const isDragging = ref(false);

const canAddMore = computed(() => files.value.length < props.maxFiles);

const openFilePicker = () => {
    fileInput.value?.click();
};

const handleFileSelect = (event) => {
    const selectedFiles = Array.from(event.target.files || []);
    addFiles(selectedFiles);
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;
    const droppedFiles = Array.from(event.dataTransfer.files || []);
    addFiles(droppedFiles);
};

const addFiles = (newFiles) => {
    const validFiles = newFiles
        .filter((file) => {
            // Validar tamaño
            if (file.size > props.maxSize * 1024) {
                alert(
                    `${file.name} excede el tamaño máximo de ${props.maxSize}KB`
                );
                return false;
            }
            return true;
        })
        .slice(0, props.maxFiles - files.value.length);

    validFiles.forEach((file) => {
        const fileData = {
            file,
            name: file.name,
            size: file.size,
            type: file.type,
            preview: null,
            id: Date.now() + Math.random(),
        };

        // Crear preview para imágenes
        if (file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = (e) => {
                fileData.preview = e.target.result;
                files.value = [...files.value];
            };
            reader.readAsDataURL(file);
        }

        files.value.push(fileData);
    });

    // Emitir solo si hay nuevos archivos
    emit(
        "update:modelValue",
        files.value.filter((f) => !f.isExisting).map((f) => f.file)
    );
};

const removeFile = (index) => {
    const removed = files.value[index];
    files.value.splice(index, 1);

    // Emitir el evento para imágenes existentes
    if (
        removed.isExisting &&
        removed.id &&
        !String(removed.id).startsWith("existing-")
    ) {
        emit("remove-existing", removed.id);
    }
    emit(
        "update:modelValue",
        files.value.filter((f) => !f.isExisting).map((f) => f.file)
    );
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const getFileIcon = (type) => {
    if (type.startsWith("image/")) return PhotoIcon;
    return DocumentIcon;
};

const onDragOver = (event) => {
    event.preventDefault();
    isDragging.value = true;
};

const onDragLeave = (event) => {
    event.preventDefault();
    isDragging.value = false;
};

onMounted(() => {
    props.existingFiles.forEach((f, idx) => {
        files.value.push({
            id: f.id,
            file: null,
            name: f.name ?? f.url?.split("/").pop() ?? "archivo",
            size: f.size ?? 0,
            type: f.type ?? "",
            preview: f.url,
            isExisting: true,
        });
    });
});
</script>

<template>
    <div class="space-y-4">
        <!-- Input oculto -->
        <input
            ref="fileInput"
            type="file"
            :accept="accept"
            :multiple="multiple"
            @change="handleFileSelect"
            class="hidden"
        />

        <!-- Zona de drop -->
        <div
            v-if="canAddMore"
            @click="openFilePicker"
            @drop="handleDrop"
            @dragover="onDragOver"
            @dragleave="onDragLeave"
            :class="[
                'relative border-2 border-dashed rounded-lg px-6 py-4 cursor-pointer transition-colors',
                isDragging
                    ? 'border-blue-400 bg-blue-50'
                    : 'border-gray-300 hover:border-gray-400 hover:bg-gray-200',
            ]"
        >
            <div class="text-center">
                <PlusIcon class="w-8 h-8 mx-auto text-gray-400" />
                <div class="mt-2">
                    <p class="text-sm font-medium text-gray-600">{{ label }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ description }}</p>
                    <p class="mt-1 text-xs text-gray-400">
                        {{ files.length }}/{{ maxFiles }} archivos
                    </p>
                </div>
            </div>
        </div>

        <!-- Lista de archivos seleccionados -->
        <div v-if="files.length > 0" class="space-y-2">
            <div
                v-for="(fileData, index) in files"
                :key="fileData.id"
                class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm"
            >
                <!-- Preview o icono -->
                <div class="flex-shrink-0 mr-3">
                    <img
                        v-if="fileData.preview"
                        :src="fileData.preview"
                        :alt="fileData.name"
                        class="object-cover w-10 h-10 rounded"
                    />
                    <component
                        v-else
                        :is="getFileIcon(fileData.type)"
                        class="w-10 h-10 text-gray-400"
                    />
                </div>

                <!-- Info del archivo -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        {{ fileData.name }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ formatFileSize(fileData.size) }}
                    </p>
                </div>

                <!-- Botón eliminar -->
                <button
                    @click.stop="removeFile(index)"
                    type="button"
                    class="flex-shrink-0 p-1 ml-2 text-gray-400 rounded-full hover:text-red-500 hover:bg-red-50"
                >
                    <XMarkIcon class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>
</template>
