<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    hasTerms: {
        type: Boolean,
        default: !!(
            typeof window !== "undefined" &&
            window?.__INERTIA__?.page?.props?.jetstream
                ?.hasTermsAndPrivacyPolicyFeature
        ),
    },
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        terms: props.hasTerms ? (data.terms ? "on" : "") : undefined,
    })).post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Crear cuenta" />

    <div class="min-h-screen lg:grid lg:grid-cols-2">
        <div class="relative hidden lg:block lg:sticky lg:top-0 lg:h-screen">
            <img
                class="absolute inset-0 object-cover w-full h-full"
                src="https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80"
                alt="Imagen de fondo"
            />
        </div>

        <div
            class="flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-20 xl:px-24 lg:overflow-y-auto"
        >
            <div class="w-full max-w-sm mx-auto lg:w-96">
                <div class="flex flex-col items-center justify-center">
                    <img
                        class="w-auto h-20"
                        src="/images/edunova-black.png"
                        alt="edunova"
                    />
                    <h2
                        class="mt-8 font-bold tracking-tight text-gray-900 text-2xl/9"
                    >
                        Crea tu cuenta
                    </h2>
                    <p class="mt-2 text-gray-500 text-sm/6">
                        ¿Ya tienes cuenta?
                        <Link
                            :href="route('login')"
                            class="font-semibold text-indigo-600 hover:text-indigo-500"
                        >
                            Inicia sesión
                        </Link>
                    </p>
                </div>

                <div class="mt-10">
                    <form @submit.prevent="submit" class="space-y-6" novalidate>
                        <!-- Nombre -->
                        <div>
                            <label
                                for="name"
                                class="block font-medium text-gray-900 text-sm/6"
                                >Nombre</label
                            >
                            <div class="mt-2">
                                <input
                                    v-model="form.name"
                                    type="text"
                                    id="name"
                                    name="name"
                                    autocomplete="name"
                                    required
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                    :class="{
                                        'outline-red-500 focus:outline-red-500':
                                            form.errors.name,
                                    }"
                                />
                            </div>
                            <p
                                v-if="form.errors.name"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label
                                for="email"
                                class="block font-medium text-gray-900 text-sm/6"
                                >Correo electrónico</label
                            >
                            <div class="mt-2">
                                <input
                                    v-model="form.email"
                                    type="email"
                                    id="email"
                                    name="email"
                                    autocomplete="username"
                                    required
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                    :class="{
                                        'outline-red-500 focus:outline-red-500':
                                            form.errors.email,
                                    }"
                                />
                            </div>
                            <p
                                v-if="form.errors.email"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label
                                for="password"
                                class="block font-medium text-gray-900 text-sm/6"
                                >Contraseña</label
                            >
                            <div class="mt-2">
                                <input
                                    v-model="form.password"
                                    type="password"
                                    id="password"
                                    name="password"
                                    autocomplete="new-password"
                                    required
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                    :class="{
                                        'outline-red-500 focus:outline-red-500':
                                            form.errors.password,
                                    }"
                                />
                            </div>
                            <p
                                v-if="form.errors.password"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Confirmación -->
                        <div>
                            <label
                                for="password_confirmation"
                                class="block font-medium text-gray-900 text-sm/6"
                                >Confirmar contraseña</label
                            >
                            <div class="mt-2">
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    required
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                    :class="{
                                        'outline-red-500 focus:outline-red-500':
                                            form.errors.password_confirmation,
                                    }"
                                />
                            </div>
                            <p
                                v-if="form.errors.password_confirmation"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ form.errors.password_confirmation }}
                            </p>
                        </div>

                        <!-- Submit -->
                        <div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50"
                            >
                                <span v-if="!form.processing"
                                    >Crear cuenta</span
                                >
                                <span v-else>Procesando…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
