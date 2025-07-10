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

                                <!-- Post Stats -->
                                <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">
                                    {{ $post->likes->count() }} Likes · {{ $post->comments->count() }} Comments
                                </p>

                                <!-- Post Actions -->
                                <div
                                    class="flex justify-around border-t border-gray-300 dark:border-gray-600 pt-2 text-gray-600 dark:text-gray-300 text-sm">
                                    <button class="hover:text-blue-600 flex items-center gap-1">👍 Like</button>
                                    <button class="hover:text-blue-600 flex items-center gap-1">💬 Comment</button>
                                    <button class="hover:text-blue-600 flex items-center gap-1">↗️ Share</button>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-600 dark:text-gray-300">No posts yet.</p>
                        @endforelse
                    </section>


                    {{-- <section class="max-w-2xl mx-auto px-4">
                        <!-- Post Card -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 mb-6">
                            <!-- Post Header -->
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('storage/images/3d.jpg') }}" class="rounded-full w-10 h-10"
                                    alt="User Avatar">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">John Doe</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300">2 hours ago</p>
                                </div>
                            </div>

                            <!-- Post Description -->
                            <p class="text-gray-800 dark:text-gray-200 mb-3">
                                Enjoying nature's beauty 🌿🌤️
                            </p>

                            <!-- Post Image -->
                            <div class="mb-4">
                                <img src="{{ asset('storage/images/bird.jpg') }}" alt="Post Image"
                                    class="w-full rounded-lg">
                            </div>

                            <!-- Post Stats -->
                            <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">5 Likes · 3 Comments</p>

                            <!-- Post Actions -->
                            <div
                                class="flex justify-around border-t border-gray-300 dark:border-gray-600 pt-2 text-gray-600 dark:text-gray-300 text-sm">
                                <button class="hover:text-blue-600 flex items-center gap-1">👍 Like</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">💬 Comment</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">↗️ Share</button>
                            </div>
                        </div>

                        <!-- Post Card 2 -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 mb-6">
                            <!-- Post Header -->
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('storage/images/3d.jpg') }}" class="rounded-full w-10 h-10"
                                    alt="User Avatar">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Alice Smith</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300">Just now</p>
                                </div>
                            </div>

                            <!-- Post Description -->
                            <p class="text-gray-800 dark:text-gray-200 mb-3">
                                Throwback to my last beach trip 🌊🏖️
                            </p>

                            <!-- Post Image -->
                            <div class="mb-4">
                                <img src="{{ asset('storage/images/bird.jpg') }}" alt="Post Image"
                                    class="w-full rounded-lg">
                            </div>

                            <!-- Post Stats -->
                            <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">8 Likes · 2 Comments</p>

                            <!-- Post Actions -->
                            <div
                                class="flex justify-around border-t border-gray-300 dark:border-gray-600 pt-2 text-gray-600 dark:text-gray-300 text-sm">
                                <button class="hover:text-blue-600 flex items-center gap-1">👍 Like</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">💬 Comment</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">↗️ Share</button>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 mb-6">
                            <!-- Post Header -->
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('storage/images/3d.jpg') }}" class="rounded-full w-10 h-10"
                                    alt="User Avatar">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Alice Smith</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300">Just now</p>
                                </div>
                            </div>

                            <!-- Post Description -->
                            <p class="text-gray-800 dark:text-gray-200 mb-3">
                                Throwback to my last beach trip 🌊🏖️
                            </p>

                            <!-- Post Image -->
                            <div class="mb-4">
                                <img src="{{ asset('storage/images/bird.jpg') }}" alt="Post Image"
                                    class="w-full rounded-lg">
                            </div>

                            <!-- Post Stats -->
                            <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">8 Likes · 2 Comments</p>

                            <!-- Post Actions -->
                            <div
                                class="flex justify-around border-t border-gray-300 dark:border-gray-600 pt-2 text-gray-600 dark:text-gray-300 text-sm">
                                <button class="hover:text-blue-600 flex items-center gap-1">👍 Like</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">💬 Comment</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">↗️ Share</button>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 mb-6">
                            <!-- Post Header -->
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('storage/images/3d.jpg') }}" class="rounded-full w-10 h-10"
                                    alt="User Avatar">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Alice Smith</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300">Just now</p>
                                </div>
                            </div>

                            <!-- Post Description -->
                            <p class="text-gray-800 dark:text-gray-200 mb-3">
                                Throwback to my last beach trip 🌊🏖️
                            </p>

                            <!-- Post Image -->
                            <div class="mb-4">
                                <img src="{{ asset('storage/images/bird.jpg') }}" alt="Post Image"
                                    class="w-full rounded-lg">
                            </div>

                            <!-- Post Stats -->
                            <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">8 Likes · 2 Comments</p>

                            <!-- Post Actions -->
                            <div
                                class="flex justify-around border-t border-gray-300 dark:border-gray-600 pt-2 text-gray-600 dark:text-gray-300 text-sm">
                                <button class="hover:text-blue-600 flex items-center gap-1">👍 Like</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">💬 Comment</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">↗️ Share</button>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 mb-6">
                            <!-- Post Header -->
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('storage/images/3d.jpg') }}" class="rounded-full w-10 h-10"
                                    alt="User Avatar">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Alice Smith</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-300">Just now</p>
                                </div>
                            </div>

                            <!-- Post Description -->
                            <p class="text-gray-800 dark:text-gray-200 mb-3">
                                Throwback to my last beach trip 🌊🏖️
                            </p>

                            <!-- Post Image -->
                            <div class="mb-4">
                                <img src="{{ asset('storage/images/bird.jpg') }}" alt="Post Image"
                                    class="w-full rounded-lg">
                            </div>

                            <!-- Post Stats -->
                            <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">8 Likes · 2 Comments</p>

                            <!-- Post Actions -->
                            <div
                                class="flex justify-around border-t border-gray-300 dark:border-gray-600 pt-2 text-gray-600 dark:text-gray-300 text-sm">
                                <button class="hover:text-blue-600 flex items-center gap-1">👍 Like</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">💬 Comment</button>
                                <button class="hover:text-blue-600 flex items-center gap-1">↗️ Share</button>
                            </div>
                        </div>
                    </section> --}}


                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            async function submitForm(form, onSuccess, onError) {
                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: form.method,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    response.ok ? onSuccess(data) : onError(data);
                } catch (err) {
                    console.error('Unexpected error:', err);
                    onError({
                        message: 'An unexpected error occurred.'
                    });
                }
            }

            document.querySelectorAll('form.ajax-form').forEach((form) => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    submitForm(
                        form,
                        (data) => {
                            const successBox = document.getElementById('form-success-message');
                            if (successBox) {
                                successBox.textContent = data.message ||
                                    'Form submitted successfully!';
                                successBox.classList.remove('hidden');

                                // Auto-hide after 3 seconds
                                setTimeout(() => {
                                    successBox.classList.add('hidden');
                                    successBox.textContent = '';
                                }, 3000);
                            }

                            form.reset();
                        },
                        (error) => {
                            alert(error.message || form.dataset.error ||
                                'Form submission failed.');
                        }
                    );
                });
            });
        });
    </script>


</x-app-layout>
