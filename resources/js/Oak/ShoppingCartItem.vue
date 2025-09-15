<script setup>
import { router } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import moment from 'moment';

const props = defineProps(['item']);

const removeItem = () => {
  router.delete(route('shopping-cart.destroy', props.item.id));
};

function getSelectedDays(start, end) {
  if (!start || !end) return 0;
  const startDate = moment(start, 'YYYY-MM-DD');
  const endDate = moment(end, 'YYYY-MM-DD');
  return endDate.diff(startDate, 'days') + 1;
}
</script>
<template>
  <div class="flex py-6">
    <div class="flex-shrink-0">
      <img
        :src="item.image"
        :alt="item.name"
        class="object-cover object-center w-24 h-24 rounded-md sm:h-24 sm:w-32"
      />
    </div>

    <div class="flex flex-col flex-1 ml-4 sm:ml-6">
      <div>
        <div>
          <div>
            <h4 class="text-sm">
              <a
                href="#"
                class="font-medium text-gray-700 text-md hover:text-gray-800"
                >{{ item.name }}</a
              >
            </h4>
            <p class="mt-0 text-sm text-gray-500">
              {{ item.address }}
            </p>
            <!-- <p class="mt-1 text-sm text-gray-500">
              {{ item.pivot.start }} - {{ item.pivot.end }}
            </p> -->
            <p class="mt-1 text-base font-medium text-gray-900">
              {{
                $numbro(item.pivot.subtotal).formatCurrency({
                  mantissa: 2,
                  thousandSeparated: true,
                })
              }}
            </p>
            <div class="flex items-center justify-between w-full mt-2">
              <p class="text-sm text-gray-500 underline">
                {{
                  $numbro(item.price).formatCurrency({
                    mantissa: 2,
                    thousandSeparated: true,
                  })
                }}
                /día x
                {{ getSelectedDays(item.pivot.start, item.pivot.end) }} días
              </p>
              <button
                type="button"
                @click="removeItem"
                class="flex items-center space-x-2 text-sm font-medium text-gray-500 hover:text-red-500 hover:underline"
              >
                <span class="text-red-500">Eliminar</span>
                <TrashIcon class="w-5 h-5 text-red-500" />
              </button>
            </div>
          </div>

          <div>
            <!-- <div class="inline-flex items-center underline">
              <p class="text-sm text-right text-gray-500">
                {{
                  $numbro(item.pivot.price * item.min_day_sales).formatCurrency({
                    mantissa: 2,
                    thousandSeparated: true
                  })
                }}
                mes x {{ item.pivot.slots }} slots
              </p>
              <PencilIcon class="w-4 h-4 ml-1" />
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
