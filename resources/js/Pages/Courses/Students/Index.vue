<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";
import CourseCard from "@/Components/CourseCard.vue";
import {
    ArrowRightIcon,
    BookOpenIcon,
    PlusIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";

// Swiper
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

// Props
const props = defineProps({
    courses: {
        type: Object,
        required: true,
    },
    categories: {
        type: Object,
        required: true,
    },
});

// Normalizacion de data
const courseData = computed(() => props.courses?.data ?? props.courses ?? []);
const categoryData = computed(
    () => props.categories?.data ?? props.categories ?? []
);

// Módulos de Swiper
const modules = [Navigation, Pagination, Autoplay];

// Configuración de breakpoints para responsive
const swiperBreakpoints = {
    320: { slidesPerView: 1, spaceBetween: 16 },
    640: { slidesPerView: 2, spaceBetween: 20 },
    1024: { slidesPerView: 3, spaceBetween: 24 },
    1280: { slidesPerView: 4, spaceBetween: 24 },
};

// Agrupar cursos por categoría
const coursesByCategory = computed(() => {
    if (
        !Array.isArray(categoryData.value) ||
        !Array.isArray(courseData.value)
    ) {
        return [];
    }

    const grouped = {};

    categoryData.value.forEach((category) => {
        grouped[category.id] = {
            category: category,
            courses: courseData.value.filter(
                (course) => course.category.id === category.id
            ),
        };
    });

    // Filtrar solo categorías que tengan cursos
    return Object.values(grouped).filter((item) => item.courses.length > 0);
});
</script>
<template>
    <AppLayout
        title="Cursos disponibles"
        subtitle="Descubre los cursos que EduNova tiene para ti y amplía tus conocimientos."
        :pages="[
            {
                name: 'Cursos disponibles',
                href: route('courses.students.index'),
                current: false,
            },
        ]"
    >
        <!-- <pre>{{ courseData }}</pre> -->

        <!-- Sin cursos -->
        <div v-if="courseData.length === 0" class="py-12 text-center">
            <BookOpenIcon class="w-16 h-16 mx-auto mb-4 text-gray-400" />
            <p class="text-lg text-gray-500">
                No hay cursos disponibles en este momento.
            </p>
        </div>

        <!-- Swipers por categoría -->
        <div v-else class="py-5">
            <section
                v-for="item in coursesByCategory"
                :key="item.category.id"
                class="mx-9"
            >
                <!-- Header de categoría -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ item.category.name }}
                    </h2>
                    <p
                        v-if="item.category.description"
                        class="mt-1 text-gray-600"
                    >
                        {{ item.category.description }}
                    </p>
                    <p class="mt-1 text-sm font-medium text-indigo-600">
                        {{ item.courses.length }}
                        {{
                            item.courses.length === 1
                                ? "curso disponible"
                                : "cursos disponibles"
                        }}
                    </p>
                </div>

                <!-- Swiper de cursos de esta categoría -->
                <div class="relative">
                    <Swiper
                        :modules="modules"
                        :slides-per-view="4"
                        :space-between="24"
                        :navigation="{
                            prevEl: `.swiper-button-prev-${item.category.id}`,
                            nextEl: `.swiper-button-next-${item.category.id}`,
                        }"
                        :pagination="{
                            el: `.swiper-pagination-${item.category.id}`,
                            clickable: true,
                            dynamicBullets: true,
                        }"
                        :breakpoints="swiperBreakpoints"
                        :autoplay="{
                            delay: 5000,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        }"
                        :loop="item.courses.length > 4"
                        class="!pb-12"
                    >
                        <SwiperSlide
                            v-for="course in item.courses"
                            :key="course.id"
                        >
                            <CourseCard
                                :course="course"
                                :href="
                                    route('courses.students.courses', course.id)
                                "
                            />
                        </SwiperSlide>
                    </Swiper>

                    <!-- Botones de navegación personalizados (únicos por categoría) -->
                    <button
                        :class="`swiper-button-prev-${item.category.id}`"
                        class="absolute z-10 p-3 transition-all -translate-x-4 -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow-lg -left-5 top-1/2 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <ChevronLeftIcon class="w-5 h-5 text-gray-700" />
                    </button>
                    <button
                        :class="`swiper-button-next-${item.category.id}`"
                        class="absolute z-10 p-3 transition-all translate-x-4 -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow-lg -right-5 top-1/2 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <ChevronRightIcon class="w-5 h-5 text-gray-700" />
                    </button>

                    <!-- Paginación personalizada -->
                    <div
                        :class="`swiper-pagination-${item.category.id}`"
                        class="mt-4"
                    ></div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Personalización de paginación */
:deep(.swiper-pagination-bullet) {
    background: #6366f1;
    opacity: 0.5;
}

:deep(.swiper-pagination-bullet-active) {
    opacity: 1;
    background: #4f46e5;
}

/* Ocultar flechas por defecto de Swiper */
:deep(.swiper-button-next),
:deep(.swiper-button-prev) {
    display: none;
}
</style>
