<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";

const props = defineProps(["user"]);
</script>

<template>
    <AppLayout
        :title="'¡Bienvenido de vuelta! ' + props.user.name"
        :subtitle="
            props.user?.roles?.some((role) => role.name === 'estudiante')
                ? '¿Que te gustaria aprender hoy? Explora el contenido mas reciente dentro de EduNova'
                : 'Comparte tu conocimiento y transforma la experiencia educativa de tus alumnos.'
        "
    >
        <div>
            <div>
                <div class="overflow-hidden bg-white">
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
