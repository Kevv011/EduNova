<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { Link, useForm } from '@inertiajs/vue3';

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

const emits = defineEmits(['close', 'change-to-login']);

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
});

const submit = () => {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
    onSuccess: () => {
      emits('close');
      form.reset();
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
        <h2 class="font-semibold text-lg">Registrarse</h2>
      </div>
      <form @submit.prevent="submit">
        <div>
          <InputLabel for="name" value="Nombre" />
          <TextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-full"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="mt-4">
          <InputLabel for="email" value="Correo" />
          <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
            required
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
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
          <InputLabel
            for="password_confirmation"
            value="Confirmar Contraseña"
          />
          <TextInput
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="mt-1 block w-full"
            required
            autocomplete="new-password"
          />
          <InputError
            class="mt-2"
            :message="form.errors.password_confirmation"
          />
        </div>

        <div
          v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
          class="mt-4"
        >
          <InputLabel for="terms">
            <div class="flex items-center">
              <Checkbox
                id="terms"
                v-model:checked="form.terms"
                name="terms"
                required
              />

              <div class="ms-2">
                I agree to the
                <a
                  target="_blank"
                  :href="route('terms.show')"
                  class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >Terms of Service</a
                >
                and
                <a
                  target="_blank"
                  :href="route('policy.show')"
                  class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >Privacy Policy</a
                >
              </div>
            </div>
            <InputError class="mt-2" :message="form.errors.terms" />
          </InputLabel>
        </div>

        <div class="mt-6">
          <PrimaryButton
            class="w-full justify-center"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Registrarse
          </PrimaryButton>

          <div class="inline-flex items-center space-x-2 mt-4 text-sm">
            <span>¿Ya tienes cuenta?</span>
            <button
              type="button"
              class="text-blue-800 hover:text-blue-900 focus:outline-none font-semibold"
              @click="$emit('change-to-login')"
            >
              Inicia sesión aquí
            </button>
          </div>
        </div>
      </form>
    </div>
  </Modal>
</template>
