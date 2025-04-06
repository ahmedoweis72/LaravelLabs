<x-layout>
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl mb-6 overflow-hidden border border-gray-100">
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-3 text-gray-800">{{ $post->title }}</h1>
            <p class="mb-5 text-gray-600 leading-relaxed">{{ $post->description }}</p>
            <div class="flex items-center text-sm text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>{{ $post->user->name }}</span>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100 mb-6">
        <div class="border-b border-gray-100">
            <div class="px-6 py-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <h4 class="text-lg font-medium text-gray-800">Comments</h4>
            </div>
        </div>
        
        <div class="divide-y divide-gray-100">
            @forelse($post->comments as $comment)
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-gray-700 leading-relaxed">{{ $comment->body }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center text-sm text-gray-500">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-medium mr-2">
                                {{ substr($comment->user->name, 0, 1) }}
                            </div>
                            <span>{{ $comment->user->name }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <button class="flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors" onclick="toggleEdit({{ $comment->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </button>
                            <form action="{{ route('posts.comments.destroy', [$post->id, $comment->id]) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Form (Hidden by default) -->
                    <div id="edit-form-{{ $comment->id }}" style="display: none;" class="mt-4 bg-gray-50 p-4 rounded-lg">
                        <form action="{{ route('posts.comments.update', [$post->id, $comment->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <textarea name="body" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="3" required>{{ $comment->body }}</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" class="flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" onclick="toggleEdit({{ $comment->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel
                                </button>
                                <button type="submit" class="ml-3 flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-gray-500 text-lg">No comments yet. Be the first to share your thoughts!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add Comment Form -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="border-b border-gray-100">
            <div class="px-6 py-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
                <h4 class="text-lg font-medium text-gray-800">Add Your Comment</h4>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('posts.comments.store', $post->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea name="body" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-300 transition-colors" rows="4" placeholder="Share your thoughts..." required></textarea>
                </div>
                <input type="hidden" name="user_id" value="{{ $post->user_id }}">
                <div class="flex justify-end">
                    <button type="submit" class="flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Post Comment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleEdit(commentId) {
    const editForm = document.getElementById(`edit-form-${commentId}`);
    editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
}
</script>
</x-layout>