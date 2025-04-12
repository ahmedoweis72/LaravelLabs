<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    post: Object,
    users: Array,
});

const form = useForm({
    title: props.post.title,
    description: props.post.description,
    creator: props.post.user_id,
    image: null,
    _method: 'PUT',
});

const imagePreview = ref(null);
const hasExistingImage = computed(() => !!props.post.image);
const existingImageUrl = computed(() => props.post.image_url);

// If there's an existing image, show it in the preview initially
if (hasExistingImage.value) {
    // We don't set imagePreview here to avoid conflicts with new uploads
    // The existing image is shown through existingImageUrl
}

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    form.image = file;
    
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.value = null;
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
    document.getElementById('image').value = '';
};

const submit = () => {
    form.post(route('posts.update', props.post.slug), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Post" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Post</h2>
                <div class="flex space-x-3">
                    <Link :href="route('posts.show', post.id)" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to Post
                    </Link>
                    <Link :href="route('posts.index')" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        All Posts
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Error Summary -->
                        <div v-if="Object.keys(form.errors).length > 0" class="mb-6 border-l-4 border-red-500 bg-red-50 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        Please fix the following errors:
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li v-for="(error, key) in form.errors" :key="key">
                                                {{ error }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <InputLabel for="title" value="Title" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                    :class="{'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.title}"
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="description" value="Description" />
                                <textarea
                                    id="description"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    v-model="form.description"
                                    rows="6"
                                    required
                                    :class="{'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.description}"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div>
                                <InputLabel for="creator" value="Author" />
                                <select
                                    id="creator"
                                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    v-model="form.creator"
                                    required
                                    :class="{'border-red-500 focus:border-red-500 focus:ring-red-500': form.errors.creator}"
                                >
                                    <option value="" disabled>Select an author</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.creator" />
                            </div>

                            <div>
                                <InputLabel for="image" value="Post Image (JPG, PNG only)" />
                                
                                <!-- Current Image (if exists) -->
                                <div v-if="hasExistingImage && !imagePreview" class="mt-2 mb-4">
                                    <p class="text-sm text-gray-500 mb-1">Current Image:</p>
                                    <div class="relative w-40 h-40 border border-gray-200 rounded-md overflow-hidden bg-gray-50">
                                        <img :src="existingImageUrl" class="w-full h-full object-cover" alt="Current image" />
                                        <div v-if="!existingImageUrl" class="absolute inset-0 flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-1 flex items-center">
                                    <input
                                        id="image"
                                        type="file"
                                        class="hidden"
                                        @change="handleImageUpload"
                                        accept=".jpg,.png"
                                    />
                                    <label for="image" class="flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ hasExistingImage ? 'Change Image' : 'Choose Image' }}
                                    </label>
                                    <button 
                                        v-if="form.image || imagePreview" 
                                        type="button" 
                                        @click="removeImage"
                                        class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remove New Image
                                    </button>
                                    <button 
                                        v-if="hasExistingImage && !form.image && !imagePreview" 
                                        type="button" 
                                        @click="form._method = 'PUT'; form.post(route('posts.update', props.post.slug), { data: { remove_image: true }, preserveScroll: true })"
                                        class="ml-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remove Current Image
                                    </button>
                                </div>
                                
                                <!-- New Image Preview -->
                                <div v-if="imagePreview" class="mt-3">
                                    <p class="text-sm text-gray-500 mb-1">New Image Preview:</p>
                                    <div class="relative w-40 h-40 border border-gray-200 rounded-md overflow-hidden">
                                        <img :src="imagePreview" class="w-full h-full object-cover" alt="New image preview" />
                                    </div>
                                </div>
                                
                                <p class="mt-1 text-sm text-gray-500">Accepted formats: JPG, PNG. Max size: 2MB.</p>
                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <SecondaryButton
                                    :href="route('posts.show', post.id)"
                                    class="mr-3"
                                >
                                    Cancel
                                </SecondaryButton>
                                <PrimaryButton
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Update Post
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
