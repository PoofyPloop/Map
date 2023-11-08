<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Discussions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Discussions</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <p class="text-2xl font-semibold text-gray-600 pb-2">Discussions</p>
                <Link
                    href="/discussions/create"
                    class="primary-button mb-4"
                >
                    Add New
                </Link>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4" v-for="disc in $page.props.discussions" :key="disc.id">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold pb-4">{{ disc.title }}</h2>
                        <p>{{ disc.content.substr(0, 250) }}...</p>

                        <div class="flex items-center space-x-4 mt-4">
                            <Link
                                :href="`/discussions/${disc.id}`"
                                class="primary-button"
                            >
                                Read More
                            </Link>

                            <p class="text-sm text-gray-400">Comments Posted: {{ disc.comments.length }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
