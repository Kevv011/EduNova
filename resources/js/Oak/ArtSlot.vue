<script setup>
import { onMounted, ref } from 'vue';
import FileInput from './FileInput.vue';
import { router, useForm } from '@inertiajs/vue3';
import { TransitionRoot } from '@headlessui/vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import OakTextarea from '@/Oak/Textarea.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import { useHasPermission } from '@/composables/useHasPermission';

const props = defineProps(['slot', 'campaign', 'unit']);

const emits = defineEmits(['artLoaded']);

const showingInput = ref(false);

let url = route('api.images.store', {
  imageableType: 'slot',
  imageable: props.slot?.id,
});

const setUrlToUpdate = () => {
  url = route('api.images.update', {
    image: props.slot.image?.id,
  });
};

const setUrlToStore = () => {
  url = route('api.images.store', {
    imageableType: 'slot',
    imageable: props.slot?.id,
  });
};

const artLoaded = () => {
  router.reload({
    onFinish: () => {
      emits('artLoaded');
      showingInput.value = false;
    },
  });
};

onMounted(() => {
  if (!props.slot.image) {
    showingInput.value = true;

    return;
  }

  showingInput.value = props.slot.image ? false : true;
});

const replaceArt = () => {
  setUrlToUpdate();
  showingInput.value = true;
};

const cancelUpdate = () => {
  setUrlToStore();
  showingInput.value = false;
};

const showingModalToRejectArt = ref(false);

const formReject = useForm({
  observations: null,
});

const rejectArt = () => {
  formReject.put(
    route('campaigns.units.slots.reject', {
      campaign: props.campaign.id,
      unit: props.unit.id,
      slot: props.slot.id,
    }),
    {
      onSuccess: () => {
        showingModalToRejectArt.value = false;
        formReject.reset();
        emits('artLoaded');
      },
    },
  );
};

const approving = ref(false);

const approve = () => {
  approving.value = true;

  router.put(
    route('campaigns.units.slots.approve', {
      campaign: props.campaign.id,
      unit: props.unit.id,
      slot: props.slot.id,
    }),
    {},
    {
      onSuccess: () => {
        emits('artLoaded');
      },
      onFinish: () => {
        approving.value = false;
      },
    },
  );
};

const uploading = ref(false);

const uploadArt = () => {
  uploading.value = true;

  router.put(
    route('campaigns.units.slots.upload-content', {
      campaign: props.campaign.id,
      unit: props.unit.id,
      slot: props.slot.id,
    }),
    {},
    {
      onFinish: () => {
        uploading.value = false;
      },
    },
  );
};
</script>
<template>
  <div class="mb-3 flex justify-end" v-if="!slot.uploaded && slot.image">
    <PrimaryButton
      @click="uploadArt"
      :disabled="uploading"
      v-if="useHasPermission('upload_arts_to_player') && slot.status != 'rejected'"
    >
      Cargar arte en Broadsign
    </PrimaryButton>
  </div>
  <div class="mb-3" v-if="slot.status === 'rejected' && slot.observations">
    <p class="text-base text-red-500 font-semibold">{{ slot.observations }}</p>
  </div>
  <div class="h-80 rounded-lg overflow-hidden" v-if="!showingInput">
    <video
      class="w-full h-full"
      controls
      v-if="['mp4', 'mov'].includes(slot.image?.extension)"
    >
      <source
        :src="`/storage/${slot.image?.path}`"
        :type="`video/${slot.image?.extension}`"
      />
      Tu navegador no soporta la etiqueta de video.
    </video>
    <img
      :src="`/storage/${slot.image?.path}`"
      class="w-full h-full rounded-lg object-cover"
      :alt="slot.image?.name"
      v-else
    />
  </div>
  <div v-else>
    <FileInput
      :url="url"
      @loaded="artLoaded"
      v-if="useHasPermission('upload_arts')"
    />
  </div>
  <div class="flex justify-end mt-4 space-x-3">
    <SecondaryButton
      @click="cancelUpdate"
      v-if="showingInput && slot.status === 'pending_authorization'"
    >
      Cancelar
    </SecondaryButton>
    <SecondaryButton
      @click="replaceArt"
      v-if="
        !showingInput &&
        ['pending_authorization', 'rejected'].includes(slot.status) &&
        useHasPermission('upload_arts')
      "
    >
      Reemplazar
    </SecondaryButton>

    <SecondaryButton
      @click="showingModalToRejectArt = true"
      v-if="
        slot.status === 'pending_authorization' &&
        useHasPermission('reject_arts')
      "
    >
      Rechazar arte
    </SecondaryButton>

    <PrimaryButton
      @click="approve"
      :disabled="approving"
      v-if="
        slot.status === 'pending_authorization' &&
        useHasPermission('approve_arts')
      "
    >
      Aprobar arte
    </PrimaryButton>
  </div>

  <ConfirmationModal
    :show="showingModalToRejectArt"
    @close="showingModalToRejectArt = false"
    max-width="md"
    type="error"
  >
    <template #title>Rechazar arte</template>
    <template #content>
      Estás a punto de rechazar el arte, ¿deseas continuar?

      <div class="my-2">
        <InputLabel value="Comentario" />
        <OakTextarea
          rows="3"
          class="w-full mt-1"
          v-model="formReject.observations"
        />
        <InputError :message="formReject.errors.observations" />
      </div>
    </template>
    <template #footer>
      <SecondaryButton
        class="justify-center"
        @click="showingModalToRejectArt = false"
      >
        Cancelar
      </SecondaryButton>
      <DangerButton
        class="ml-2 justify-center"
        :disabled="formReject.processing"
        @click="rejectArt"
      >
        Rechazar arte
      </DangerButton>
    </template>
  </ConfirmationModal>
</template>
