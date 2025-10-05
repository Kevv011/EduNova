<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import { computed } from "vue";

const props = defineProps(["user", "courses"]);

const isInstructor = computed(() => {
    return (
        props.user.roles?.some((role) => role.name === "instructor") ?? false
    );
});
</script>

<template>
    <AppLayout
        :title="'¡Bienvenido/a! ' + props.user.name"
        :subtitle="
            props.user?.roles?.some((role) => role.name === 'estudiante')
                ? '¿Que te gustaria aprender hoy? Explora el contenido mas reciente dentro de EduNova'
                : 'Comparte tu conocimiento y transforma la experiencia educativa de tus alumnos.'
        "
    >
        <div>
            <div>
                <div class="overflow-hidden bg-white">
                    <Welcome
                        :courses="props?.courses"
                        :user="props.user"
                        v-if="isInstructor"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
