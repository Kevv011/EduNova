<script setup>
import {
  ClockIcon,
  ComputerDesktopIcon,
  PlusIcon,
  TrashIcon,
  XMarkIcon,
  ShoppingCartIcon,
  EyeIcon,
} from '@heroicons/vue/24/outline';
import ProgresCircle from './ProgresCircle.vue';
import DialogModal from '@/Components/DialogModal.vue';
import { computed, onMounted, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import ButtonGroup from './ButtonGroup.vue';
import InputError from '@/Components/InputError.vue';
import moment from 'moment';

const props = defineProps({
  selected: {
    type: Boolean,
    default: false,
  },
  screen: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['add', 'remove', 'open', 'openCartModal']);

// Formateo de valores de impactos a miles --------------------------------
function formatImpacts(val) {
  val = Number(val);
  if (val >= 1000) {
    return (val / 1000).toFixed(val % 1000 === 0 ? 0 : 1) + 'K';
  }
  return val;
}
</script>
<template>
  <div
    class="bg-gray-300 rounded-lg cursor-pointer"
    @click="emit('open', screen)"
  >
    <div class="relative">
      <div class="relative w-full overflow-hidden rounded-t-lg h-50">
        <img
          :src="screen.image"
          :alt="screen.name"
          class="object-cover object-center w-full h-full"
        />
      </div>
      <div class="relative px-3 pb-4 mt-4">
        <div class="flex items-center justify-start gap-2 mb-3">
          <div class="inline-flex items-center">
            <ComputerDesktopIcon class="w-6 h-6" />
            <span class="text-xs">{{
              screen.resolution_width && screen.resolution_height
                ? screen.resolution_width +
                  ' X ' +
                  screen.resolution_height +
                  'px'
                : '------- px'
            }}</span>
          </div>
          <div class="inline-flex items-center">
            <div
              class="relative flex items-center justify-center w-5 space-x-1 me-1 text-dark h-7"
            >
              <EyeIcon class="w-5 h-5" />
            </div>

            <span class="text-xs">
              {{ formatImpacts(screen.people_impacts ?? 0) }}
            </span>
          </div>
          <div class="inline-flex items-center">
            <ClockIcon class="w-6 h-6" />
            <span class="text-xs">{{ screen.ad_seconds }}s</span>
          </div>
        </div>
        <p class="mt-2">{{ screen.name }}</p>
        <p class="mt-1 text-sm text-gray-700 underline">
          {{ screen.address ?? '-----' }}
        </p>

        <div class="flex items-center justify-between mt-3">
          <!-- Agregar-quitar del carrito -->
          <div>
            <button
              type="button"
              class="relative p-2 transition duration-150 ease-in-out bg-red-500 rounded-lg hover:bg-red-600 focus:bg-red-600 active:bg-red-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
              @click.stop="emit('remove', screen.id)"
              v-if="selected"
            >
              <div class="flex items-center gap-1 text-sm text-white">
                <TrashIcon class="w-4 h-4 text-white" />
                <p>Remover</p>
              </div>
            </button>
            <button
              type="button"
              class="relative p-2 transition duration-150 ease-in-out bg-orange-500 rounded-lg hover:bg-orange-600 focus:bg-orange-600 active:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
              @click.stop="emit('openCartModal', screen, 'card')"
              v-else
            >
              <div class="flex items-center gap-1 p-0 text-sm text-white">
                <ShoppingCartIcon class="w-5 text-white" />
                <p>Agregar</p>
              </div>
            </button>
          </div>

          <!-- Precio -->
          <div class="flex items-end gap-1 mt-2">
            <p class="text-lg font-medium">
              {{
                $numbro(screen.price).formatCurrency({
                  thousandSeparated: true,
                  mantissa: 2,
                })
              }}
            </p>
            <p class="text-xs">/ día + IVA</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
