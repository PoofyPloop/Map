<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const questionInput = ref('');
const typeInput = ref('textbox');
const scoreInput = ref('0');
const option1Input = ref('');
const option2Input = ref('');
const option3Input = ref('');
const option4Input = ref('');
const answerInput = ref('option1');
const confirmingQuestionDeletion = ref(false);

const form = useForm({
    quiz_id: usePage().props.quiz.id,
    question: '',
    type: 'mcq',
    score: 0,
    option1: '',
    option2: '',
    option3: '',
    option4: '',
    answer: 'option1'
});

const addQuestion = () => {
    form.post(route('question.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.question) {
                questionInput.value.focus();
            }
        },
    });
};

const form2 = useForm({});

const confirmQuestionDeletion = () => {
    confirmingQuestionDeletion.value = true;
};

const deleteQuestion = (id) => {
    form.delete(route('question.destroy', id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => {},
        onFinish: () => form2.reset(),
    });
};

const closeModal = () => {
    confirmingQuestionDeletion.value = false;
    form.reset();
};
</script>

<template>
    <Head title="Show Quiz" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Question - Show</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between">
                            <h2 class="title-h2">Question</h2>
                        </div>

                        <div class="pt-4" v-if="$page.props.quiz">
                            <p>{{ $page.props.quiz.title }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-xs pb-2">Questions</p>
                            <div class="bg-gray-200 rounded-md p-3 flex items-center justify-between mt-4" v-for="question in $page.props.quiz.questions" :key="question.id">
                                <p>{{ question.text }}</p>

                                <DangerButton @click="confirmQuestionDeletion">Delete Account</DangerButton>

                                <Modal :show="confirmingQuestionDeletion" @close="closeModal">
                                    <div class="p-6">
                                        <h2 class="text-lg font-medium text-gray-900">
                                            Are you sure you want to delete this question?
                                        </h2>

                                        <div class="mt-6 flex justify-end">
                                            <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                                            <DangerButton
                                                class="ml-3"
                                                :class="{ 'opacity-25': form2.processing }"
                                                :disabled="form2.processing"
                                                @click="deleteQuestion(question.id)"
                                            >
                                                Delete Question
                                            </DangerButton>
                                        </div>
                                    </div>
                                </Modal>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between">
                            <h2 class="title-h2">Add Question</h2>
                        </div>

                        <div class="pt-4">
                            <form @submit.prevent="addQuestion" class="mt-6 space-y-6">
                                <div>
                                    <InputLabel for="question" value="Question" />

                                    <textarea ref="questionInput" id="question" v-model="form.question"></textarea>

                                    <InputError :message="form.errors.category" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-between">
                                    <p>Answer Type</p>

                                    <label>
                                        <input type="radio" ref="typeInput" v-model="form.type" value="textbox">
                                        Textbox
                                    </label>

                                    <label>
                                        <input type="radio" ref="typeInput" v-model="form.type" value="mcq">
                                        Multiple Choice
                                    </label>

                                    <div class="flex items-center space-x-2">
                                        <p>Points</p>
                                        <input type="number" ref="scoreInput" class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm w-full mt-1" v-model="form.score">
                                    </div>
                                </div>

                                <div v-if="form.type == 'mcq'">
                                    <div class="grid grid-cols-12 gap-6">
                                        <div class="col-span-12 md:col-span-6">
                                            <InputLabel for="option1" value="Option 1" />

                                            <TextInput
                                                id="option1"
                                                ref="option1Input"
                                                v-model="form.option1"
                                                type="text"
                                                class="mt-1 block w-full"
                                            />

                                            <InputError :message="form.errors.option1" class="mt-2" />
                                        </div>
                                        
                                        <div class="col-span-12 md:col-span-6">
                                            <InputLabel for="option2" value="Option 2" />

                                            <TextInput
                                                id="option2"
                                                ref="option2Input"
                                                v-model="form.option2"
                                                type="text"
                                                class="mt-1 block w-full"
                                            />

                                            <InputError :message="form.errors.option2" class="mt-2" />
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <InputLabel for="option3" value="Option 3" />

                                            <TextInput
                                                id="option3"
                                                ref="option3Input"
                                                v-model="form.option3"
                                                type="text"
                                                class="mt-1 block w-full"
                                            />

                                            <InputError :message="form.errors.option3" class="mt-2" />
                                        </div>

                                        <div class="col-span-12 md:col-span-6">
                                            <InputLabel for="option4" value="Option 4" />

                                            <TextInput
                                                id="option4"
                                                ref="option4Input"
                                                v-model="form.option4"
                                                type="text"
                                                class="mt-1 block w-full"
                                            />

                                            <InputError :message="form.errors.option4" class="mt-2" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="answer" value="Answer" />

                                    <select ref="answerInput" v-model="form.answer">
                                        <option value="option1" selected>
                                            Option 1
                                        </option>
                                        <option value="option2">
                                            Option 2
                                        </option>
                                        <option value="option3">
                                            Option 3
                                        </option>
                                        <option value="option4">
                                            Option 4
                                        </option>
                                    </select>

                                    <InputError :message="form.errors.category" class="mt-2" />
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
