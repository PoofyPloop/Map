<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextareaInput from '@/Components/TextareaInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const contentInput = ref('');
const confirmingCommentDeletion = ref(false);
const editComment = ref(null);

const form = useForm({
    content: ''
});

const confirmCommentDeletion = () => {
    confirmingCommentDeletion.value = true;
};

const storeComment = () => {
    form.post(route('discussions.comment', usePage().props.discussion.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        },
        onError: () => {
            if (form.errors.content) {
                contentInput.value.focus();
            }
        },
    });
};

const editCommentData = (comment) => {
    editComment.value = comment;
    form.content = comment.content;
};

const cancelEditComment = () => {
    editComment.value = null;
    form.content = '';
};

const updateComment = () => {
    form.put(route('discussions.comment.update', {
        id: usePage().props.discussion.id,
        cid: editComment.value.id
    }), {
        preserveScroll: true,
        onSuccess: () => {
            editComment.value = null;
            form.reset()
        },
        onError: () => {
            if (form.errors.content) {
                contentInput.value.focus();
            }
        },
    });
};

const deleteComment = (id) => {
    form.delete(route('discussions.comment.delete', {
        id: usePage().props.discussion.id,
        cid: id
    }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => {},
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingCommentDeletion.value = false;
    form.reset();
};
</script>

<template>
    <Head title="Discussion" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Discussion</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="pb-4">
                            <h1 class="text-2xl font-semibold">{{ $page.props.discussion.title }}</h1>
                            <p class="text-sm text-gray-400">
                                Published by {{ $page.props.discussion.user.name }}
                            </p>
                        </div>

                        <div class="disc-content">
                            {{ $page.props.discussion.content }}
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <h2 class="text-xl font-semibold pb-2">
                        Comments
                    </h2>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                        <form @submit.prevent="storeComment" class="space-y-6">
                            <div>
                                <InputLabel for="content" value="Comment" />

                                <TextareaInput
                                    id="content"
                                    ref="contentInput"
                                    v-model="form.content"
                                    class="mt-1 block w-full"
                                />

                                <InputError :message="form.errors.content" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Comment</PrimaryButton>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                                        Comment Posted.
                                    </p>
                                </Transition>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-4" v-for="comment in $page.props.discussion.comments" :key="comment.id">
                        <div v-if="!editComment">
                            <p class="text-sm text-gray-400 pb-2">
                                {{ comment.user.name }} | {{ new Date(comment.created_at).toDateString() }}
                            </p>
                            <p>
                                {{ comment.content }}
                            </p>
                        </div>

                        <div class="flex pt-4 justify-end space-x-4 text-sm" v-if="!editComment">
                            <a href="#" class="text-gray-500 hidden" @click.prevent="editCommentData(comment)">Edit</a>
                            <a href="" class="text-red-500" @click.prevent="confirmCommentDeletion">Delete</a>
                        </div>

                        <form @submit.prevent="updateComment" class="space-y-6" v-else>
                            <div>
                                <InputLabel for="content" value="Comment" />

                                <TextareaInput
                                    id="content"
                                    ref="contentInput"
                                    v-model="form.content"
                                    class="mt-1 block w-full"
                                />

                                <InputError :message="form.errors.content" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                                <a href="#" @click.prevent="cancelEditComment" class="text-sm text-gray-400">Cancel</a>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                                        Comment Updated.
                                    </p>
                                </Transition>
                            </div>
                        </form>

                        <Modal :show="confirmingCommentDeletion" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Are you sure you want to delete the comment?
                                </h2>

                                <div class="mt-6 flex justify-end">
                                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                                    <DangerButton
                                        class="ml-3"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                        @click="deleteComment(comment.id)"
                                    >
                                        Delete Comment
                                    </DangerButton>
                                </div>
                            </div>
                        </Modal>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
