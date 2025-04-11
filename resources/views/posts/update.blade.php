<x-layout>
  
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Edit Post</h2>
        <form method="POST" action="{{ route('posts.update', $post->id) }}">
            @csrf
            @method('PUT') {{-- Use PUT method for updates --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" value="{{ old('title', $post->title) }}" name="title" class="mt-1 block w-full border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Enter title" />
                @error('title')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" rows="4" placeholder="Enter description">{{ old('description', $post->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="creator" class="block text-sm font-medium text-gray-700">Post Creator</label>
                <select id="creator" name="creator" class="mt-1 block w-full border {{ $errors->has('creator') ? 'border-red-500' : 'border-gray-300' }} rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('creator', $post->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('creator')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <button type="submit" class="w-full bg-green-500 text-white p-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-sm">Update</button>
        </form>
    </div>
</x-layout>