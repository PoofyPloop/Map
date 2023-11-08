<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const form = useForm({
    answers: []
});

const testQuiz = () => {
    // verify if all questions are answered
    if(usePage().props.quiz.questions.length == form.answers.length) {
        form.post(route('quiz.result', usePage().props.quiz.id), {
            preserveScroll: true,
            onSuccess: (res) => {
                console.log(res.props)
                form.reset()
            },
            onError: () => {
                alert('An error occured!')
            },
        });
    } else {
        alert('Please answer all the questions to submit.')
    }
};
</script>

<template>
    <Head title="Quiz" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quiz</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p class="pb-6 text-xl font-semibold">{{ $page.props.quiz.title }}</p>

                        <form @submit.prevent="testQuiz" class="space-y-6">
                            <div 
                                class="ques border rounded-md border-gray-200 p-4" 
                                v-for="(ques, index) in $page.props.quiz.questions" 
                                :key="ques.id"
                            >
                                <div class="pb-2">{{ index + 1 }}. {{ ques.text }}</div>

                                <select :id="`ques${ques.id}`" v-model="form.answers[index]">
                                    <option 
                                        v-for="(op, index) in JSON.parse(ques.answer.choices)" 
                                        :key="index" 
                                        :value="op"
                                    >
                                        {{ op }}
                                    </option>
                                </select>
                            </div>

                            <div class="flex items-center gap-4" v-if="$page.props.quiz.questions.length == form.answers.length">
                                <PrimaryButton :disabled="form.processing">Submit</PrimaryButton>

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
    </AuthenticatedLayout>
</template>
