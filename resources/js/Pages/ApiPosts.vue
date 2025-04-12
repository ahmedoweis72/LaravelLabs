<template>
  <Head title="API Posts" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">API Posts</h2>
        <Link :href="route('posts.create')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
          Create Post
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- Loading state -->
            <div v-if="loading" class="py-8 text-center">
              <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-600"></div>
              <p class="mt-2 text-gray-600">Loading posts...</p>
            </div>

            <!-- Error state with user-friendly message -->
            <div v-else-if="error" class="border-l-4 border-red-500 bg-red-50 p-4 my-4 rounded">
              <div class="flex items-start">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-red-700">
                    {{ error }}
                  </p>
                  <div class="mt-2">
                    <button @click="fetchPosts()" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium focus:outline-none focus:underline">
                      Try again
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- No posts message -->
            <div v-else-if="posts.data && posts.data.length === 0" class="text-center py-8 text-gray-500">
              No posts found. Create your first post!
            </div>
            
            <!-- Posts list -->
            <div v-else-if="posts.data" class="space-y-6">
              <div v-for="post in posts.data" :key="post.id" class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex flex-col md:flex-row">
                  <!-- Post Image (if exists) -->
                  <div class="md:w-1/4 flex-shrink-0">
                    <a :href="`/posts/${post.slug}`" class="block">
                      <div v-if="post.image" class="w-full h-48 md:h-full overflow-hidden bg-gray-100">
                        <img 
                          :src="post.image_url" 
                          class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" 
                          alt="Post image" 
                          loading="lazy"
                          @error="$event.target.src = '/storage/posts/placeholder.jpg'; $event.target.classList.add('opacity-50');"
                        />
                      </div>
                      <div v-else class="w-full h-48 md:h-full bg-gray-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                      </div>
                    </a>
                  </div>
                  
                  <!-- Post Content -->
                  <div class="p-6 flex-grow">
                    <div class="flex justify-between items-start">
                      <div>
                        <div class="flex flex-col space-y-2">
                          <a :href="`/posts/${post.slug}`" class="text-lg font-semibold text-gray-900 hover:text-indigo-600 transition-colors duration-200">
                            {{ post.title }}
                          </a>
                        </div>
                        <p class="text-sm text-gray-500 mb-3">
                          By {{ post.user.name }} • {{ new Date(post.created_at).toLocaleDateString() }}
                        </p>
                        <p class="text-gray-700 line-clamp-2">{{ post.description }}</p>
                      </div>
                      <div class="flex space-x-2">
                        <a :href="`/posts/${post.slug}/edit`" class="inline-flex items-center px-3 py-1 bg-gray-100 border border-gray-300 rounded-md font-medium text-xs text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                          Edit
                        </a>
                        <button @click="deletePost(post.slug)" class="inline-flex items-center px-3 py-1 bg-red-100 border border-red-300 rounded-md font-medium text-xs text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150">
                          Delete
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Pagination -->
              <Pagination 
                :links="paginationLinks" 
                :current-page="currentPage" 
                :last-page="lastPage" 
                :from="from" 
                :to="to" 
                :total="total" 
                :per-page="perPage"
                :uses-inertia="false"
                :error="paginationError"
                item-name="posts"
                @page-click="goToPage"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import axios from 'axios';

// State variables
const apiEndpoint = '/api/posts';
const perPage = ref(5);
const posts = ref({
  data: [],
  meta: {
    current_page: 1,
    from: null,
    last_page: 1,
    links: [],
    path: '',
    per_page: perPage.value,
    to: null,
    total: 0
  }
});
const loading = ref(true);
const error = ref(null);
const paginationError = ref('');

// Computed properties for pagination
const paginationLinks = computed(() => posts.value.meta?.links || []);
const currentPage = computed(() => posts.value.meta?.current_page || 1);
const lastPage = computed(() => posts.value.meta?.last_page || 1);
const from = computed(() => posts.value.meta?.from || null);
const to = computed(() => posts.value.meta?.to || null);
const total = computed(() => posts.value.meta?.total || 0);

// Fetch posts from API
const fetchPosts = async (url = apiEndpoint) => {
  loading.value = true;
  error.value = null;
  paginationError.value = '';
  
  try {
    // Get CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Set up headers with CSRF token and accept JSON
    const headers = {
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    };
    
    const response = await axios.get(url, {
      headers,
      params: {
        per_page: perPage.value
      },
      withCredentials: true // Important for maintaining session cookies
    });
    
    // Handle different response structures
    if (response.data.data) {
      // If response has a data property (Laravel API Resource Collection)
      posts.value = response.data;
    } else {
      // If response is the direct array of posts
      posts.value = {
        data: response.data,
        meta: response.data.meta || {}
      };
    }
    
    loading.value = false;
  } catch (err) {
    console.error('Error fetching posts:', err);
    error.value = 'Please provide a title for your post. We encountered an issue loading the posts. Please try again.';
    loading.value = false;
  }
};

// Handle pagination
const goToPage = (url) => {
  if (url) {
    fetchPosts(url);
  }
};

// Delete post
const deletePost = async (slug) => {
  if (!confirm('Are you sure you want to delete this post?')) {
    return;
  }
  
  try {
    // Get CSRF token from meta tag
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Set up headers with CSRF token and accept JSON
    const headers = {
      'X-CSRF-TOKEN': token,
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    };
    
    await axios.delete(`/api/posts/${slug}`, {
      headers,
      withCredentials: true
    });
    
    // Refresh the current page
    const currentPage = posts.value.meta?.links?.find(link => link.active)?.url || apiEndpoint;
    fetchPosts(currentPage);
  } catch (err) {
    console.error('Error deleting post:', err);
    error.value = 'There was a problem deleting your post. Please try again.';
  }
};

// Fetch posts on component mount
onMounted(() => {
  fetchPosts();
});
</script>
