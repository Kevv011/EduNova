<template>
  <file-pond
    ref="pond"
    name="attachment"
    :label-idle="`<div
                  class='flex flex-col relative justify-center items-center'
                >
                  <svg
                    xmlns='http://www.w3.org/2000/svg'
                    fill='none'
                    viewBox='0 0 24 24'
                    stroke-width='1.5'
                    stroke='currentColor'
                    class='text-gray-500 w-10 h-10'
                  >
                    <path
                      stroke-linecap='round'
                      stroke-linejoin='round'
                      d='M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z'
                    />
                  </svg>
                  <p class='mt-6 text-gray-500'>Presiona o arrastra tus archivos para insertar.</p>
                  <p class='text-sm mt-3'>Max. File Size: 8MB</p>
                </div>`"
    label-file-processing-complete="Archivo cargado"
    label-tap-to-undo="Haga clic para deshacer"
    label-file-processing="Cargando..."
    label-tap-to-cancel="Haga clic para cancelar"
    label-tap-to-retry="Haga clic para reintentar"
    :accepted-file-types="acceptedFiles"
    :allow-image-preview="allowImagePreview"
    :allow-remove="false"
    :allow-multiple="allowMultiple"
    max-file-size="8MB"
  />
</template>
<script setup>
import axios from 'axios';
import vueFileIPond, { setOptions } from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import { ref } from 'vue';

const FilePond = vueFileIPond(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview,
);

let serverMessage = {};

const props = defineProps({
  acceptedFiles: {
    type: Array,
    default: [
      'image/*',
      'video/*',
      'application/pdf',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      'application/vnd.ms-excel',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ],
  },
  url: {
    type: String,
    required: true,
  },
  allowMultiple: {
    type: Boolean,
    default: true,
  },
  allowImagePreview: {
    type: Boolean,
    default: false,
  },
  keepName: {
    type: Boolean,
    default: false,
  },
  collectionName: {
    type: String,
    default: 'default',
  },
  disk: {
    type: String,
    default: 'public',
  },
  baseRouteDelete: {
    type: String,
    default: '/api/attachments',
  },
  clearValues: {
    type: Boolean,
    default: false,
  },
});

const emits = defineEmits(['loaded', 'removed']);
const pond = ref(null);

setOptions({
  server: {
    process: (
      fieldName,
      file,
      metadata,
      load,
      error,
      progress,
      abort,
      transfer,
      options,
    ) => {
      const formData = new FormData();
      formData.append(fieldName, file, file.name);
      formData.append('disk', props.disk);
      formData.append('keep-name', props.keepName);
      formData.append('collection', props.collectionName);

      const controller = new AbortController();

      const request = axios
        .post(props.url, formData, {
          onUploadProgress: (e) => {
            progress(e.lengthComputable, e.loaded, e.total);
          },
          signal: controller.signal,
        })
        .then((response) => {
          if (response.status >= 200 && response.status < 300) {
            load(response.data.id);
            setTimeout(() => {
              emits('loaded');

              if (pond.value && props.clearValues) {
                pond.value.removeFiles();
              }
            }, 1000);
          }
        })
        .catch((er) => {
          error(er.response);
        });

      return {
        abort: () => {
          request.abort();
          abort();
        },
      };
    },
    revert: (uniqueFileId, load, error) => {
      axios
        .delete(`${props.baseRouteDelete}/${uniqueFileId}`)
        .then((response) => {
          load();
          emits('removed', uniqueFileId);
        })
        .catch((e) => {
          error(e.response.message ?? e.message);
        });
    },
  },

  labelFileProcessingError: () => {
    return serverMessage.message;
  },
});
</script>
<style>
.filepond--drop-label {
  min-height: 24rem; /* h-96 */
  height: auto !important;
  width: 100% !important;
  padding: 0 !important;
}

.filepond--drop-label > div {
  height: 100%;
  width: 100%;
}
</style>
