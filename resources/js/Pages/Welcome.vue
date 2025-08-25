<script setup>
import { ref } from "vue";
import { defineComponent, h } from "vue";

// Swipper imports
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, FreeMode } from "swiper/modules";
import "swiper/css";

const modules = [Autoplay, FreeMode];

import {
    Dialog,
    DialogPanel,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from "@headlessui/vue";

import {
    Bars3Icon,
    MinusSmallIcon,
    PlusSmallIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";

import {
    ArrowPathIcon,
    CheckIcon,
    CloudArrowUpIcon,
    Cog6ToothIcon,
    FingerPrintIcon,
    LockClosedIcon,
    ServerIcon,
} from "@heroicons/vue/20/solid";

import { Link } from "@inertiajs/vue3";

const navigation = [
    { name: "Product", href: "#" },
    { name: "Features", href: "#" },
    { name: "Marketplace", href: "#" },
    { name: "Company", href: "#" },
];

const posts = [
    {
        id: 1,
        title: "Boost your conversion rate",
        href: "#",
        description:
            "Illo sint voluptas. Error voluptates culpa eligendi. Hic vel totam vitae illo. Non aliquid explicabo necessitatibus unde. Sed exercitationem placeat consectetur nulla deserunt vel. Iusto corrupti dicta.",
        imageUrl:
            "https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3603&q=80",
        date: "Mar 16, 2020",
        datetime: "2020-03-16",
        author: {
            name: "Michael Foster",
            imageUrl:
                "https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        },
    },
    {
        id: 2,
        title: "How to use search engine optimization to drive sales",
        href: "#",
        description:
            "Optio cum necessitatibus dolor voluptatum provident commodi et. Qui aperiam fugiat nemo cumque.",
        imageUrl:
            "https://images.unsplash.com/photo-1547586696-ea22b4d4235d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80",
        date: "Mar 10, 2020",
        datetime: "2020-03-10",
        author: {
            name: "Lindsay Walton",
            imageUrl:
                "https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        },
    },
    {
        id: 3,
        title: "Improve your customer experience",
        href: "#",
        description:
            "Cupiditate maiores ullam eveniet adipisci in doloribus nulla minus. Voluptas iusto libero adipisci rem et corporis.",
        imageUrl:
            "https://images.unsplash.com/photo-1492724441997-5dc865305da7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80",
        date: "Feb 12, 2020",
        datetime: "2020-02-12",
        author: {
            name: "Tom Cook",
            imageUrl:
                "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80",
        },
    },
];

// Contenido del Swipper
const base = [
    { id: 1, title: "Card 1" },
    { id: 2, title: "Card 2" },
    { id: 3, title: "Card 3" },
];
const items = [...base, ...base, ...base];

const mobileMenuOpen = ref(false);

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
        name: "GitHub",
        href: "#",
        icon: defineComponent({
            render: () =>
                h("svg", { fill: "currentColor", viewBox: "0 0 24 24" }, [
                    h("path", {
                        "fill-rule": "evenodd",
                        d: "M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z",
                        "clip-rule": "evenodd",
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
    <div class="bg-white">
        <!-- Header -->
        <header class="absolute inset-x-0 top-0 z-50">
            <nav
                class="flex items-center justify-between p-6 lg:px-8"
                aria-label="Global"
            >
                <div class="flex lg:flex-1">
                    <a href="#" class="-m-1.5 p-1.5">
                        <img
                            class="w-auto h-11"
                            src="/images/edunova-black.png"
                            alt="edunova"
                        />
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button
                        type="button"
                        class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-500"
                        @click="mobileMenuOpen = true"
                    >
                        <span class="sr-only">Open main menu</span>
                        <Bars3Icon class="size-6" aria-hidden="true" />
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-12">
                    <a
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        class="font-semibold text-gray-900 text-sm/6"
                        >{{ item.name }}</a
                    >
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <a href="#" class="font-semibold text-gray-900 text-sm/6"
                        >Iniciar Sesión<span aria-hidden="true">&rarr;</span></a
                    >
                </div>
            </nav>
            <Dialog
                class="lg:hidden"
                @close="mobileMenuOpen = false"
                :open="mobileMenuOpen"
            >
                <div class="fixed inset-0 z-50" />
                <DialogPanel
                    class="fixed inset-y-0 right-0 z-50 w-full p-6 overflow-y-auto bg-white sm:max-w-sm sm:ring-1 sm:ring-gray-900/10"
                >
                    <div class="flex items-center justify-between">
                        <a href="#" class="-m-1.5 p-1.5">
                            <span class="sr-only">Your Company</span>
                            <img
                                class="w-auto h-8"
                                src="/images/edunova-black.png"
                                alt=""
                            />
                        </a>
                        <button
                            type="button"
                            class="-m-2.5 rounded-md p-2.5 text-gray-700"
                            @click="mobileMenuOpen = false"
                        >
                            <span class="sr-only">Close menu</span>
                            <XMarkIcon class="size-6" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="flow-root mt-6">
                        <div class="-my-6 divide-y divide-gray-500/10">
                            <div class="py-6 space-y-2">
                                <a
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :href="item.href"
                                    class="block px-3 py-2 -mx-3 font-semibold text-gray-900 rounded-lg text-base/7 hover:bg-gray-50"
                                    >{{ item.name }}</a
                                >
                            </div>
                            <div class="py-6">
                                <a
                                    href="#"
                                    class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                                    >Log in</a
                                >
                            </div>
                        </div>
                    </div>
                </DialogPanel>
            </Dialog>
        </header>

        <main>
            <!-- Hero section -->
            <div class="relative pb-16 overflow-hidden isolate pt-14 sm:pb-20">
                <img
                    src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2830&q=80&blend=fff&sat=-100&exp=15&blend-mode=overlay"
                    alt=""
                    class="absolute inset-0 object-cover -z-10 size-full opacity-10"
                />
                <div
                    class="absolute inset-x-0 overflow-hidden -top-40 -z-10 transform-gpu blur-3xl sm:-top-80"
                    aria-hidden="true"
                >
                    <div
                        class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-288.75"
                        style="
                            clip-path: polygon(
                                74.1% 44.1%,
                                100% 61.6%,
                                97.5% 26.9%,
                                85.5% 0.1%,
                                80.7% 2%,
                                72.5% 32.5%,
                                60.2% 62.4%,
                                52.4% 68.1%,
                                47.5% 58.3%,
                                45.2% 34.5%,
                                27.5% 76.7%,
                                0.1% 64.9%,
                                17.9% 100%,
                                27.6% 76.8%,
                                76.1% 97.7%,
                                74.1% 44.1%
                            );
                        "
                    />
                </div>
                <div class="px-6 mx-auto max-w-7xl lg:px-8">
                    <div class="max-w-2xl py-32 mx-auto sm:py-48 lg:py-56">
                        <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                            <div
                                class="relative px-3 py-1 text-gray-600 rounded-full text-sm/6 ring-1 ring-gray-900/10 hover:ring-gray-900/20"
                            >
                                Contenido innovador sobre el mundo de la
                                tecnología.
                                <a
                                    href="#"
                                    class="font-semibold text-indigo-600"
                                    ><span
                                        class="absolute inset-0"
                                        aria-hidden="true"
                                    />Ver más
                                    <span aria-hidden="true">&rarr;</span></a
                                >
                            </div>
                        </div>
                        <div class="text-center">
                            <h1
                                class="text-5xl font-semibold tracking-tight text-gray-900 text-balance sm:text-7xl"
                            >
                                Fortalece tu conocimiento
                            </h1>
                            <p
                                class="mt-8 text-lg font-medium text-gray-600 text-pretty sm:text-xl/8"
                            >
                                EduNova es una plataforma diseñada para
                                potenciar tus conocimientos a través de
                                educación interactiva y cursos gratuitos,
                                brindándote las herramientas necesarias para
                                alcanzar tus metas de aprendizaje.
                            </p>
                            <div
                                class="flex items-center justify-center mt-10 gap-x-6"
                            >
                                <Link
                                    :href="route('register')"
                                    class="rounded-md bg-indigo-500 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                    >Registrate</Link
                                >
                                <a
                                    href="#"
                                    class="font-semibold text-gray-900 text-sm/6"
                                    >Explorar
                                    <span aria-hidden="true">→</span></a
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Logo cloud -->
                    <!-- <div
                        class="grid items-center max-w-lg grid-cols-4 mx-auto gap-x-8 gap-y-10 sm:max-w-xl sm:grid-cols-6 sm:gap-x-10 lg:mx-0 lg:max-w-none lg:grid-cols-5"
                    >
                        <img
                            class="object-contain w-full col-span-2 max-h-12 lg:col-span-1"
                            src="https://tailwindcss.com/plus-assets/img/logos/158x48/transistor-logo-gray-900.svg"
                            alt="Transistor"
                            width="158"
                            height="48"
                        />
                        <img
                            class="object-contain w-full col-span-2 max-h-12 lg:col-span-1"
                            src="https://tailwindcss.com/plus-assets/img/logos/158x48/reform-logo-gray-900.svg"
                            alt="Reform"
                            width="158"
                            height="48"
                        />
                        <img
                            class="object-contain w-full col-span-2 max-h-12 lg:col-span-1"
                            src="https://tailwindcss.com/plus-assets/img/logos/158x48/tuple-logo-gray-900.svg"
                            alt="Tuple"
                            width="158"
                            height="48"
                        />
                        <img
                            class="object-contain w-full col-span-2 max-h-12 sm:col-start-2 lg:col-span-1"
                            src="https://tailwindcss.com/plus-assets/img/logos/158x48/savvycal-logo-gray-900.svg"
                            alt="SavvyCal"
                            width="158"
                            height="48"
                        />
                        <img
                            class="object-contain w-full col-span-2 col-start-2 max-h-12 sm:col-start-auto lg:col-span-1"
                            src="https://tailwindcss.com/plus-assets/img/logos/158x48/statamic-logo-gray-900.svg"
                            alt="Statamic"
                            width="158"
                            height="48"
                        />
                    </div> -->
                </div>
                <div
                    class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
                    aria-hidden="true"
                >
                    <div
                        class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-288.75"
                        style="
                            clip-path: polygon(
                                74.1% 44.1%,
                                100% 61.6%,
                                97.5% 26.9%,
                                85.5% 0.1%,
                                80.7% 2%,
                                72.5% 32.5%,
                                60.2% 62.4%,
                                52.4% 68.1%,
                                47.5% 58.3%,
                                45.2% 34.5%,
                                27.5% 76.7%,
                                0.1% 64.9%,
                                17.9% 100%,
                                27.6% 76.8%,
                                76.1% 97.7%,
                                74.1% 44.1%
                            );
                        "
                    />
                </div>
            </div>

            <!-- Feature section -->
            <div class="py-24 bg-white sm:py-24">
                <div class="px-6 mx-auto max-w-7xl lg:px-8">
                    <div class="max-w-2xl mx-auto text-center">
                        <h2
                            class="text-4xl font-semibold tracking-tight text-gray-900 text-balance sm:text-5xl"
                        >
                            ¿Todo listo para acercarte a tu carrera profesional?
                        </h2>
                        <p class="mt-2 text-gray-600 text-lg/8">
                            Impulsa tus habilidades y adquiere experiencia en
                            las áreas que te apasionan. Destaca y alcanza nuevas
                            oportunidades en tu desarrollo profesional.
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
                                <time :datetime="post.datetime" class="mr-8">{{
                                    post.date
                                }}</time>
                                <div class="flex items-center -ml-4 gap-x-4">
                                    <svg
                                        viewBox="0 0 2 2"
                                        class="-ml-0.5 size-0.5 flex-none fill-white/50"
                                    >
                                        <circle cx="1" cy="1" r="1" />
                                    </svg>
                                    <div class="flex gap-x-2.5">
                                        <img
                                            :src="post.author.imageUrl"
                                            alt=""
                                            class="flex-none rounded-full size-6 bg-white/10"
                                        />
                                        {{ post.author.name }}
                                    </div>
                                </div>
                            </div>
                            <h3 class="mt-3 font-semibold text-white text-lg/6">
                                <a :href="post.href">
                                    <span class="absolute inset-0" />
                                    {{ post.title }}
                                </a>
                            </h3>
                        </article>
                    </div>
                </div>
            </div>

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
                    <div class="p-4 bg-white shadow h-80 rounded-xl">
                        {{ item.title }}
                    </div>
                </SwiperSlide>
            </Swiper>

            <!-- Learning objectives -->
            <div class="py-24 bg-gray-50 sm:py-32">
                <div class="max-w-2xl px-6 mx-auto lg:max-w-7xl lg:px-8">
                    <h2
                        class="font-semibold text-center text-indigo-600 text-base/7"
                    >
                        Deploy faster
                    </h2>
                    <p
                        class="max-w-lg mx-auto mt-2 text-4xl font-semibold tracking-tight text-center text-balance text-gray-950 sm:text-5xl"
                    >
                        Everything you need to deploy your app
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
                                        Mobile friendly
                                    </p>
                                    <p
                                        class="max-w-lg mt-2 text-gray-600 text-sm/6 max-lg:text-center"
                                    >
                                        Anim aute id magna aliqua ad ad non
                                        deserunt sunt. Qui irure qui lorem
                                        cupidatat commodo.
                                    </p>
                                </div>
                                <div
                                    class="@container relative min-h-120 w-full grow max-lg:mx-auto max-lg:max-w-sm"
                                >
                                    <div
                                        class="absolute inset-x-10 top-10 bottom-0 overflow-hidden rounded-t-[12cqw] border-x-[3cqw] border-t-[3cqw] border-gray-700 bg-gray-900 shadow-2xl"
                                    >
                                        <img
                                            class="object-cover object-top size-full"
                                            src="https://tailwindcss.com/plus-assets/img/component-images/bento-03-mobile-friendly.png"
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
                                <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center"
                                    >
                                        Performance
                                    </p>
                                    <p
                                        class="max-w-lg mt-2 text-gray-600 text-sm/6 max-lg:text-center"
                                    >
                                        Lorem ipsum, dolor sit amet consectetur
                                        adipisicing elit maiores impedit.
                                    </p>
                                </div>
                                <div
                                    class="flex items-center justify-center flex-1 px-8 max-lg:pt-10 max-lg:pb-12 sm:px-10 lg:pb-2"
                                >
                                    <img
                                        class="w-full max-lg:max-w-xs"
                                        src="https://tailwindcss.com/plus-assets/img/component-images/bento-03-performance.png"
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
                                <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center"
                                    >
                                        Security
                                    </p>
                                    <p
                                        class="max-w-lg mt-2 text-gray-600 text-sm/6 max-lg:text-center"
                                    >
                                        Morbi viverra dui mi arcu sed. Tellus
                                        semper adipiscing suspendisse semper
                                        morbi.
                                    </p>
                                </div>
                                <div
                                    class="@container flex flex-1 items-center max-lg:py-6 lg:pb-2"
                                >
                                    <img
                                        class="h-[min(152px,40cqw)] object-cover"
                                        src="https://tailwindcss.com/plus-assets/img/component-images/bento-03-security.png"
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
                                class="absolute bg-white rounded-lg inset-px max-lg:rounded-b-4xl lg:rounded-r-4xl"
                            />
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]"
                            >
                                <div
                                    class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0"
                                >
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center"
                                    >
                                        Powerful APIs
                                    </p>
                                    <p
                                        class="max-w-lg mt-2 text-gray-600 text-sm/6 max-lg:text-center"
                                    >
                                        Sit quis amet rutrum tellus ullamcorper
                                        ultricies libero dolor eget sem sodales
                                        gravida.
                                    </p>
                                </div>
                                <div class="relative w-full min-h-120 grow">
                                    <div
                                        class="absolute bottom-0 right-0 overflow-hidden bg-gray-900 shadow-2xl top-10 left-10 rounded-tl-xl outline outline-white/10"
                                    >
                                        <div
                                            class="flex bg-gray-900 outline outline-white/5"
                                        >
                                            <div
                                                class="flex -mb-px font-medium text-gray-400 text-sm/6"
                                            >
                                                <div
                                                    class="px-4 py-2 text-white border-b border-r border-r-white/10 border-b-white/20 bg-white/5"
                                                >
                                                    NotificationSetting.jsx
                                                </div>
                                                <div
                                                    class="px-4 py-2 border-r border-gray-600/10"
                                                >
                                                    App.jsx
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-6 pt-6 pb-14">
                                            <!-- Your code example -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="absolute rounded-lg shadow-sm pointer-events-none inset-px outline outline-black/5 max-lg:rounded-b-4xl lg:rounded-r-4xl"
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
                        &copy; 2024 Your Company, Inc. All rights reserved.
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
