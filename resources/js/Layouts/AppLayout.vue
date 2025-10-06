<script setup>
import { ref, computed, onMounted } from "vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import { useHasPermission } from "@/composables/useHasPermission";
import useToast from "@/composables/useToast";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import {
    Bars3Icon,
    BellIcon,
    BookOpenIcon,
    HomeIcon,
    InboxStackIcon,
    RectangleStackIcon,
    ShoppingBagIcon,
    UserIcon,
} from "@heroicons/vue/24/outline";
import { ChevronDownIcon, MagnifyingGlassIcon } from "@heroicons/vue/20/solid";
import Breadcrumbs from "@/Oak/Navigation/Breadcrumbs.vue";

const userNavigation = [{ name: "Pérfil", href: route("profile.show") }];

const props = defineProps({
    title: String,
    subtitle: String,
    pages: {
        type: Array,
        default: () => [],
    },
    user: String,
    hideCart: {
        type: Boolean,
        default: false,
    },
    disabledOptions: {
        type: Boolean,
        default: false,
    },
});

// User autenticado
const user = computed(() => usePage().props.user);

const sidebarNavigation = computed(() => [
    {
        name: "Inicio",
        href: route("dashboard"),
        icon: HomeIcon,
        current: route().current("dashboard"),
        visible: !props.disabledOptions,
    },
    {
        name: "Mis cursos",
        href: route("courses.index"),
        icon: RectangleStackIcon,
        current: route().current("courses.*"),
        visible: !props.disabledOptions && useHasPermission("ver_cursos"),
    },
    {
        name: "Cursos disponibles",
        href: route("courses.students.index"),
        icon: BookOpenIcon,
        current: route().current("courses.students*"),
        visible: useHasPermission("ver_cursos_estudiantes"),
    },
    {
        name: "Mis cursos",
        href: route("students.courses"),
        icon: RectangleStackIcon,
        current: route().current("students.*"),
        visible: useHasPermission("ver_cursos_estudiantes"),
    },
    {
        name: "Materias impartidas",
        href: route("categories.index"),
        icon: InboxStackIcon,
        current: route().current("categories.*"),
        visible: !props.disabledOptions && useHasPermission("ver_materias"),
    },
    {
        name: "Pérfil",
        href: route("profile.show"),
        icon: UserIcon,
        current: route().current("profile.show"),
        visible: !props.disabledOptions,
    },
]);

const sidebarOpen = ref(false);

const logout = () => {
    router.post(route("logout"));
};

const style = computed(
    () => usePage().props.jetstream.flash?.bannerStyle || "success"
);

const message = computed(() => usePage().props.jetstream.flash?.banner);

onMounted(() => {
    if (message.value) {
        useToast(message.value)[style.value]();
    }
});
</script>

<template>
    <Head :title="title" />
    <div>
        <!-- Static sidebar for desktop -->
        <div
            class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:block lg:w-20 lg:overflow-y-auto lg:bg-gray-900 lg:pb-4"
        >
            <div class="flex items-center justify-center h-16 shrink-0">
                <a :href="route('home')">
                    <img
                        src="/images/edunova-short.png"
                        alt="edunova-logo"
                        class="size-11"
                    />
                </a>
            </div>
            <nav class="mt-8">
                <ul role="list" class="flex flex-col items-center space-y-1">
                    <li
                        v-for="item in sidebarNavigation.filter(
                            (x) => x.visible
                        )"
                        :key="item.name"
                    >
                        <Link
                            :href="item.href"
                            :class="[
                                item.current
                                    ? 'bg-gray-800 text-white'
                                    : 'text-gray-400 hover:bg-gray-800 hover:text-white',
                                'group flex gap-x-3 rounded-md p-3 text-sm/6 font-semibold',
                            ]"
                            v-tooltip.right="{
                                content: item.name,
                                popperClass: 'tooltip-white',
                            }"
                        >
                            <component
                                :is="item.icon"
                                class="size-6 shrink-0"
                                aria-hidden="true"
                            />
                            <span class="sr-only">{{ item.name }}</span>
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="lg:pl-20">
            <div
                class="sticky top-0 z-40 flex items-center h-16 px-4 bg-white border-b border-gray-200 shadow-xs shrink-0 gap-x-4 sm:gap-x-6 sm:px-6 lg:px-8"
            >
                <button
                    type="button"
                    class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <Bars3Icon class="size-6" aria-hidden="true" />
                </button>

                <!-- Separator -->
                <div
                    class="w-px h-6 bg-gray-900/10 lg:hidden"
                    aria-hidden="true"
                />

                <div
                    class="flex self-stretch flex-1 gap-x-4 lg:gap-x-6"
                    v-if="user"
                >
                    <div class="flex-1"></div>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <button
                            type="button"
                            class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500"
                            v-if="!props.disabledOptions"
                        >
                            <span class="sr-only">Notificaciones</span>
                            <BellIcon class="size-6" aria-hidden="true" />
                        </button>

                        <!-- Separator -->
                        <div
                            class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10"
                            aria-hidden="true"
                        />

                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative">
                            <MenuButton class="relative flex items-center">
                                <span class="absolute -inset-1.5" />
                                <span class="sr-only">Open user menu</span>
                                <img
                                    class="rounded-full size-8 bg-gray-50"
                                    :src="user.profile_photo_path"
                                    alt="user.name"
                                />
                                <span class="hidden lg:flex lg:items-center">
                                    <span
                                        class="ml-4 font-medium text-gray-900 text-sm/6"
                                        aria-hidden="true"
                                    >
                                        {{ user?.name ?? "Invitado" }}
                                    </span>
                                    <ChevronDownIcon
                                        class="ml-2 text-gray-400 size-5"
                                        aria-hidden="true"
                                    />
                                </span>
                            </MenuButton>
                            <transition
                                enter-active-class="transition duration-100 ease-out"
                                enter-from-class="transform scale-95 opacity-0"
                                enter-to-class="transform scale-100 opacity-100"
                                leave-active-class="transition duration-75 ease-in"
                                leave-from-class="transform scale-100 opacity-100"
                                leave-to-class="transform scale-95 opacity-0"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-hidden"
                                >
                                    <MenuItem
                                        v-for="item in userNavigation"
                                        :key="item.name"
                                        v-slot="{ active }"
                                    >
                                        <a
                                            :href="item.href"
                                            :class="[
                                                active
                                                    ? 'bg-gray-50 outline-hidden'
                                                    : '',
                                                'block px-3 py-1 text-sm/6 text-gray-900',
                                            ]"
                                            >{{ item.name }}</a
                                        >
                                    </MenuItem>
                                    <form
                                        method="POST"
                                        @submit.prevent="logout"
                                    >
                                        <MenuItem
                                            as="button"
                                            v-slot="{ active }"
                                            type="submit"
                                            class="block px-3 py-1 text-gray-900 text-sm/6"
                                        >
                                            Cerrar sesión
                                        </MenuItem>
                                    </form>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>

            <main>
                <div class="px-2 py-10 sm:px-6 lg:px-6 lg:py-6">
                    <div
                        class="max-w-sm py-16 mx-auto sm:max-w-2xl sm:py-1 md:max-w-3xl lg:max-w-[100rem] lg:px-2"
                    >
                        <Breadcrumbs
                            :pages="pages"
                            :disabled-breadcrumb="props.disabledOptions"
                        />
                        <h1
                            class="mt-2 font-medium text-gray-900 text-2xl/7 sm:truncate sm:text-3xl sm:tracking-tight"
                        >
                            {{ title }}
                        </h1>
                        <p v-if="subtitle" class="mt-1 text-gray-600 text-md">
                            {{ subtitle }}
                        </p>
                        <div class="py-5">
                            <slot />
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
<style>
.tooltip-white .v-popper__inner {
    background-color: #fff;
    color: black;
}

.tooltip-white .v-popper__arrow {
    border-color: white;
}
</style>
