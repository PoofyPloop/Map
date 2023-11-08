<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" v-if="$page.props.auth.user.role == 1">
                <p class="text-gray-600 pb-4">Quiz Statistics</p>

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="flex justify-between">
                                    <p>Completed</p>
                                    <p class="text-4xl">4</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="flex justify-between">
                                    <p>Average</p>
                                    <p class="text-4xl">2</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8" v-if="$page.props.auth.user.role == 2">
                <div>
                    <p class="text-2xl font-semibold text-gray-600 pb-2">Statistics</p>
                    <Link
                        href="/quiz"
                        class="primary-button"
                    >
                        All Quiz
                    </Link>

                    <div class="grid grid-cols-12 gap-6 pt-6">
                        <div class="col-span-6">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900">
                                    <div class="flex justify-between">
                                        <p>Quizzes created</p>
                                        <p class="text-4xl">{{ $page.props.quizzes ? $page.props.quizzes.length : 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-6">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900">
                                    <div class="flex justify-between">
                                        <p>Problems fixed</p>
                                        <p class="text-4xl">2</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-10">
                    <p class="text-gray-600 pb-4">Reports</p>

                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-12">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900">
                                    <div class="flex justify-between">
                                        <p>Quizzes created</p>
                                        <p class="text-4xl">4</p>
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
