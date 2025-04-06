<x-layout>
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Create Post</h2>
        <form method="POST" action="/posts">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Enter title" />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full border border-gray-300 rounded-md p-2" rows="4" placeholder="Enter description"></textarea>
            </div>
            <div class="mb-4">
                <label for="creator" class="block text-sm font-medium text-gray-700">Post Creator</label>
                <select id="creator" name="creator" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Create</button>
        </form>
    </div>
</x-layout>