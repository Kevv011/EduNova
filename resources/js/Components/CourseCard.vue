<script setup>
import { BookOpenIcon, UserIcon, TagIcon } from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
    href: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <Link v-if="href" :href="href" class="block h-full">
        <div
            class="relative h-full overflow-hidden transition-all bg-white border border-gray-200 shadow-sm cursor-pointer group rounded-xl hover:shadow-xl"
        >
            <!-- Imagen del curso -->
            <div
                class="relative h-48 overflow-hidden bg-gradient-to-br from-indigo-100 to-purple-100"
            >
                <img
                    v-if="course.image"
                    :src="course.image"
                    :alt="course.name"
                    class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110"
                />
                <div
                    v-else
                    class="flex items-center justify-center w-full h-full"
                >
                    <BookOpenIcon class="w-16 h-16 text-indigo-300" />
                </div>
            </div>

            <!-- Contenido de la card -->
            <div class="p-5">
                <!-- Categoría -->
                <div
                    v-if="course.category"
                    class="flex items-center gap-2 mb-3"
                >
                    <TagIcon class="w-4 h-4 text-indigo-600" />
                    <span class="text-xs font-medium text-indigo-600">
                        {{ course.category.name }}
                    </span>
                </div>

                <!-- Nombre del curso -->
                <h3
                    class="mb-3 text-lg font-bold text-gray-900 transition-colors line-clamp-2 group-hover:text-indigo-600"
                >
                    {{ course.name }}
                </h3>

                <!-- Descripción -->
                <p class="mb-4 text-sm text-gray-600 line-clamp-2">
                    {{ course.description }}
                </p>

                <!-- Áreas del curso -->
                <div
                    v-if="course.areas && course.areas.length"
                    class="flex flex-wrap gap-2 mb-4"
                >
                    <span
                        v-for="(area, idx) in course.areas.slice(0, 3)"
                        :key="idx"
                        class="px-2 py-1 text-xs font-medium text-indigo-700 rounded-md bg-indigo-50"
                    >
                        {{ area }}
                    </span>
                    <span
                        v-if="course.areas.length > 3"
                        class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded-md"
                    >
                        +{{ course.areas.length - 3 }}
                    </span>
                </div>

                <!-- Divider -->
                <div class="w-full h-px mb-4 bg-gray-200"></div>

                <!-- Instructor -->
                <div v-if="course.instructor" class="flex items-center gap-3">
                    <div
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100"
                    >
                        <UserIcon class="w-5 h-5 text-indigo-600" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-500">
                            Instructor
                        </p>
                        <p class="text-sm font-semibold text-gray-900 truncate">
                            {{ course.instructor.user?.name || "Sin nombre" }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Barra decorativa inferior -->
            <div
                class="h-1 transition-all duration-300 bg-gradient-to-r from-indigo-500 to-purple-500 group-hover:h-2"
            />
        </div>
    </Link>
</template>
