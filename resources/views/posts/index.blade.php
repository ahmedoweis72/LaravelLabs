<x-layout>
    <div class="max-w-6xl mx-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-700">
                <i class="fas fa-pen-alt mr-2"></i> Create Post
            </h1>
            <a href="{{ route('posts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-plus mr-2"></i> New Post
            </a>
        </div>

        <!-- Posts Table Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-table text-indigo-500 mr-2"></i> 
                    Recent Posts
                </h2>
                <p class="text-gray-500 text-sm mt-1">Showing posts</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Posted By
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($posts as $post)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-file-alt text-indigo-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{$post->title }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 text-xs font-medium">{{ substr($post->user->name ?? 'NA', 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $post->user->name ?? 'Unknown User' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $post->created_at->format('Y-m-d') }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('posts.show', $post->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('posts.edit', $post->id) }}" class="text-green-600 hover:text-green-900 mr-4">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr> 
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Section -->
        <div class="flex items-center justify-between bg-white px-6 py-4 rounded-xl shadow-sm">
            <div class="flex-1 flex justify-between sm:hidden">
                <!-- Previous and Next buttons for small screens -->
                @if ($posts->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                        Previous
                    </span>
                @else
                    <a href="{{ $posts->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                @endif

                @if ($posts->hasMorePages())
                    <a href="{{ $posts->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                @else
                    <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white">
                        Next
                    </span>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing 
                        <span class="font-medium">{{ $posts->firstItem() }}</span> 
                        to 
                        <span class="font-medium">{{ $posts->lastItem() }}</span> 
                        of 
                        <span class="font-medium">{{ $posts->total() }}</span> 
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <!-- Previous Button -->
                        @if ($posts->onFirstPage())
                            <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $posts->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $currentPage = $posts->currentPage();
                            $lastPage = $posts->lastPage();
                            $pages = [];
                            
                            // Always show first page
                            $pages[] = 1;
                            
                            // Calculate range around current page
                            $startPage = max(2, $currentPage - 2);
                            $endPage = min($lastPage - 1, $currentPage + 2);
                            
                            // Add ellipsis after first page if needed
                            if ($startPage > 2) {
                                $pages[] = '...';
                            }
                            
                            // Add pages around current page
                            for ($i = $startPage; $i <= $endPage; $i++) {
                                $pages[] = $i;
                            }
                            
                            // Add ellipsis before last page if needed
                            if ($endPage < $lastPage - 1) {
                                $pages[] = '...';
                            }
                            
                            // Always show last page if more than 1 page exists
                            if ($lastPage > 1) {
                                $pages[] = $lastPage;
                            }
                        @endphp
                        
                        @foreach ($pages as $page)
                            @if ($page === '...')
                                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                    ...
                                </span>
                            @else
                                @if ($page == $currentPage)
                                    <span aria-current="page" class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $posts->url($page) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endif
                        @endforeach

                        <!-- Next Button -->
                        @if ($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    </div>
</x-layout>