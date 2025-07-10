document.addEventListener("DOMContentLoaded", () => {
    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content;

    // 🔁 Submit generic AJAX form
    async function submitForm(form, onSuccess, onError) {
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: form.method,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: formData,
            });

            const data = await response.json();
            response.ok ? onSuccess(data) : onError(data);
        } catch (err) {
            console.error("Unexpected error:", err);
            onError({ message: "An unexpected error occurred." });
        }
    }

    // ✅ Generic AJAX form handling
    document.querySelectorAll("form.ajax-form").forEach((form) => {
        form.addEventListener("submit", (e) => {
            e.preventDefault();
            submitForm(
                form,
                (data) => {
                    const successBox = document.getElementById(
                        "form-success-message"
                    );
                    if (successBox) {
                        
                        successBox.textContent =
                            data.message || "Form submitted successfully!";
                        successBox.classList.remove("hidden");
                        setTimeout(() => {
                            successBox.classList.add("hidden");
                            successBox.textContent = "";
                        }, 3000);
                    }
                    form.reset();
                },
                (error) => {
                    alert(
                        error.message ||
                            form.dataset.error ||
                            "Form submission failed."
                    );
                }
            );
        });
    });

    // ✅ LIKE BUTTON LOGIC
    // document.querySelectorAll('.like-button').forEach(button => {
    //     button.addEventListener('click', async () => {
    //         const postId = button.dataset.postId;
    //         try {
    //             const response = await fetch(`/like/${postId}`, {
    //                 method: 'POST',
    //                 headers: {
    //                     'Content-Type': 'application/json',
    //                     'X-CSRF-TOKEN': csrfToken,
    //                     'Accept': 'application/json'
    //                 }
    //             });

    //             const data = await response.json();
    //             const likeElement = document.getElementById(`likes-count-${data.postId}`);
    //             if (likeElement) {
    //                 likeElement.textContent = data.likeCount;
    //             }

    //         } catch (error) {
    //             console.error('Like request failed:', error);
    //         }
    //     });
    // });

    document.getElementById("post-feed")?.addEventListener("click", async (event) => {
            const button = event.target.closest(".like-button");
            if (!button) return;

            const postId = button.dataset.postId;
            const csrfToken = document.querySelector(
                'meta[name="csrf-token"]'
            )?.content;

            try {
                const response = await fetch(`/like/${postId}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: JSON.stringify({}), // required to avoid 419
                });

                const data = await response.json();

                const likeElement = document.getElementById(
                    `likes-count-${data.postId}`
                );
                if (likeElement) {
                    likeElement.textContent = `${data.likeCount} Likes`;
                }
            } catch (error) {
                console.error("Like request failed:", error);
            }
        });

    // COMMENT SUBMIT via AJAX
    function setupCommentFormListeners(csrfToken) {
        document.querySelectorAll(".ajax-comment-form").forEach((form) => {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();

                const postId = form.dataset.postId;
                const commentInput = form.querySelector(
                    'input[name="comment"]'
                );
                const commentText = commentInput.value.trim();
                if (!commentText) return;

                const formData = new FormData(form);

                try {
                    const response = await fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            Accept: "application/json",
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (response.ok) {
                        commentInput.value = ""; // Reset input
                    } else {
                        alert(data.message || "Comment submission failed.");
                    }
                } catch (error) {
                    console.error("AJAX comment submission error:", error);
                    alert("Unexpected error while posting comment.");
                }
            });
        });
    }

    //  LOAD FULL COMMENTS
    function setupLoadMoreComments() {
        document.querySelectorAll(".load-more-comments").forEach((button) => {
            button.addEventListener("click", async () => {
                const postId = button.dataset.postId;
                const commentSection = document.getElementById(
                    `comments-section-${postId}`
                );
                const allCommentSection = document.getElementById(
                    `all-comments-${postId}`
                );
                const hideBtn = document.querySelector(
                    `.hide-comments[data-post-id="${postId}"]`
                );

                try {
                    const response = await fetch(`/posts/${postId}/comments`);
                    const comments = await response.json();

                    allCommentSection.innerHTML = "";

                    comments.forEach((comment) => {
                        const commentDiv = document.createElement("div");
                        commentDiv.classList.add(
                            "flex",
                            "items-start",
                            "gap-2"
                        );
                        commentDiv.innerHTML = `
                            <img src="${
                                comment.user.profile_photo_url ??
                                "/storage/images/3d.jpg"
                            }"
                                 class="w-8 h-8 rounded-full" alt="User">
                            <div class="bg-gray-100 dark:bg-gray-600 rounded-lg px-3 py-2 max-w-sm">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                    ${comment.user.name} 
                                    <span class="ml-4 text-xs text-gray-500 dark:text-gray-300">${
                                        comment.time
                                    }</span>
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-200">${
                                    comment.comment
                                }</p>
                            </div>
                        `;
                        allCommentSection.appendChild(commentDiv);
                    });

                    commentSection?.classList.add("hidden");
                    allCommentSection?.classList.remove("hidden");
                    button.classList.add("hidden");
                    hideBtn?.classList.remove("hidden");
                } catch (error) {
                    console.error("Error loading full comments:", error);
                }
            });
        });
    }

    // HIDE FULL COMMENTS
    function setupHideComments() {
        document.querySelectorAll(".hide-comments").forEach((button) => {
            button.addEventListener("click", () => {
                const postId = button.dataset.postId;
                const commentSection = document.getElementById(
                    `comments-section-${postId}`
                );
                const allCommentSection = document.getElementById(
                    `all-comments-${postId}`
                );
                const showMoreBtn = document.querySelector(
                    `.load-more-comments[data-post-id="${postId}"]`
                );

                allCommentSection?.classList.add("hidden");
                commentSection?.classList.remove("hidden");
                showMoreBtn?.classList.remove("hidden");
                button.classList.add("hidden");
            });
        });
    }

    // TOGGLE COMMENT FORM VISIBILITY
    document.querySelectorAll(".toggle-comment-form").forEach((button) => {
        button.addEventListener("click", () => {
            const postId = button.dataset.postId;
            const formEl = document.getElementById(`comment-form-${postId}`);
            if (formEl) {
                formEl.classList.toggle("hidden");
            }
        });
    });

    //all comment features
    setupCommentFormListeners(csrfToken);
    setupLoadMoreComments();
    setupHideComments();
});
