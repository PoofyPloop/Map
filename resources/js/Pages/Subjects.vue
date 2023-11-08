<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subjects</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-6" v-for="sub in $page.props.subjects" :key="sub.id">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <p class="pb-6">{{ sub.title }}</p>

                                <div class="quizzes flex items-center justify-between border rounded border-gray-200 p-3" v-for="quiz in sub.quizzes" :key="quiz.id">
                                    <div>{{ quiz.title }}</div>

                                    <div class="flex items-center" v-if="$page.props.auth.user.role == 1">
                                        <Link
                                            :href="`/quiz/test/${quiz.id}`"
                                            class="primary-button"
                                        >
                                            Take a test
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
