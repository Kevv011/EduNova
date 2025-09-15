<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Datepicker from '@vuepic/vue-datepicker';
import Select from '@/Oak/Select.vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { useForm } from '@inertiajs/vue3';
import { watch, computed } from 'vue';
import moment from 'moment';

const emits = defineEmits(['close']);

// Props definidas
const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maintenance: {
    type: Object,
    default: null,
  },
  closeable: {
    type: Boolean,
    default: true,
  },
  technicians: {
    type: Array,
  },
  showNotification: {
    type: Function,
    required: true,
  },
  currentUser: {
    type: Object,
    required: true,
  },
});

// Funcion para cerrar y resetear modal
function handleClose() {
  form.reset();
  if (props.closeable) emits('close');
}

const form = useForm({
  maintenance_id: props.maintenance?.id ?? null,
  assigned_technician: props.maintenance?.assigned_technician ?? null,
  priority: props.maintenance?.priority ?? null,
  date_job: props.maintenance?.date_job
    ? new Date(props.maintenance.date_job.replace('Z', ''))
    : null,
});

// Envio de los datos
const submit = () => {
  if (form.date_job) {
    form.date_job = moment(form.date_job).format('YYYY-MM-DD HH:mm:ss');
  }

  form.post(route('maintenances.storeTechnician'), {
    onSuccess: () => {
      handleClose();
      props.showNotification('Técnico asignado exitosamente.');
    },
  });
};

// Watcher para manejar los datos del modal por cada registro de la tabla
watch(
  () => props.maintenance,
  (newVal) => {
    if (!newVal) return;

    form.maintenance_id = newVal.id;
    form.assigned_technician = newVal.assigned_technician ?? null;
    form.priority = newVal.priority ?? null;
    form.date_job = newVal.date_job
      ? new Date(newVal.date_job.replace('Z', ''))
      : null;

    form.clearErrors();
  },
  { immediate: true },
);

// Watch para cuando se vuelve a abrir el mismo modal (mismo registro)
watch(
  () => props.show,
  (visible) => {
    if (visible && props.maintenance) {
      form.maintenance_id = props.maintenance.id;
      form.assigned_technician = props.maintenance.assigned_technician ?? null;
      form.priority = props.maintenance.priority ?? null;
      form.date_job = props.maintenance.date_job
        ? new Date(props.maintenance.date_job.replace('Z', ''))
        : null;

      form.clearErrors();
    }
  },
);

const techniciansWithSelf = computed(() => {
  const exists = props.technicians.some((t) => t.id === props.currentUser.id);
  const list = [...props.technicians];
  if (!exists) {
    list.unshift({
      id: props.currentUser.id,
      name: '+ ' + props.currentUser.name,
    });
  }
  return list;
});
</script>

<template>
  <Modal
    :show="show"
    @close="handleClose"
    max-width="lg"
    :closeable="closeable"
  >
    <div class="px-6 py-4">
      <div class="flex justify-end" v-if="closeable">
        <button type="button" class="focus:outline-none" @click="handleClose">
          <XMarkIcon class="w-5 h-5 text-black" />
        </button>
      </div>

      <div class="mb-4">
        <h2 class="text-lg font-semibold">Asignar técnico</h2>
      </div>

      <form @submit.prevent="submit">
        <!-- Tecnico -->
        <div>
          <InputLabel value="Técnico" class="mb-1" />
          <Select
            v-model="form.assigned_technician"
            :options="techniciansWithSelf"
            :reduce="(t) => t.id"
            :searchable="true"
            label="name"
            placeholder="Seleccionar un técnico disponible"
          ></Select>
          <InputError class="mt-2" :message="form.errors.assigned_technician" />
        </div>

        <!-- Prioridad -->
        <div class="col-span-6 mt-3">
          <InputLabel value="Prioridad" class="mb-1" />
          <Select
            v-model="form.priority"
            :options="[
              { id: 'alta', name: 'Alta' },
              { id: 'media', name: 'Media' },
              { id: 'baja', name: 'Baja' },
            ]"
            :reduce="(p) => p.id"
            :searchable="false"
            label="name"
            placeholder="Seleccionar prioridad del mantenimiento"
          ></Select>
          <InputError :message="form.errors.priority" class="mt-2" />
        </div>

        <!-- Fecha a realizar el mantenimiento -->
        <div class="mt-4">
          <InputLabel value="Fecha a realizar el mantenimiento" class="mb-1" />
          <Datepicker
            v-model="form.date_job"
            :format="'dd-MM-yyyy HH:mm'"
            :enable-time-picker="true"
            auto-apply
            :min-date="new Date()"
            :flow="['calendar', 'time']"
            :utc="false"
            placeholder="Selecciona una fecha"
          />
          <InputError class="mt-2" :message="form.errors.date_job" />
        </div>

        <div class="mt-6">
          <PrimaryButton
            class="justify-center w-full"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Confirmar
          </PrimaryButton>
        </div>
      </form>
    </div>
  </Modal>
</template>
