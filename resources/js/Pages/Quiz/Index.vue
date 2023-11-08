<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';

const confirmingQuizDeletion = ref(false);

const form = useForm({});

const confirmQuizDeletion = () => {
    confirmingQuizDeletion.value = true;
};

const deleteQuiz = (id) => {
    form.delete(route('quiz.destroy', id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => {},
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingQuizDeletion.value = false;
    form.reset();
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Quizzes</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 bg-white py-6">
                <div>
                    <h3 class="title-h3 text-gray-600 pb-2">All Quizzes</h3>
                    <Link
                        href="/quiz/create"
                        class="primary-button"
                    >
                        Create New Quiz
                    </Link>

                    <div class="mt-6 list-style-none">
                        <div v-for="quiz in $page.props.quizzes" :key="quiz.id" class="p-4 bg-gray-200 overflow-hidden shadow-sm sm:rounded-lg mt-3 flex items-center justify-between">
                            <p>{{ quiz.title }}</p>

                            <div class="flex items-center space-x-2">
                                <a :href="`/quiz/${quiz.id}`" class="primary-button">Questions</a>

                                <a :href="`/quiz/${quiz.id}/edit`" class="primary-button">Edit Quiz</a>

                                <DangerButton @click="confirmQuizDeletion">Delete Account</DangerButton>

                                <Modal :show="confirmingQuizDeletion" @close="closeModal">
                                    <div class="p-6">
                                        <h2 class="text-lg font-medium text-gray-900">
                                            Are you sure you want to delete this quiz?
                                        </h2>

                                        <div class="mt-6 flex justify-end">
                                            <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                                            <DangerButton
                                                class="ml-3"
                                                :class="{ 'opacity-25': form.processing }"
                                                :disabled="form.processing"
                                                @click="deleteQuiz(quiz.id)"
                                            >
                                                Delete Quiz
                                            </DangerButton>
                                        </div>
                                    </div>
                                </Modal>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
