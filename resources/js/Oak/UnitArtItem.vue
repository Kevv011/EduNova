<script setup>
import { useHasRole } from '@/composables/useHasRole';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps(['unit', 'campaign', 'selected']);

const checkedSlots = computed(() => {
  let statuses = [
    'pending_authorization',
    'rejected',
    'approved',
    'publishing',
  ];

  if (useHasRole('operator')) {
    statuses = ['rejected', 'published', 'approved', 'publishing'];
  } else if (useHasRole('traffic')) {
    statuses = ['rejected', 'published', 'approved', 'publishing'];
  }

  return props.unit?.slots.filter((x) => statuses.includes(x.status))?.length;
});

const firstSlot = computed(() => {
  return props.unit.slots[0];
});

const hasSlotRejected = computed(() => {
  return (
    props.unit.slots.filter((element) => element.status === 'rejected')
      ?.length > 0
  );
});
</script>
<template>
  <div
    :class="[
      'relative flex justify-between py-5 px-2  rounded-lg',
      selected ? 'bg-gray-200' : '',
      hasSlotRejected ? 'bg-red-100 hover:bg-red-200' : 'hover:bg-gray-300',
    ]"
  >
    <div class="flex gap-x-4 pr-6 flex-1">
      <div class="flex-shrink-0">
        <img
          :src="
            unit.screen.image?.startsWith('https')
              ? unit.screen.image
              : `/storage/${unit.screen.image}`
          "
          :alt="unit.screen.name"
          class="h-24 w-24 rounded-md object-cover object-center sm:h-32 sm:w-32"
        />
      </div>
      <div class="min-w-0 flex-auto">
        <p class="text-sm font-semibold leading-6 text-gray-900">
          <Link
            :href="
              route('campaigns.arts', {
                campaign: campaign.id,
                unit: unit.id,
                slot: firstSlot?.id,
              })
            "
          >
            <span class="absolute inset-x-0 -top-px bottom-0"></span>
            {{ unit.screen.name }}
          </Link>
        </p>
        <p class="mt-1 flex text-xs leading-5 text-gray-500">
          {{ unit.screen.mall ? unit.screen.mall.name : '' }}
        </p>
        <p class="mt-1 flex text-xs leading-5 text-gray-500">
          {{ unit.slots.length }} Slots
        </p>
        <p class="mt-1 flex text-xs leading-5 text-gray-500">
          {{ unit.screen.resolution ? unit.screen.resolution.measures : '---' }}
        </p>
        <div v-if="unit.status !== 'published'">
          <p class="text-right text-xs mb-2">
            {{ checkedSlots }} / {{ unit.slots.length }} slots
            {{ useHasRole('customer') ? 'cargados' : 'revisados' }}
          </p>
          <div class="w-full bg-gray-100 rounded-xl h-2">
            <div
              class="bg-orange-600 text-xs font-medium text-center p-0.5 leading-none rounded-xl h-2"
              :style="{
                width: (checkedSlots / unit.slots.length) * 100 + '%',
              }"
            ></div>
          </div>
        </div>
      </div>
    </div>
    <div class="flex items-center justify-between gap-x-4">
      <svg
        class="h-5 w-5 flex-none text-gray-400"
        viewBox="0 0 20 20"
        fill="currentColor"
        aria-hidden="true"
        data-slot="icon"
      >
        <path
          fill-rule="evenodd"
          d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
          clip-rule="evenodd"
        ></path>
      </svg>
    </div>
  </div>
</template>
