<script setup lang="ts">
import { computed } from 'vue';

type Props = {
  /** Valor actual (puede superar max; el arco se satura si capAt100=true) */
  value: number;
  /** Máximo para calcular el % */
  max?: number;
  /** Ancho del SVG (alto se calcula a la mitad) */
  size?: number;
  /** Grosor del anillo */
  thickness?: number;
  /** Color del progreso */
  color?: string;
  /** Color del track (restante) */
  trackColor?: string;
  /** Decimales para el texto del % */
  precision?: number;
  /** Punta redondeada del trazo */
  rounded?: boolean;
  /** Si true, el arco no pasa del 100% aunque el valor lo supere */
  capAt100?: boolean;
  /** Clase extra para el texto central */
  labelClass?: string;
};

const props = withDefaults(defineProps<Props>(), {
  max: 100,
  size: 180,
  thickness: 18,
  color: '#f97316', // Tailwind orange-500 aprox
  trackColor: '#fde7df', // un naranja muy claro
  precision: 0,
  rounded: true,
  capAt100: true,
  labelClass: 'text-xl font-semibold fill-current',
});

/** Geometría del arco */
const r = computed(() => props.size / 2 - props.thickness / 2);
const cx = computed(() => props.size / 2);
const cy = computed(() => props.size / 2);
/** Semicírculo superior de izquierda a derecha */
const arcPath = computed(
  () =>
    `M ${cx.value - r.value} ${cy.value} A ${r.value} ${r.value} 0 1 1 ${
      cx.value + r.value
    } ${cy.value}`,
);

/** % “real” para el label y % “pintado” para el arco */
const pctRaw = computed(() =>
  props.max! <= 0 ? 0 : (props.value / props.max!) * 100,
);
const pctPaint = computed(() =>
  props.capAt100 ? Math.min(100, Math.max(0, pctRaw.value)) : pctRaw.value,
);

/** Texto del porcentaje */
const pctLabel = computed(() => `${pctRaw.value.toFixed(props.precision)}%`);

/** Altura del SVG (un poco más que la mitad para que quepa el trazo) */
const height = computed(() => props.size / 2 + props.thickness / 6);
</script>

<template>
  <figure
    class="inline-flex flex-col items-center justify-start -mt-4"
    role="img"
    :aria-label="`Progreso: ${pctLabel}`"
  >
    <svg
      :width="size"
      :height="height"
      :viewBox="`0 0 ${size} ${height}`"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <!-- Track -->
      <path
        :d="arcPath"
        pathLength="100"
        :stroke="trackColor"
        :stroke-width="thickness"
        :stroke-linecap="rounded ? 'round' : 'butt'"
      />

      <!-- Progreso -->
      <path
        :d="arcPath"
        pathLength="100"
        :stroke="color"
        :stroke-width="thickness"
        :stroke-linecap="rounded ? 'round' : 'butt'"
        :style="{ transition: 'stroke-dasharray 600ms ease' }"
        :stroke-dasharray="`${pctPaint} 100`"
      />
    </svg>

    <!-- Etiqueta central (dentro del semicírculo) -->
    <div
      class="pointer-events-none -mt-7"
      :style="{ '--label-shift': `${thickness / 2}px` }"
      aria-hidden="true"
    >
      <svg :width="size" :height="0" style="position: absolute; opacity: 0" />
      <span :class="labelClass">{{ pctLabel }}</span>
    </div>
  </figure>
</template>
