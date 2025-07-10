// resources/js/pusher.js

import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;
Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: "14149aed27880e786aa4",
    cluster: "ap2",
    forceTLS: true,
});

// ✅ Listen for likes being updated in real time
window.Echo.channel("posts").listen(".like.updated", (e) => {
    console.log("🔥 Real-time event received:", e);
    const likeElement = document.getElementById(`likes-count-${e.postId}`);
    if (likeElement) {
        likeElement.textContent = `${e.likeCount}`;
    }
});

// ✅ Listen for new comments in real time
window.Echo.channel("posts").listen(".comment.created", (e) => {
    console.log("🟢 New comment event received:", e);

    const commentSection = document.getElementById(
        `comments-section-${e.postId}`
    );
    if (!commentSection) return;

    const commentHTML = `
        <div class="flex items-start gap-2">
            <img src="${e.user.profile_photo_url ?? "/storage/images/3d.jpg"}"
                 class="w-8 h-8 rounded-full" alt="User">
            <div class="bg-gray-100 dark:bg-gray-600 rounded-lg px-3 py-2 max-w-sm">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                    ${e.user.name} <span class="mx-4">just now</span>
                </p>
                <p class="text-sm text-gray-700 dark:text-gray-200">${
                    e.comment.comment
                }</p>
            </div>
        </div>
    `;

    commentSection.insertAdjacentHTML("afterbegin", commentHTML);

    const commentCountElement = document.getElementById(
        `comments-count-${e.postId}`
    );
    if (commentCountElement) {
        const current = parseInt(commentCountElement.textContent) || 0;
        commentCountElement.textContent = `${current + 1}`;
    }
});

// posts

window.Echo.channel("posts").listen(".post.created", (e) => {
    console.log("🟣 New post created:", e);

    const feed = document.getElementById("post-feed");
    if (!feed) return;

    const post = e.post;

    const postHTML = `
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4 mb-6">
            <div class="flex items-center mb-4">
                <img src="${
                    post.user.profile_photo_url ?? "/storage/images/3d.jpg"
                }"
                    class="rounded-full w-10 h-10" alt="User Avatar">
                <div class="ml-3">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">${
                        post.user.name
                    }</p>
                    <p class="text-xs text-gray-500 dark:text-gray-300">just now</p>
                </div>
            </div>

            ${
                post.description
                    ? `<p class="text-gray-800 dark:text-gray-200 mb-3">${post.description}</p>`
                    : ""
            }

            ${
                post.image_path
                    ? `
                <div class="mb-4">
                    <img src="/storage/${post.image_path}" alt="Post Image" class="w-full rounded-lg">
                </div>
            `
                    : ""
            }

            <p class="text-sm text-gray-500 dark:text-gray-300 mb-2">
                <span id="likes-count-${post.id}">0</span> Likes ·
                <span id="comments-count-${post.id}">0</span> Comments
            </p>

            <div class="flex justify-around border-t border-gray-300 dark:border-gray-600 pt-2 text-gray-600 dark:text-gray-300 text-sm">
                <button class="hover:text-blue-600 flex items-center gap-1 like-button" data-post-id="${
                    post.id
                }">
                    👍 Like
                </button>
                <button type="button" class="toggle-comment-form hover:text-blue-600 flex items-center gap-1" data-post-id="${
                    post.id
                }">
                    💬 Comment
                </button>
                <button class="hover:text-blue-600 flex items-center gap-1">↗️ Share</button>
            </div>

            <div class="mt-4 space-y-2 max-h-[300px] overflow-y-auto hidden" id="all-comments-${
                post.id
            }"></div>
            <div class="mt-4 space-y-2" id="comments-section-${post.id}"></div>

            <div class="mt-3" id="comment-form-${post.id}">
                <form method="POST" action="/comments" class="flex gap-2 items-center ajax-comment-form" data-post-id="${
                    post.id
                }">
                    <input type="hidden" name="post_id" value="${post.id}">
                    <input type="text" name="comment" placeholder="Write a comment..." required
                        class="flex-grow rounded-full border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm text-gray-800 dark:text-gray-100 bg-gray-100 dark:bg-gray-800 focus:outline-none focus:ring focus:border-blue-300">
                    <button type="submit" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-full">
                        Post
                    </button>
                </form>
            </div>
        </div>
    `;

    feed.insertAdjacentHTML("afterbegin", postHTML);
});
