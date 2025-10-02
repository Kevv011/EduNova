<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GroupInput from "@/Oak/GroupInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import OakTable from "@/Oak/Table.vue";
import ActionBtn from "@/Oak/ActionBtn.vue";
import NormalBtn from "@/Oak/NormalBtn.vue";
import { useFilters } from "@/composables/useFilters";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import moment from "moment";
import {
    EyeIcon,
    MagnifyingGlassIcon,
    PencilIcon,
    UserPlusIcon,
    CheckCircleIcon,
    XMarkIcon,
    ExclamationCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/24/outline";

// Props
const props = defineProps({
    courses: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

// Filters
const query = useFilters(
    {
        filters: {
            course_name: props.filters.filter?.course_name ?? null,
        },
        sort: props.filters.sort ? props.filters.sort : "-id",
    },
    route("courses.index"),
    {
        preserveScroll: true,
        preserveState: true,
    }
);

// Headers de la tabla
const headers = [
    {
        key: "id",
        title: "ID",
        sortable: true,
    },
    {
        key: "name",
        title: "Curso",
    },
    {
        key: "description",
        title: "Descripcion",
    },
    {
        key: "available_status",
        title: "Habilitado a estudiantes",
        align: "center",
    },
    {
        key: "category_id",
        title: "Categoria",
        align: "center",
    },
    {
        key: "authorized",
        title: "Autorizado",
        align: "center",
    },
    {
        key: "created_at",
        title: "Creado",
    },
    {
        key: "actions",
        title: "Acciones",
        align: "right",
    },
];

// Pages
const pages = [
    { name: "Mis cursos", href: route("courses.index"), current: true },
];

// Pasar AREAS de array a texto
// const areasText = (areas) => {
//     return Array.isArray(areas) ? areas.join(", ") : areas;
// };
</script>

<template>
    <AppLayout
        title="Mis cursos"
        subtitle="Vista de tus cursos creados a la comunidad de estudiantes"
        :pages="pages"
    >
        <!-- Boton de creacion -->
        <div class="flex items-center justify-end">
            <PrimaryButton
                tag="link"
                color="blue"
                :href="route('courses.create')"
            >
                Crear solicitud de nuevo curso
            </PrimaryButton>
        </div>

        <div class="grid grid-cols-4 gap-2">
            <GroupInput :icon="MagnifyingGlassIcon">
                <TextInput
                    type="search"
                    v-model="query.filters.course_name"
                    placeholder="Buscar un curso..."
                    class="w-full pl-10"
                />
            </GroupInput>
        </div>

        <!-- Tabla de contenido -->
        <OakTable :headers="headers" :items="props.courses">
            <!-- available_status -->
            <template #available_status="{ item }">
                <div
                    :class="[
                        'rounded-lg',
                        item?.available_status
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700',
                    ]"
                >
                    {{
                        item?.available_status ? "Habilitado" : "No habilitado"
                    }}
                </div>
            </template>

            <!-- category.name -->
            <template #category_id="{ item }">
                {{ item?.category?.name }}
            </template>

            <!-- areas -->
            <!-- <template #areas="{ item }">
                <div
                    class="max-w-[250px] truncate"
                    v-tooltip="areasText(item.areas)"
                >
                    {{ areasText(item.areas) }}
                </div>
            </template> -->

            <!-- Authorized -->
            <template #authorized="{ item }">
                <div class="flex items-center justify-center">
                    <component
                        :is="
                            item.authorized === 'authorized'
                                ? CheckCircleIcon
                                : item.authorized === 'rejected'
                                ? XCircleIcon
                                : ExclamationCircleIcon
                        "
                        class="w-10 h-10 rounded-full"
                        :class="
                            item.authorized === 'authorized'
                                ? 'text-green-600 bg-green-100'
                                : item.authorized === 'rejected'
                                ? 'text-red-600 bg-red-100'
                                : 'text-yellow-600 bg-yellow-100'
                        "
                        v-tooltip="
                            item.authorized === 'authorized'
                                ? 'AUTORIZADO'
                                : item.authorized === 'rejected'
                                ? 'RECHAZADO'
                                : 'PENDIENTE DE AUTORIZACIÓN'
                        "
                    />
                </div>
            </template>

            <!-- Description -->
            <template #description="{ item }">
                <div
                    class="max-w-[250px] truncate"
                    v-tooltip="item?.description"
                >
                    {{ item?.description }}
                </div>
            </template>

            <!-- Created_at -->
            <template #created_at="{ item }">
                {{ moment(item.created_at).format("DD MMM YYYY, HH:mm") }}
            </template>

            <template #actions="{ item }">
                <div class="flex justify-end">
                    <ActionBtn
                        :href="route('courses.modules', item.id)"
                        tag="a"
                        type="blue"
                        :icon="EyeIcon"
                        v-tooltip="'Ver detalle'"
                    />
                    <ActionBtn
                        :href="route('courses.edit', item.id)"
                        tag="a"
                        :icon="PencilIcon"
                        type="outline"
                        class="ml-2"
                        v-tooltip="'Actualizar'"
                    />
                </div>
            </template>
        </OakTable>
    </AppLayout>
</template>
