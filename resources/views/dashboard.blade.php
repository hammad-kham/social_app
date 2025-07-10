<x-app-layout>
    <div class="py-12">
        <div class="w-full max-w-7xl sm:w-full lg:w-1/2 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="form-success-message" class="hidden mb-4 p-3 rounded-lg bg-green-100 text-green-800 text-sm">
                    </div>

                    <section>
                        <!-- Post Create Form -->
                        <form class="ajax-form mb-6" id="ajax-form" action="{{ route('posts.store') }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf
                            <div class="mb-4">
                                <textarea name="description"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 text-gray-800 dark:text-gray-200 bg-white dark:bg-gray-700"
                                    rows="3" placeholder="What's on your mind?"></textarea>
                                @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Attach
                                    Image</label>
                                <input type="file" name="image"
                                    class="w-full text-gray-900 bg-white dark:bg-gray-700 dark:text-gray-100 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none p-2" />
                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow">
                                Post
                            </button>
                        </form>
                    </section>

                    <section class="max-w-2xl mx-auto px-4">

                        <div id="post-feed">
                        @forelse ($posts as $post)
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 mb-6">
                                <!-- Post Header -->
                                <div class="flex items-center mb-4">
                                    <img src="{{ $post->user->profile_photo_url ?? asset('storage/images/3d.jpg') }}"
                                        class="rounded-full w-10 h-10" alt="User Avatar">
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $post->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-300">
                                            {{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <!-- Post Description -->
                                @if ($post->description)
                                    <p class="text-gray-800 dark:text-gray-200 mb-3">
                                        {{ $post->description }}
                                    </p>
                                @endif

                                <!-- Post Image -->
                                @if ($post->image_path)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image"
                                            class="w-full rounded-lg">
                                    </div>
                                @endif

                                <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">
                                    <span id="likes-count-{{ $post->id }}">{{ $post->likes->count() }}</span> Likes
                                    ·
                                    <span
                                        id="comments-count-{{ $post->id }}">{{ $post->comments->count() }}</span>
                                    Comments
                                </p>



                                <!-- Post Actions -->
                                <div
                                    class="flex justify-around border-t border-gray-300 dark:border-gray-600 pt-2 text-gray-600 dark:text-gray-300 text-sm">

                                    <!-- Like Button -->
                                    <button class="hover:text-blue-600 flex items-center gap-1 like-button"
                                        data-post-id="{{ $post->id }}">
                                        👍 Like
                                    </button>

                                    <!-- Comment Button -->
                                    {{-- <button class="hover:text-blue-600 flex items-center gap-1">💬 Comment</button> --}}

                                    <!-- Comment Button -->
                                    <button type="button"
                                        class="toggle-comment-form hover:text-blue-600 flex items-center gap-1"
                                        data-post-id="{{ $post->id }}">
                                        💬 Comment
                                    </button>

                                    <!-- Share Button -->
                                    <button class="hover:text-blue-600 flex items-center gap-1">↗️ Share</button>
                                </div>
                                @php
                                    $postId = $post->id;
                                @endphp
                                <div class="mt-4 space-y-2 max-h-[300px] overflow-y-auto hidden"
                                    id="all-comments-{{ $postId }}"></div>
                                <!-- Comments List -->
                                <div class="mt-4 space-y-2" id="comments-section-{{ $post->id }}">
                                    @foreach ($post->comments->take(2) as $comment)
                                        <div class="flex items-start gap-2">
                                            <img src="{{ $comment->user->profile_photo_url ?? asset('storage/images/3d.jpg') }}"
                                                class="w-8 h-8 rounded-full" alt="User">
                                            <div class="bg-gray-100 dark:bg-gray-600 rounded-lg px-3 py-2 max-w-sm">
                                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                                    {{ $comment->user->name }} <span
                                                        class="mx-4">{{ $comment->created_at->diffForHumans() }}
                                                    </span></p>
                                                <p class="text-sm text-gray-700 dark:text-gray-200">
                                                    {{ $comment->comment }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- View More / Show Less Buttons -->
                                @if ($post->comments->count() > 2)
                                    <button class="text-sm text-blue-600 mt-2 load-more-comments"
                                        data-post-id="{{ $post->id }}">
                                        View more comments
                                    </button>
                                    <button class="text-sm text-red-600 mt-2 hide-comments hidden"
                                        data-post-id="{{ $post->id }}">
                                        Show less
                                    </button>
                                @endif


                                <!-- Comment Form -->
                                <div class="mt-3" id="comment-form-{{ $post->id }}">
                                    <form method="POST" action="{{ route('comments.store') }}"
                                        class="flex gap-2 items-center ajax-comment-form"
                                        data-post-id="{{ $post->id }}">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <input type="text" name="comment" placeholder="Write a comment..." required
                                            class="flex-grow rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm text-gray-800 dark:text-gray-100 bg-gray-100 dark:bg-gray-800 focus:outline-none focus:ring focus:border-blue-300">
                                        <button type="submit"
                                            class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-full">
                                            Post
                                        </button>
                                    </form>
                                </div>







                            </div>
                        @empty
                            <p class="text-center text-gray-600 dark:text-gray-300">No posts yet.</p>
                        @endforelse
                        </div>
                    </section>



                </div>
            </div>
        </div>
    </div>


</x-app-layout>
