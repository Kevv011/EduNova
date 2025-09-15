<script setup>
const props = defineProps(["loggers", "campaign"]);
</script>
<template>
    <section>
        <div class="overflow-hidden">
            <div class="p-6">
                <h2 class="text-base font-medium text-gray-900">
                    Pasos de registro
                </h2>
                <div class="flow-root mt-6">
                    <ul role="list" class="-mb-8">
                        <li
                            v-for="(logger, loggerIdx) in loggers"
                            :key="logger.id"
                        >
                            <div class="relative pb-8">
                                <span
                                    v-if="loggerIdx !== loggers.length - 1"
                                    class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"
                                    aria-hidden="true"
                                />
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span
                                            :class="[
                                                'flex h-8 w-8 items-center justify-center rounded-full ring-8 ring-white bg-orange-600 text-white',
                                            ]"
                                        >
                                            {{ logger.order }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div
                                            class="flex justify-between space-x-4 pt-1.5"
                                        >
                                            <div>
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ logger.name }}
                                                </p>
                                            </div>
                                            <div
                                                class="text-sm text-right text-gray-500 whitespace-nowrap"
                                            >
                                                <span
                                                    class="px-2 font-medium rounded-xl"
                                                    :class="{
                                                        'bg-green-100 text-green-600':
                                                            logger.status ===
                                                            'Completado',
                                                        'bg-yellow-100 text-yellow-800':
                                                            logger.status ===
                                                            'En progreso',
                                                        'bg-gray-100 text-gray-900':
                                                            logger.status ===
                                                            'Pendiente',
                                                    }"
                                                    >{{ logger.status }}</span
                                                >
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs">
                                            <p>{{ logger.message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>
