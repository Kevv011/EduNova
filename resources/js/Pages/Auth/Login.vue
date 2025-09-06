<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    canResetPassword: { type: Boolean, default: true },
    status: { type: String, default: "" },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? "on" : "",
    })).post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div
        v-if="props.status"
        class="p-3 mb-4 text-sm text-green-700 border border-green-200 rounded-md bg-green-50"
    >
        {{ props.status }}
    </div>

    <div class="min-h-screen lg:grid lg:grid-cols-2">
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
                        Inicia sesión
                    </h2>
                    <p class="mt-2 text-gray-500 text-sm/6">
                        ¿Aún no tienes cuenta?
                        <Link
                            :href="route('register')"
                            class="font-semibold text-indigo-600 hover:text-indigo-500"
                            >Crear cuenta</Link
                        >
                    </p>
                </div>

                <div class="mt-10">
                    <form @submit.prevent="submit" class="space-y-6" novalidate>
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
                                    name="email"
                                    id="email"
                                    autocomplete="email"
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
                                    name="password"
                                    id="password"
                                    autocomplete="current-password"
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

                        <!-- Remember + Forgot -->
                        <div class="flex items-center justify-between">
                            <label
                                class="flex items-center gap-2 text-gray-900 text-sm/6"
                            >
                                <input
                                    id="remember"
                                    name="remember"
                                    type="checkbox"
                                    v-model="form.remember"
                                    class="w-4 h-4 text-indigo-600 bg-white border border-gray-300 rounded-sm focus:ring-0"
                                />
                                Recuérdame
                            </label>
                            <div
                                v-if="props.canResetPassword"
                                class="text-sm/6"
                            >
                                <Link
                                    :href="route('password.request')"
                                    class="font-semibold text-indigo-600 hover:text-indigo-500"
                                >
                                    ¿Olvidaste tu contraseña?
                                </Link>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50"
                            >
                                <span v-if="!form.processing"
                                    >Iniciar sesión</span
                                >
                                <span v-else>Procesando…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="relative hidden lg:block lg:sticky lg:top-0 lg:h-screen">
            <img
                class="absolute inset-0 object-cover w-full h-full"
                src="https://images.unsplash.com/photo-1496917756835-20cb06e75b4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80"
                alt="Imagen de fondo"
            />
        </div>
    </div>
</template>
