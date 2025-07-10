import "./bootstrap";
import "./pusher";


import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

// import Echo from "laravel-echo";
// import Pusher from "pusher-js";

// window.Pusher = Pusher;
// Pusher.logToConsole = true;

// window.Echo = new Echo({
//     broadcaster: "pusher",
//     key: "14149aed27880e786aa4",
//     cluster: "ap2",
//     forceTLS: true,
// });

// // ✅ Listen for likes being updated in real time
// window.Echo.channel("posts").listen(".like.updated", (e) => {
//     console.log("🔥 Real-time event received:", e); // 👈 add this line
//     console.log("Raw event data:", JSON.stringify(e));

//     const likeElement = document.getElementById(`likes-count-${e.postId}`);
//     console.log(likeElement);
//     if (likeElement) {
//         likeElement.textContent = `${e.likeCount} Likes`;
//     }
// });

// // comment

// window.Echo.channel("posts").listen(".comment.created", (e) => {
//     console.log("🟢 New comment event received:", e);

//     const commentSection = document.getElementById(
//         `comments-section-${e.postId}`
//     );
//     if (!commentSection) return;

//     const commentHTML = `
//         <div class="flex items-start gap-2">
//             <img src="${e.user.profile_photo_url ?? "/storage/images/3d.jpg"}"
//                  class="w-8 h-8 rounded-full" alt="User">
//             <div class="bg-gray-100 dark:bg-gray-600 rounded-lg px-3 py-2 max-w-sm">
//                 <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
//                     ${e.user.name} <span class="mx-4">just now </span>
//                 </p>
//                 <p class="text-sm text-gray-700 dark:text-gray-200">${
//                     e.comment.comment
//                 }</p>
//             </div>
//         </div>
//     `;

//     commentSection.insertAdjacentHTML("afterbegin", commentHTML);

//     // 🔄 Update comment count
//     const commentCountElement = document.getElementById(
//         `comments-count-${e.postId}`
//     );
//     if (commentCountElement) {
//         const current = parseInt(commentCountElement.textContent) || 0;
//         commentCountElement.textContent = `${current + 1} Comments`;
//     }
// });
