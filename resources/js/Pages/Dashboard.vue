<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";

const props = defineProps(["user"]);
</script>

<template>
    <AppLayout
        :title="'¡Bienvenido de vuelta! ' + props.user.name"
        subtitle="¿Que te gustaria aprender hoy? Explora el contenido mas reciente dentro de EduNova"
    >
        <div class="py-12">
            <div>
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
