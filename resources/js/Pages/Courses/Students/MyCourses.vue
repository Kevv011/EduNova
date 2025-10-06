<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed } from "vue";
import CourseCardLarge from "@/Components/CourseCardLarge.vue";

// Props
const props = defineProps({
    courses: {
        type: Object,
    },
});

// Normalizar data
const courseData = computed(() => props.courses?.data ?? props.courses ?? []);
</script>
<template>
    <AppLayout
        title="Mis cursos"
        subtitle="Consulta y gestiona los cursos en los que estás inscrito."
        :pages="[
            {
                name: 'Mis cursos',
                href: route('students.courses'),
                current: true,
            },
        ]"
    >
        <div class="px-6 py-10">
            <div
                v-if="!courseData.length"
                class="py-12 text-center text-gray-500"
            >
                <BookOpenIcon class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <p class="text-lg">No estás inscrito en ningún curso.</p>
            </div>

            <div v-else class="space-y-6">
                <CourseCardLarge
                    v-for="course in courseData"
                    :key="course.id"
                    :course="course"
                />
            </div>
        </div>
    </AppLayout>
</template>
