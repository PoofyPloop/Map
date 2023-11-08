<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Welcome" />

    <div class="max-h-screen overflow-hidden">
        <div class="bg-dots-darker bg-center bg-primary-400 selection:text-white py-4">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <div>
                    <img src="/assets/img/logo.png" alt="Cleron" class="h-6 block">
                </div>

                <div v-if="canLogin" class="text-right">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="font-semibold text-white hover:text-gray-100 focus:outline focus:outline-2 focus:rounded-sm"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="font-semibold text-white hover:text-gray-100 focus:outline focus:outline-2 focus:rounded-sm"
                            >Log in</Link
                        >

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="ml-4 font-semibold text-white hover:text-gray-100 focus:outline focus:outline-2 focus:rounded-sm"
                            >Register</Link
                        >
                    </template>
                </div>
            </div>
        </div>

        <div class="relative" style="height: calc(100vh - 55px);">
            <div>
                <img src="/assets/img/home-bg.jpg" alt="Cleron" class="w-full object-cover" />
            </div>
            <div class="bg-black opacity-40 absolute top-0 left-0 w-full h-full"></div>
            <div class="absolute bottom-16 left-20">
                <Link class="inline-flex items-center px-4 py-2 bg-white border border-transparent rounded-md font-semibold text-xs text-primay-400 uppercase tracking-widest hover:bg-gray-100 focus:bg-gray-100 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2 transition ease-in-out duration-150" :href="route('login')">Get Started</Link>
            </div>
        </div>
    </div>
</template>
