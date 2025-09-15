<script setup>
import { computed } from "vue";

const emits = defineEmits(['close'])

const props = defineProps({
  art: {
    type: Object,
    required: true, // { url: "...", mime_type: "image/jpeg" | "video/mp4" | etc }
  },
  screenWidth: {
    type: Number,
    required: true,
  },
  screenHeight: {
    type: Number,
    required: true,
  },
});

const isPortrait = computed(() => props.screenHeight > props.screenWidth);

const isVideo = computed(() => props.art.mime_type?.startsWith("video/"));
</script>

<template>
  <div
    class="relative bg-black overflow-hidden border border-gray-600 mx-auto cursor-pointer"
    @click="$emit('click')"
    :style="{
      aspectRatio: `${screenWidth} / ${screenHeight}`,
      maxWidth: '100%',
      maxHeight: '100%'
    }"
  >
    <video
      v-if="isVideo"
      :src="art.url"
      class="w-full h-full object-contain"
      autoplay
      loop
      muted
      playsinline
    />
    <img
      v-else
      :src="art.url"
      alt="Arte"
      class="w-full h-full object-contain"
    />
  </div>
</template>
