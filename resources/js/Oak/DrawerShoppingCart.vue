<script setup>
import { onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
  show: { type: Boolean, default: false }, // v-model:show
  maxWidth: { type: String, default: 'xl' }, // 'sm'|'md'|'lg'|'xl'|'2xl'|'3xl'
  closeable: { type: Boolean, default: true },
});

const emit = defineEmits(['update:show']);

function requestClose() {
  if (props.closeable) emit('update:show', false);
}

function onKeydown(e) {
  if (e.key === 'Escape') requestClose();
}

// bloquear scroll al abrir
watch(
  () => props.show,
  (v) => {
    document.body.style.overflow = v ? 'hidden' : '';
  },
);

onMounted(() => document.addEventListener('keydown', onKeydown));
onUnmounted(() => {
  document.removeEventListener('keydown', onKeydown);
  document.body.style.overflow = '';
});

const widthClass =
  {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
    '3xl': 'sm:max-w-3xl',
  }[props.maxWidth] ?? 'sm:max-w-xl';
</script>

<template>
  <Teleport to="body">
    <!-- Overlay -->
    <Transition
      enter-active-class="transition-opacity duration-300 ease-linear"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-200 ease-linear"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="show"
        class="fixed inset-0 z-50 bg-gray-900/60"
        aria-hidden="true"
        @click="requestClose"
      />
    </Transition>

    <!-- Panel -->
    <Transition
      enter-active-class="transition duration-500 ease-in-out transform"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transition duration-500 ease-in-out transform"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div
        v-if="show"
        class="fixed inset-y-0 right-0 z-50 flex max-w-full pl-10"
        role="dialog"
        aria-modal="true"
      >
        <div class="w-screen pointer-events-auto" :class="widthClass">
          <div
            class="flex flex-col h-full bg-white divide-y divide-gray-200 shadow-xl"
          >
            <div class="flex-1 min-h-0 py-6 overflow-y-auto">
              <div class="px-4 sm:px-6">
                <div class="flex items-start justify-between">
                  <div class="text-base font-semibold text-gray-900">
                    <slot name="title" />
                  </div>
                  <div class="flex items-center ml-3 h-7">
                    <button
                      type="button"
                      class="relative text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      @click="requestClose"
                    >
                      <span class="absolute -inset-2.5" />
                      <span class="sr-only">Close panel</span>
                      <!-- simple X -->
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"
                        />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <div class="relative flex-1 px-4 mt-6 sm:px-6">
                <slot name="content" />
              </div>
            </div>

            <div class="flex justify-end flex-shrink-0 px-4 py-4">
              <slot name="footer" />
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>
