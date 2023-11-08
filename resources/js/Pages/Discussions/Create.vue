<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const titleInput = ref('');
const contentInput = ref('');

const form = useForm({
    title: '',
    content: ''
});

const storeDiscussion = () => {
    form.post(route('discussions.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            // alert('Discussion Created!')
        },
        onError: () => {
            if (form.errors.title) {
                titleInput.value.focus();
            }
            if (form.errors.content) {
                contentInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <Head title="Create Discussion" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Discussion - Create</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between">
                            <h2 class="title-h2">Create Discussion</h2>
                            <Link href="/discussions" class="text-sm text-primary-500">Back to all discussions</Link>
                        </div>

                        <div>
                            <form @submit.prevent="storeDiscussion" class="mt-6 space-y-6">
                                <div>
                                    <InputLabel for="title" value="Discussion Title" />

                                    <TextInput
                                        id="title"
                                        ref="titleInput"
                                        v-model="form.title"
                                        type="text"
                                        class="mt-1 block w-full"
                                    />

                                    <InputError :message="form.errors.title" class="mt-2" />
                                </div>

                                <div>
                                    <InputLabel for="content" value="Content" />

                                    <TextareaInput
                                        id="content"
                                        ref="contentInput"
                                        v-model="form.content"
                                        class="mt-1 block w-full"
                                    />

                                    <InputError :message="form.errors.content" class="mt-2" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                                    <Transition
                                        enter-active-class="transition ease-in-out"
                                        enter-from-class="opacity-0"
                                        leave-active-class="transition ease-in-out"
                                        leave-to-class="opacity-0"
                                    >
                                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                                    </Transition>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
