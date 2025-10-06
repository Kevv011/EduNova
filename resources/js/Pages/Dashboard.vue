<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import WelcomeStudent from "@/Components/WelcomeStudent.vue";
import { computed } from "vue";

const props = defineProps(["user", "courses", "coursesStudent", "categories"]);

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
            isInstructor
                ? 'Comparte tu conocimiento y transforma la experiencia educativa de tus alumnos.'
                : '¿Que te gustaria aprender hoy? Explora el contenido mas reciente dentro de EduNova'
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
                    <WelcomeStudent v-else :user="props.user" :courses="props?.coursesStudent" :categories="props?.categories" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
