<script setup>
import { router } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import ProgresCircle from './ProgresCircle.vue';
const props = defineProps(['screen', 'selected']);
</script>
<template>
  <div class="flex py-6">
    <!-- <pre>{{ props.screen.total_spots_used }}</pre> -->
    <div class="flex-shrink-0">
      <img
        :src="screen.image"
        :alt="screen.name"
        class="object-cover object-center w-24 h-24 rounded-md sm:h-32 sm:w-32"
      />
    </div>

    <div class="flex flex-col flex-1 ml-4 sm:ml-6">
      <div>
        <div class="flex justify-between">
          <div>
            <h4 class="text-sm">
              <a
                href="#"
                class="font-medium text-gray-700 hover:text-gray-800"
                >{{ screen.name }}</a
              >
            </h4>
            <p class="mt-1 text-sm text-gray-500">
              {{ screen.resolution_width }} x {{ screen.resolution_height }} px
            </p>
          </div>

          <div>
            <div class="inline-flex items-center space-x-2">
              <div class="relative flex items-center justify-center w-7 h-7">
                <ProgresCircle
                  :max="Number(screen.total_spots_used)"
                  :value="screen.slots"
                />
              </div>

              <span class="text-xs"
                >{{ Number(screen.slots) - Number(screen.total_spots_used) }}/{{
                  screen.slots
                }}
                slots</span
              >
            </div>
            <!-- <p class="ml-4 text-base font-medium text-right text-gray-900">
              {{
                $numbro(screen.pivot.subtotal).formatCurrency({
                  mantissa: 2,
                  thousandSeparated: true,
                })
              }}
            </p> -->
            <!-- <div class="inline-flex items-center underline">
              <p class="text-sm text-right text-gray-500">
                {{
                  $numbro(screen.pivot.price * screen.min_day_sales).formatCurrency({
                    mantissa: 2,
                    thousandSeparated: true
                  })
                }}
                mes x {{ screen.pivot.slots }} slots
              </p>
              <PencilIcon class="w-4 h-4 ml-1" />
            </div> -->
          </div>
        </div>
      </div>

      <div class="flex items-end justify-between flex-1 mt-4">
        <p class="flex items-center space-x-2 text-sm text-gray-700"></p>
        <div class="ml-4">
          <button
            type="button"
            @click="$emit('remove', screen)"
            v-if="selected"
            class="inline-flex items-center space-x-2 text-sm font-medium text-gray-500 underline hover:text-red-500"
          >
            <span>Eliminar</span>
            <TrashIcon class="w-4 h-4 text-red-500" />
          </button>
          <button
            type="button"
            @click="$emit('add', screen)"
            v-else
            class="inline-flex items-center space-x-2 text-sm font-medium text-gray-500 underline hover:text-orange-500"
          >
            <span>Agregar</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
