<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { PlusIcon } from "@heroicons/vue/24/outline";
import SubjectCard from "@/Components/SubjectCard.vue";

// Props
const props = defineProps({
    courses: { type: Array, required: true },
    categories: { type: Array, required: true },
});
</script>
<template>
    <AppLayout
        title="Materias impartidas"
        subtitle="Visualiza las asignaturas que impartes respecto al contenido de tus cursos publicados como docente"
        :pages="[
            {
                name: 'Mis cursos',
                href: route('courses.index'),
                current: false,
            },
        ]"
    >
        <div v-if="!categories.length">
            <div class="flex flex-col items-center mt-10">
                <img src="/images/EmptySubject.png" alt="empty" width="500" />
                <PrimaryButton
                    class="max-w-md mt-3"
                    tag="a"
                    :href="route('courses.index')"
                >
                    <PlusIcon class="size-5 me-1" />
                    Crear un curso
                </PrimaryButton>
            </div>
        </div>
        <div v-else>
            <SubjectCard
                v-for="category in categories"
                :key="category.id"
                :category="category"
                :courses="courses"
            />
        </div>
    </AppLayout>
</template>
