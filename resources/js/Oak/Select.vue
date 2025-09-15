<script setup>
import { onMounted } from "vue";

const emits = defineEmits([
    "update:modelValue",
    "search",
    "option:selected",
    "prepared",
]);

defineOptions({
    inheritAttrs: false,
});

defineProps(["modelValue"]);

const onSearch = (searchQuery) => {
    if (searchQuery) {
        emits("search", searchQuery);
    }
};

const onSelect = (option) => {
    emits("option:selected", option);
};

onMounted(() => {
    setTimeout(() => {
        emits("prepared");
    }, 500);
});
</script>

<template>
    <Multiselect
        class="w-full"
        :modelValue="modelValue"
        v-bind="$attrs"
        @update:modelValue="$emit('update:modelValue', $event)"
        @search-change="onSearch"
        @select="onSelect"
        :searchable="true"
        :close-on-select="true"
        :clear-on-select="false"
        placeholder="Seleccionar opción..."
        no-results-text="No se encontró ningún resultado"
        no-options-text="No hay opciones disponibles"
    >
        <!-- Slot para opciones personalizadas -->
        <template v-if="$slots.option" #option="{ option }">
            <slot name="option" :option="option" />
        </template>

        <!-- Slot para elemento seleccionado -->
        <template v-if="$slots['selected-option']" #singleLabel="{ option }">
            <slot name="selected-option" :option="option" />
        </template>

        <!-- Slot para múltiples elementos seleccionados -->
        <template
            v-if="$slots['selected-option']"
            #tag="{ option, handleTagRemove, disabled }"
        >
            <slot
                name="selected-option"
                :option="option"
                :handleTagRemove="handleTagRemove"
                :disabled="disabled"
            />
        </template>
    </Multiselect>
</template>

<style>
/* Estilos personalizados para que se vea similar a v-select */
.multiselect {
    min-height: 40px;
}

.multiselect.is-open {
    border-radius: 4px 4px 0 0;
}

.multiselect.is-open .multiselect-dropdown {
    border-radius: 0 0 4px 4px;
}
</style>
