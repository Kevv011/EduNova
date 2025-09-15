<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import DialogModal from '@/Components/DialogModal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { Link, router, useForm } from '@inertiajs/vue3';

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  closeable: {
    type: Boolean,
    default: true,
  },
});

const emits = defineEmits(['close', 'change-to-register']);

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form
    .transform((data) => ({
      ...data,
      remember: form.remember ? 'on' : '',
    }))
    .post('/login', {
      onFinish: () => form.reset('password'),
      onSuccess: () => {
        emits('close');
        form.reset();
        router.visit(route('home'));
      },
    });
};
</script>
<template>
  <Modal
    :show="show"
    @close="$emit('close')"
    max-width="md"
    :closeable="closeable"
  >
    <div class="px-6 py-4">
      <div class="flex justify-end" v-if="closeable">
        <button
          type="button"
          class="focus:outline-none"
          @click="$emit('close')"
        >
          <XMarkIcon class="w-5 h-5 text-black" />
        </button>
      </div>

      <div class="my-4">
        <h2 class="font-semibold text-lg">Iniciar sesión</h2>
      </div>
      <form @submit.prevent="submit">
        <div>
          <InputLabel for="email" value="Correo" />
          <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
            required
            autofocus
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="Contraseña" />
          <TextInput
            id="password"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full"
            required
            autocomplete="current-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="block mt-4">
          <label class="flex items-center">
            <Checkbox v-model:checked="form.remember" name="remember" />
            <span class="ms-2 text-sm text-gray-600">Recuerdamé</span>
          </label>
        </div>

        <div class="mt-6">
          <!-- <Link
            :href="route('password.request')"
            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            ¿Olvidaste tu contraseña?
          </Link> -->

          <PrimaryButton
            class="w-full justify-center"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Iniciar sesión
          </PrimaryButton>
          <div class="inline-flex items-center space-x-2 mt-4 text-sm">
            <span>¿No estás registrado?</span>
            <button
              type="button"
              class="text-blue-800 hover:text-blue-900 focus:outline-none font-semibold"
              @click="$emit('change-to-register')"
            >
              Registraté aquí
            </button>
          </div>
        </div>
      </form>
    </div>
  </Modal>
</template>
