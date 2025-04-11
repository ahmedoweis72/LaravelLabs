<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    post: Object,
});

const form = useForm({
    content: '',
});

const showCommentForm = ref(false);
const editingComment = ref(null);
const editForm = useForm({
    content: '',
});

const submitComment = () => {
    form.post(route('posts.comments.store', props.post.slug), {
        onSuccess: () => {
            form.reset();
            showCommentForm.value = false;
        },
    });
};

const editComment = (comment) => {
    editingComment.value = comment.id;
    editForm.content = comment.content;
};

const updateComment = (commentId) => {
    editForm.put(route('posts.comments.update', [props.post.slug, commentId]), {
        onSuccess: () => {
            editingComment.value = null;
            editForm.reset();
        },
    });
};

const deleteComment = (commentId) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        useForm().delete(route('posts.comments.destroy', [props.post.slug, commentId]));
    }
};
</script>

<template>
    <Head :title="post.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Post Details</h2>
                <div class="flex space-x-3">
                    <Link :href="route('posts.index')" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to Posts
                    </Link>
                    <Link :href="route('posts.edit', post.id)" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Edit Post
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ post.title }}</h1>
                            <p class="text-sm text-gray-500 mb-4">
                                By {{ post.user.name }} • {{ new Date(post.created_at).toLocaleDateString() }}
                            </p>
                            
                            <!-- Post Image -->
                            <div class="mb-6">
                                <div v-if="post.image" class="rounded-lg overflow-hidden shadow-md max-w-2xl bg-gray-100">
                                    <img 
                                        :src="post.image_url" 
                                        class="w-full h-auto object-cover" 
                                        alt="Post image" 
                                        loading="lazy"
                                        @error="$event.target.src = '/storage/posts/placeholder.jpg'; $event.target.classList.add('opacity-50');"
                                    />
                                </div>
                                <div v-else class="rounded-lg overflow-hidden shadow-md max-w-2xl bg-gray-100 h-64 flex items-center justify-center">
                                    <div class="text-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p>No image available for this post</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="prose max-w-none">
                                <p class="text-gray-700 whitespace-pre-line">{{ post.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
                            <button 
                                @click="showCommentForm = !showCommentForm" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Add Comment
                            </button>
                        </div>

                        <!-- New Comment Form -->
                        <div v-if="showCommentForm" class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <form @submit.prevent="submitComment">
                                <div class="mb-4">
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Your Comment</label>
                                    <textarea
                                        id="content"
                                        v-model="form.content"
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        rows="3"
                                        required
                                    ></textarea>
                                    <div v-if="form.errors.content" class="mt-1 flex items-center text-sm text-red-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ form.errors.content }}
                                    </div>
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button
                                        type="button"
                                        @click="showCommentForm = false"
                                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        Post Comment
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Comments List -->
                        <div v-if="post.comments && post.comments.length > 0" class="space-y-4">
                            <div v-for="comment in post.comments" :key="comment.id" class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex justify-between">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-semibold">
                                                {{ comment.user.name.charAt(0).toUpperCase() }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center mb-1">
                                                <h4 class="font-medium text-gray-900">{{ comment.user.name }}</h4>
                                                <span class="mx-2 text-gray-300">•</span>
                                                <span class="text-sm text-gray-500">{{ new Date(comment.created_at).toLocaleDateString() }}</span>
                                            </div>
                                            
                                            <!-- Comment Content (View Mode) -->
                                            <p v-if="editingComment !== comment.id" class="text-gray-700">{{ comment.content }}</p>
                                            
                                            <!-- Comment Edit Form -->
                                            <div v-else class="mt-2">
                                                <textarea
                                                    v-model="editForm.content"
                                                    class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                    rows="3"
                                                ></textarea>
                                                <div v-if="editForm.errors.content" class="mt-1 flex items-center text-sm text-red-600">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ editForm.errors.content }}
                                                </div>
                                                <div class="flex justify-end space-x-2 mt-2">
                                                    <button
                                                        type="button"
                                                        @click="editingComment = null"
                                                        class="inline-flex items-center px-3 py-1 bg-gray-100 border border-gray-300 rounded-md font-medium text-xs text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150"
                                                    >
                                                        Cancel
                                                    </button>
                                                    <button
                                                        type="button"
                                                        @click="updateComment(comment.id)"
                                                        :disabled="editForm.processing"
                                                        class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-medium text-xs text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150"
                                                    >
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Comment Actions -->
                                    <div v-if="$page.props.auth.user.id === comment.user_id && editingComment !== comment.id" class="flex space-x-2">
                                        <button
                                            @click="editComment(comment)"
                                            class="text-sm text-gray-500 hover:text-indigo-600 transition-colors duration-150"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteComment(comment.id)"
                                            class="text-sm text-gray-500 hover:text-red-600 transition-colors duration-150"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="text-center py-8 text-gray-500">
                            No comments yet. Be the first to comment!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
