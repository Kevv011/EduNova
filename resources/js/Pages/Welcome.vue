<script setup>
import { ref } from "vue";
import { defineComponent, h } from "vue";

// Swipper imports
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, FreeMode } from "swiper/modules";
import "swiper/css";

const modules = [Autoplay, FreeMode];

const añoActual = new Date().getFullYear();

import { Link } from "@inertiajs/vue3";

//Contenido de la sección Feature
const posts = [
    {
        id: 1,
        title: "Aprende matemáticas sin miedo",
        href: "#",
        description:
            "Descubre cómo dominar los números con clases interactivas, ejemplos prácticos y ejercicios que realmente entenderás. Conviértete en un crack de las mates mientras te diviertes.",
        imageUrl:
            "https://img.freepik.com/free-vector/tiny-students-with-huge-sign-pi-flat-vector-illustration-boy-girl-studying-math-algebra-school-college-holding-ruler-using-laptop-geometric-figures-background-education-concept_74855-23227.jpg?semt=ais_hybrid&w=740&q=80",
        date: "Ago 20, 2025",
        datetime: "2025-08-20",
        author: {
            name: "Equipo Educativo",
            imageUrl:
                "https://images.unsplash.com/photo-1527980965255-d3b416303d12?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        },
    },
    {
        id: 2,
        title: "Domina programación desde cero",
        href: "#",
        description:
            "Aprende a programar paso a paso con proyectos reales y herramientas modernas. Nuestro enfoque práctico te ayudará a convertir ideas en código sin frustraciones.",
        imageUrl:
            "https://static.vecteezy.com/system/resources/previews/007/814/266/non_2x/programmer-and-engineering-development-coding-web-development-website-design-developer-flat-vector.jpg",
        date: "Ago 20, 2025",
        datetime: "2025-08-20",
        author: {
            name: "Mentores Tech",
            imageUrl:
                "https://www.gaceta.udg.mx/wp-content/uploads/2022/09/Captura-de-Pantalla-2022-09-26-a-las-9.58.54-1024x672.png",
        },
    },
    {
        id: 3,
        title: "Construye tu futuro, un tema a la vez",
        href: "#",
        description:
            "Cada clase que tomas te acerca más a tus metas. Aprende con recursos pensados para ti, desde conceptos básicos hasta habilidades avanzadas, y haz que tu aprendizaje cuente.",
        imageUrl:
            "https://media.istockphoto.com/id/1316676125/vector/teamwork-concept-with-character-coworker-putting-jigsaw-puzzle-together-successful-problem.jpg?s=612x612&w=0&k=20&c=ofXL_MTYr3DwMqYgsJtJpiFc8fUVSK21TBHx5obmPFQ=",
        date: "Ago 20, 2025",
        datetime: "2025-08-20",
        author: {
            name: "Plataforma EDU",
            imageUrl:
                "https://images.unsplash.com/photo-1531123897727-8f129e1688ce?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        },
    },
];

// Contenido del Swipper
const base = [
    {
        id: 1,
        title: "Álgebra",
        description:
            "Domina operaciones con signos, ecuaciones y expresiones algebraicas.",
    },
    {
        id: 2,
        title: "Geometría",
        description:
            "Aprende sobre figuras, áreas, volúmenes y propiedades espaciales.",
    },
    {
        id: 3,
        title: "Trigonometría",
        description:
            "Explora funciones, razones y aplicaciones en problemas reales.",
    },
    {
        id: 4,
        title: "Programación",
        description:
            "Iníciate en el mundo del código con proyectos prácticos y divertidos.",
    },
    {
        id: 5,
        title: "Física",
        description:
            "Comprende las leyes que rigen el movimiento, la energía y el universo.",
    },
    {
        id: 6,
        title: "Química",
        description:
            "Descubre el mundo de los átomos, moléculas y reacciones químicas.",
    },
];

const items = [...base, ...base, ...base];

// Contenido Footer
const navigation_footer = [
    {
        name: "Facebook",
        href: "#",
        icon: defineComponent({
            render: () =>
                h("svg", { fill: "currentColor", viewBox: "0 0 24 24" }, [
                    h("path", {
                        "fill-rule": "evenodd",
                        d: "M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z",
                        "clip-rule": "evenodd",
                    }),
                ]),
        }),
    },
    {
        name: "Instagram",
        href: "#",
        icon: defineComponent({
            render: () =>
                h("svg", { fill: "currentColor", viewBox: "0 0 24 24" }, [
                    h("path", {
                        "fill-rule": "evenodd",
                        d: "M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z",
                        "clip-rule": "evenodd",
                    }),
                ]),
        }),
    },
    {
        name: "X",
        href: "#",
        icon: defineComponent({
            render: () =>
                h("svg", { fill: "currentColor", viewBox: "0 0 24 24" }, [
                    h("path", {
                        d: "M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0113L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z",
                    }),
                ]),
        }),
    },
    {
        name: "YouTube",
        href: "#",
        icon: defineComponent({
            render: () =>
                h("svg", { fill: "currentColor", viewBox: "0 0 24 24" }, [
                    h("path", {
                        "fill-rule": "evenodd",
                        d: "M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z",
                        "clip-rule": "evenodd",
                    }),
                ]),
        }),
    },
];
</script>

<template>
    <div class="bg-dark">
        <main>
            <!-- Hero section -->
            <div class="relative pb-16 overflow-hidden isolate pt-14 sm:pb-20">
                <img
                    src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2830&q=80&blend=fff&sat=-100&exp=15&blend-mode=overlay"
                    alt=""
                    class="absolute inset-0 object-cover -z-10 size-full opacity-10"
                />
                <div class="px-6 mx-auto max-w-7xl lg:px-8">
                    <div class="max-w-2xl py-20 mx-auto sm:py-48 lg:py-40">
                        <div class="text-center">
                            <div class="flex justify-center">
                                <img
                                    src="images/edunova-white.png"
                                    alt="edunova-logo"
                                />
                            </div>
                            <h3
                                class="mt-8 text-3xl font-normal text-white font-nunito text-pretty sm:text-6xl"
                            >
                                La mejor plataforma de aprendizaje gratuito
                            </h3>
                            <p
                                class="mt-8 text-base text-accent font-nunito sm:text-2xl"
                            >
                                ¡Refuerza tu conocimiento y transforma tus
                                sueños en una realidad!
                            </p>
                            <div
                                class="flex flex-col items-center justify-center w-full mt-10 gap-y-6 md:flex-row md:gap-x-6"
                            >
                                <Link
                                    :href="route('register')"
                                    class="px-10 py-2 text-lg font-semibold text-white rounded-xl bg-primary font-montserrat hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 md:text-md"
                                >
                                    Registrarse</Link
                                >
                                <Link
                                    :href="route('login')"
                                    class="px-10 py-2 text-lg font-semibold text-white rounded-xl bg-primary font-montserrat hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 md:text-md"
                                >
                                    Iniciar sesión
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature section -->
            <div class="py-24 bg-white sm:py-24">
                <div class="px-6 mx-auto max-w-7xl lg:px-8">
                    <div class="max-w-2xl mx-auto text-center sm:max-w-4xl">
                        <h2
                            class="text-4xl font-semibold font-nunito text-important text-balance sm:text-5xl"
                        >
                            ¡Estudiar nunca fue tan fácil y divertido!
                        </h2>
                        <p
                            class="mt-2 text-lg text-accent font-nunito sm:text-2xl"
                        >
                            Aprende a tu ritmo, con clases interactivas y
                            contenido práctico pensado para ti. Con nuestra
                            plataforma, estudiar es más sencillo y emocionante
                            que nunca
                        </p>
                    </div>
                    <div
                        class="grid max-w-2xl grid-cols-1 gap-8 mx-auto mt-16 auto-rows-fr sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3"
                    >
                        <article
                            v-for="post in posts"
                            :key="post.id"
                            class="relative flex flex-col justify-end px-8 pb-8 overflow-hidden bg-gray-900 isolate rounded-2xl pt-80 sm:pt-48 lg:pt-80"
                        >
                            <img
                                :src="post.imageUrl"
                                alt=""
                                class="absolute inset-0 object-cover -z-10 size-full"
                            />
                            <div
                                class="absolute inset-0 -z-10 bg-linear-to-t from-gray-900 via-gray-900/40"
                            />
                            <div
                                class="absolute inset-0 -z-10 rounded-2xl inset-ring inset-ring-gray-900/10"
                            />

                            <div
                                class="flex flex-wrap items-center overflow-hidden text-gray-300 gap-y-1 text-sm/6"
                            >
                                <div class="flex items-center -ml-4 gap-x-4">
                                    <svg
                                        viewBox="0 0 2 2"
                                        class="-ml-0.5 size-0.5 flex-none fill-white/50"
                                    >
                                        <circle cx="1" cy="1" r="1" />
                                    </svg>
                                </div>
                            </div>
                            <h3
                                class="mt-3 font-semibold text-center text-important rounded-2xl bg-light text-lg/6"
                            >
                                <a :href="post.href">
                                    <span class="absolute inset-0" />
                                    {{ post.title }}
                                </a>
                            </h3>
                        </article>
                    </div>
                </div>
            </div>

            <!--Subjects section-->
            <div class="py-24 bg-indigo-600 sm:py-24">
                <div class="px-6 mx-auto max-w-7xl lg:px-8">
                    <div class="max-w-2xl mx-auto text-center sm:max-w-4xl">
                        <h2
                            class="text-4xl font-semibold font-nunito text-light text-balance sm:text-5xl"
                        >
                            Contamos con un amplio catálogo de materias...
                        </h2>
                        <p
                            class="mt-2 text-[#E0E7FF] font-nunito text-lg sm:text-2xl"
                        >
                            ¿Quieres desarrollar tus habilidades matemáticas,
                            adquirir conocimientos de programación o explorar el
                            maravilloso mundo de la ciencia? ¡Todo lo encuentras
                            aqui!
                        </p>
                    </div>
                    <div class="mt-16">
                        <!--Aquí irá el Swiper-->
                        <Swiper
                            :modules="modules"
                            :loop="true"
                            :freeMode="{ enabled: true, momentum: false }"
                            :autoplay="{
                                delay: 0,
                                disableOnInteraction: false,
                                pauseOnMouseEnter: true,
                            }"
                            :speed="6000"
                            :slides-per-view="'auto'"
                            :space-between="16"
                            class="w-full"
                        >
                            <SwiperSlide
                                v-for="item in items"
                                :key="item.id + '-dup1'"
                                class="!w-80"
                            >
                                <div
                                    class="flex flex-col justify-between p-6 transition-transform transform shadow-lg h-80 w-60 rounded-2xl bg-light text-dark hover:scale-105"
                                >
                                    <!-- Encabezado con ícono -->
                                    <div
                                        class="flex items-center justify-center w-20 h-20 mx-auto rounded-full bg-primary/20"
                                    >
                                        <span
                                            class="text-4xl font-bold text-primary"
                                            >{{ item.title.charAt(0) }}</span
                                        >
                                    </div>

                                    <!-- Contenido -->
                                    <h3
                                        class="mt-4 text-lg font-bold text-center text-dark"
                                    >
                                        {{ item.title }}
                                    </h3>
                                    <p class="text-sm text-center text-dark">
                                        {{ item.description }}
                                    </p>

                                    <!-- Botón -->
                                    <button
                                        class="py-2 mt-4 font-semibold transition-colors bg-indigo-600 rounded-lg text-light hover:bg-indigo-500"
                                    >
                                        Ver más
                                    </button>
                                </div>
                            </SwiperSlide>
                        </Swiper>
                    </div>
                </div>
            </div>

            <!-- Learning objectives -->
            <div class="py-24 bg-gray-50 sm:py-32">
                <div class="max-w-2xl px-6 mx-auto lg:max-w-7xl lg:px-8">
                    <h2
                        class="font-semibold text-center text-indigo-600 text-base/7"
                    >
                        Nunca pares de aprender
                    </h2>
                    <p
                        class="max-w-lg mx-auto mt-2 text-4xl font-semibold tracking-tight text-center text-balance text-gray-950 sm:text-5xl"
                    >
                        Somos una herramienta de aprendizaje completa
                    </p>
                    <div
                        class="grid gap-4 mt-10 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2"
                    >
                        <div class="relative lg:row-span-2">
                            <div
                                class="absolute bg-white rounded-lg inset-px lg:rounded-l-4xl"
                            />
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)]"
                            >
                                <div
                                    class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0"
                                >
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center"
                                    >
                                        Al alcance de tu mano
                                    </p>
                                    <p
                                        class="max-w-lg mt-2 text-gray-600 text-sm/6 max-lg:text-center"
                                    >
                                        No importa si utilizas tu celular,
                                        tablet o computadora. EduNova se adapta
                                        a tus necesidades
                                    </p>
                                </div>
                                <div
                                    class="@container relative min-h-120 w-full grow max-lg:mx-auto max-lg:max-w-sm"
                                >
                                    <div
                                        class="absolute inset-x-10 top-10 bottom-0 overflow-hidden rounded-t-[12cqw] border-x-[3cqw] border-t-[3cqw] border-gray-700 bg-gray-900 shadow-2xl"
                                    >
                                        <img
                                            class="object-cover object-center size-full"
                                            src="https://img.freepik.com/free-vector/person-using-phone_24908-81116.jpg?semt=ais_hybrid&w=740&q=80"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute rounded-lg shadow-sm pointer-events-none inset-px outline outline-black/5 lg:rounded-l-4xl"
                            />
                        </div>
                        <div class="relative max-lg:row-start-1">
                            <div
                                class="absolute bg-white rounded-lg inset-px max-lg:rounded-t-4xl"
                            />
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]"
                            >
                                <div class="px-8 pt-8 sm:px-10 sm:pt-5">
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center"
                                    >
                                        Fácil de usar
                                    </p>
                                    <p
                                        class="max-w-lg mt-2 text-gray-600 text-sm/6 max-lg:text-center"
                                    >
                                        Hemos preparado una experiencia
                                        intuitiva y divertida para acompañarte
                                        en tu camino
                                    </p>
                                </div>
                                <div
                                    class="flex items-center justify-center flex-1 px-8 mt-2 max-lg:pt-10 max-lg:pb-12 sm:px-10 lg:pb-2"
                                >
                                    <img
                                        class="w-full rounded-xl max-lg:max-w-xs"
                                        src="https://cdn.elearningindustry.com/wp-content/uploads/2024/08/Shutterstock_2492839703.jpg"
                                        alt=""
                                    />
                                </div>
                            </div>
                            <div
                                class="absolute rounded-lg shadow-sm pointer-events-none inset-px outline outline-black/5 max-lg:rounded-t-4xl"
                            />
                        </div>
                        <div
                            class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2"
                        >
                            <div
                                class="absolute bg-white rounded-lg inset-px"
                            />
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)]"
                            >
                                <div class="px-8 pt-8 sm:px-10 sm:pt-5">
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center"
                                    >
                                        Contenido actualizado
                                    </p>
                                    <p
                                        class="max-w-lg mt-2 text-gray-600 text-sm/6 max-lg:text-center"
                                    >
                                        Encuentra información de calidad que
                                        potencia tus habilidades académicas
                                    </p>
                                </div>
                                <div
                                    class="@container flex flex-1 items-center max-lg:py-6 lg:pb-2"
                                >
                                    <img
                                        class="h-[min(152px,40cqw)] object-cover"
                                        src="https://static.vecteezy.com/system/resources/previews/036/339/043/non_2x/verified-social-media-badge-icons-set-symbols-for-authenticity-and-trust-vector.jpg"
                                        alt=""
                                    />
                                </div>
                            </div>
                            <div
                                class="absolute rounded-lg shadow-sm pointer-events-none inset-px outline outline-black/5"
                            />
                        </div>
                        <div class="relative lg:row-span-2">
                            <div
                                class="absolute bg-white rounded-lg inset-px lg:rounded-l-4xl"
                            />
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)]"
                            >
                                <div
                                    class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0"
                                >
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center"
                                    >
                                        ¡GRATIS!
                                    </p>
                                    <p
                                        class="max-w-lg mt-2 text-gray-600 text-sm/6 max-lg:text-center"
                                    >
                                        No pagues ni un solo centavo, ¡La
                                        educación no tiene precio!
                                    </p>
                                </div>
                                <div
                                    class="@container relative min-h-120 w-full grow max-lg:mx-auto max-lg:max-w-sm"
                                >
                                    <div
                                        class="absolute inset-x-0 top-0 bottom-0 overflow-hidden shadow-lg rounded-5xl"
                                    >
                                        <img
                                            class="object-cover object-center size-full"
                                            src="https://img.freepik.com/free-vector/happy-young-couple-having-fun-girl-guy-dancing-party-celebrating-good-news-flat-illustration_74855-10820.jpg?semt=ais_hybrid&w=740"
                                            alt=""
                                        />
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute rounded-lg shadow-sm pointer-events-none inset-px outline outline-black/5 lg:rounded-l-4xl"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white">
            <div
                class="flex flex-col px-6 py-12 mx-auto gap-7 max-w-7xl md:items-center lg:px-8"
            >
                <div class="hidden lg:flex lg:gap-x-12">
                    <a
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        class="font-semibold text-gray-900 text-sm/6"
                        >{{ item.name }}</a
                    >
                </div>
                <div
                    class="flex flex-col-reverse items-center justify-between gap-5"
                >
                    <div class="flex justify-center gap-x-6 md:order-2">
                        <a
                            v-for="item in navigation_footer"
                            :key="item.name"
                            :href="item.href"
                            class="text-gray-600 hover:text-gray-800"
                        >
                            <span class="sr-only">{{ item.name }}</span>
                            <component
                                :is="item.icon"
                                class="size-6"
                                aria-hidden="true"
                            />
                        </a>
                    </div>
                    <p
                        class="mt-8 text-center text-gray-600 text-sm/6 md:order-1 md:mt-0"
                    >
                        &copy; {{ añoActual }} EduNova. Todos los derechos
                        reservados.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
.swiper-wrapper {
    transition-timing-function: linear !important;
}
</style>
