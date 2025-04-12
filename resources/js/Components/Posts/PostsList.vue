<template>
  <div>
    <!-- No posts message -->
    <div v-if="posts.data.length === 0" class="text-center py-8 text-gray-500">
      No posts found. Create your first post!
    </div>
    
    <!-- Posts list -->
    <div v-else class="space-y-6">
      <div v-for="post in posts.data" :key="post.id" class="border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="flex flex-col md:flex-row">
          <!-- Post Image (if exists) -->
          <div class="md:w-1/4 flex-shrink-0">
            <Link :href="route('posts.show', post.slug)">
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
            </Link>
          </div>
          
          <!-- Post Content -->
          <div class="p-6 flex-grow">
            <div class="flex justify-between items-start">
              <div>
                <div class="flex flex-col space-y-2">
                  <Link :href="route('posts.show', post.slug)" class="text-lg font-semibold text-gray-900 hover:text-indigo-600 transition-colors duration-200">
                    {{ post.title }}
                  </Link>
                </div>
                <p class="text-sm text-gray-500 mb-3">
                  By {{ post.user.name }} • {{ new Date(post.created_at).toLocaleDateString() }}
                </p>
                <p class="text-gray-700 line-clamp-2">{{ post.description }}</p>
              </div>
              <div class="flex space-x-2">
                <Link :href="route('posts.edit', post.slug)" class="inline-flex items-center px-3 py-1 bg-gray-100 border border-gray-300 rounded-md font-medium text-xs text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                  Edit
                </Link>
                <Link :href="route('posts.destroy', post.slug)" method="delete" as="button" class="inline-flex items-center px-3 py-1 bg-red-100 border border-red-300 rounded-md font-medium text-xs text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150" 
                      onclick="return confirm('Are you sure you want to delete this post?')">
                  Delete
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  posts: {
    type: Object,
    required: true
  }
});
</script>
