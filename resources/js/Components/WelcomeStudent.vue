<script setup>
import { computed, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import {
    ArrowRightIcon,
    BookOpenIcon,
    PlusIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from "@heroicons/vue/24/outline";
import moment from "moment";
import PrimaryButton from "./PrimaryButton.vue";
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from "@headlessui/vue";
import CourseCard from "./CourseCard.vue";

// Swiper
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

// Props
const props = defineProps({
    user: {
        type: String,
    },
    courses: {
        type: Object,
    },
    categories: {
        type: Object,
    },
});

// Normalizar data
const courseData = computed(() => props.courses?.data ?? props.courses ?? null);
const categorieData = computed(
    () => props.categories?.data ?? props.categories ?? null
);

// Módulos de Swiper
const modules = [Navigation, Pagination, Autoplay];

// Tabs
const categories = ref({
    Recent: [
        {
            id: 1,
            title: "Does drinking coffee make you smarter?",
            date: "5h ago",
            commentCount: 5,
            shareCount: 2,
        },
        {
            id: 2,
            title: "So you've bought coffee... now what?",
            date: "2h ago",
            commentCount: 3,
            shareCount: 2,
        },
    ],
    Popular: [
        {
            id: 1,
            title: "Is tech making coffee better or worse?",
            date: "Jan 7",
            commentCount: 29,
            shareCount: 16,
        },
        {
            id: 2,
            title: "The most innovative things happening in coffee",
            date: "Mar 19",
            commentCount: 24,
            shareCount: 12,
        },
    ],
    Trending: [
        {
            id: 1,
            title: "Ask Me Anything: 10 answers to your questions about coffee",
            date: "2d ago",
            commentCount: 9,
            shareCount: 5,
        },
        {
            id: 2,
            title: "The worst advice we've ever heard about coffee",
            date: "4d ago",
            commentCount: 1,
            shareCount: 2,
        },
    ],
});
</script>

<template>
    <!-- <pre>{{ courseData[0].instructor }}</pre> -->
    <div class="relative overflow-hidden bg-white">
        <section class="relative pt-10 overflow-hidden">
            <div aria-hidden="true" class="absolute inset-0">
                <div class="absolute inset-0 mx-auto overflow-hidden">
                    <img
                        src="/images/dashboard.png"
                        alt=""
                        class="object-cover size-full rounded-xl"
                    />
                </div>
                <div class="absolute inset-0 bg-white/85" />
                <div class="absolute" />
            </div>

            <div class="relative mx-16 mb-10">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Perfil del instructor -->
                    <div
                        class="p-6 transition-shadow bg-white border border-gray-200 shadow-sm rounded-xl hover:shadow-md"
                    >
                        <div class="flex items-center gap-4">
                            <img
                                :src="user.profile_photo_url"
                                :alt="user.name"
                                class="object-cover w-16 h-16 border-2 border-indigo-100 rounded-full"
                            />
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-xs font-medium tracking-wide text-gray-500 uppercase"
                                >
                                    Perfil
                                </p>
                                <h3
                                    class="text-lg font-semibold text-gray-900 truncate"
                                >
                                    {{ user.name }}
                                </h3>
                                <p class="text-sm text-gray-600 truncate">
                                    {{ user.email }}
                                </p>
                            </div>
                        </div>
                        <PrimaryButton
                            tag="a"
                            :href="route('profile.show')"
                            class="justify-center w-full mt-4"
                        >
                            Ver perfil
                        </PrimaryButton>
                    </div>

                    <!-- Cursos creados -->
                    <div
                        class="p-6 transition-shadow border border-indigo-200 shadow-sm bg-gradient-to-br from-gray-100 to-white rounded-xl hover:shadow-md"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p
                                    class="text-xs font-medium tracking-wide text-black uppercase"
                                >
                                    Cursos inscritos
                                </p>
                                <p
                                    class="mt-2 text-4xl font-bold text-indigo-900"
                                >
                                    <!-- {{ courses.length }} -->
                                </p>
                                <p class="mt-1 text-sm text-indigo-700">
                                    Total de cursos inscritos en EduNova
                                </p>
                            </div>
                            <div class="p-3 bg-indigo-200 rounded-full">
                                <BookOpenIcon class="w-8 h-8 text-indigo-700" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- <nav>
            <div class="w-full sm:px-0">
                <TabGroup>
                    <TabList class="flex bg-black border border-gray-300">
                        <Tab
                            v-for="category in Object.keys(categories)"
                            as="template"
                            :key="category"
                            v-slot="{ selected }"
                        >
                            <button
                                :class="[
                                    'w-full py-2.5 text-sm font-medium',
                                    selected
                                        ? 'bg-gray-600 text-white'
                                        : 'text-blue-100 hover:bg-white/[0.12] border',
                                ]"
                            >
                                {{ category }}
                            </button>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel
                            v-for="(posts, idx) in Object.values(categories)"
                            :key="idx"
                            :class="[
                                'rounded-xl bg-white p-3',
                                'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                            ]"
                        >
                            <ul>
                                <li
                                    v-for="post in posts"
                                    :key="post.id"
                                    class="relative p-3 rounded-md hover:bg-gray-100"
                                >
                                    <h3 class="text-sm font-medium leading-5">
                                        {{ post.title }}
                                    </h3>

                                    <ul
                                        class="flex mt-1 space-x-1 text-xs font-normal leading-4 text-gray-500"
                                    >
                                        <li>{{ post.date }}</li>
                                        <li>&middot;</li>
                                        <li>
                                            {{ post.commentCount }} comments
                                        </li>
                                        <li>&middot;</li>
                                        <li>{{ post.shareCount }} shares</li>
                                    </ul>

                                    <a
                                        href="#"
                                        :class="[
                                            'absolute inset-0 rounded-md',
                                            'ring-blue-400 focus:z-10 focus:outline-none focus:ring-2',
                                        ]"
                                    />
                                </li>
                            </ul>
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </nav> -->

        <!-- Sección de cursos con Swiper -->
        <section class="py-10">
            <div class="mx-9">
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">
                        Cursos disponibles
                    </h2>
                    <p class="mt-1 text-gray-600">
                        Explora y encuentra el curso perfecto para ti
                    </p>
                </div>

                <!-- Sin cursos -->
                <div v-if="courseData.length === 0" class="py-12 text-center">
                    <BookOpenIcon
                        class="w-16 h-16 mx-auto mb-4 text-gray-400"
                    />
                    <p class="text-lg text-gray-500">
                        No hay cursos disponibles en este momento.
                    </p>
                </div>

                <!-- Swiper de cursos -->
                <div v-else class="relative">
                    <Swiper
                        :modules="modules"
                        :slides-per-view="4"
                        :space-between="24"
                        :navigation="{
                            prevEl: '.swiper-button-prev-custom',
                            nextEl: '.swiper-button-next-custom',
                        }"
                        :pagination="{
                            clickable: true,
                            dynamicBullets: true,
                        }"
                        :breakpoints="swiperBreakpoints"
                        :autoplay="{
                            delay: 5000,
                            disableOnInteraction: false,
                            pauseOnMouseEnter: true,
                        }"
                        :loop="courseData.length > 4"
                        class="!pb-12"
                    >
                        <SwiperSlide
                            v-for="course in courseData"
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

                    <!-- Botones de navegación personalizados -->
                    <button
                        class="absolute z-10 p-3 transition-all -translate-x-4 -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow-lg -left-5 swiper-button-prev-custom top-1/2 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <ChevronLeftIcon class="w-5 h-5 text-gray-700" />
                    </button>
                    <button
                        class="absolute z-10 p-3 transition-all translate-x-4 -translate-y-1/2 bg-white border border-gray-300 rounded-full shadow-lg -right-5 swiper-button-next-custom top-1/2 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <ChevronRightIcon class="w-5 h-5 text-gray-700" />
                    </button>
                </div>

                <!-- Ver todos los cursos -->
                <div v-if="courseData.length > 4" class="mt-8 text-center">
                    <Link
                        :href="route('courses.students.index')"
                        class="inline-flex items-center gap-2 px-6 py-3 font-medium text-white transition bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700 hover:shadow-lg"
                    >
                        Ver todos los cursos
                        <ArrowRightIcon class="w-5 h-5" />
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
