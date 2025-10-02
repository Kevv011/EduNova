<script setup>
import { XMarkIcon } from "@heroicons/vue/24/outline";
import Modal from "./Modal.vue";

const emit = defineEmits(["close"]);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "4sxl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit("close");
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-lg font-medium text-gray-900">
                        <slot name="title" />
                    </div>
                    <div class="text-sm text-gray-500">
                        <slot name="subtitle" />
                    </div>
                </div>
                <button
                    type="button"
                    class="focus:outline-none"
                    v-if="closeable"
                    @click="close"
                >
                    <XMarkIcon class="w-5 h-5 text-black" />
                </button>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <slot name="content" />
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-end">
            <slot name="footer" />
        </div>
    </Modal>
</template>
