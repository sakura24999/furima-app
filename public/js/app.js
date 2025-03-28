document.addEventListener
('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    if(alerts.length > 0) {
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.computedStyleMap.opacity = '0';
                setTimeout(() => {
                    alert.computedStyleMap.display = 'none';
                }, 500);
            }, 5000);
        });
    }

    const imageInput = document.querySelector('.image-upload-input');
    const imagePreview = document.querySelector('.image-preview');

    if(imageInput && imagePreview) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" class="preview-image" alt="プレビュー">`;
                    imagePreview.computedStyleMap.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    const likeButtons = document.querySelectorAll('.like-button');

    if(likeButtons.length > 0) {
        likeButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                const itemId = this.getAttribute('data-item-id');
                const isLiked = this.getAttribute('data-liked') === 'true';
                const likeCountElement = document.querySelector(`.like-count[data-item-id="${itemId}"]`);
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const url = isLiked
                ? `/items/${itemId}/unlike`
                : `/items/${itemId}/like`;

                const method = isLiked ? 'DELETE' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        this.setAttribute('data-liked', isLiked ? 'false' : 'true');

                        if(isLiked) {
                            this.innerHTML = '&#9825; いいね';
                            this.classList.remove('liked');
                        } else {
                            this.innerHTML = '&#9829; いいね中';
                            this.classList.add('liked');
                        }

                        if(likeCountElement) {
                            likeCountElement.textContent = data.likesCount;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }

    const commentForm = document.querySelector('.comment-form');

    if(commentForm) {
        commentForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const itemId = this.getAttribute('data-item-id');
            const commentInput = this.querySelector('.comment-input');
            const commentText = commentInput.value.trim();
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if(commentText === '')return;

            fetch(`/items/${itemId}/comments`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    body: commentText
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    commentInput.value = '';

                    const commentList = document.querySelector('.comments-list');
                    const newComment = document.createElement('div');
                    newComment.className = 'comment-item';
                    newComment.innerHTML = `
                    <div class="comment-header">
                    <span class="comment-user">${data.userName}</span>
                    <span class="comment-data">${data.formattedDate}</span>
                    </div>
                    <div class="comment-body">${data.comment.body}</div>
                    `;

                    commentsList.appendChild(newComment);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }

    const priceInput = document.querySelector('.price-input');
    if(priceInput) {
        priceInput.addEventListener('input', function(event) {
            let value = this.value.replace(/[^\d]/g, '');
            if(value !== '') {
                value = parseInt(value).toLocaleString();
            }
            this.value = value;
        });

        const itemForm = document.querySelector('.item-form');
        if(itemForm) {
            itemForm.addEventListener('submit', function(event) {
                priceInput.value = priceInput.value.replace(/,/g, '');
            });
        }
    }

    const editImageInput = document.getElementById('image');
    const editImagePreview = document.getElementById('image-preview');

    if(editImageInput && editImagePreview) {
        editImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    editImagePreview.innerHTML = `<img src="${e.target.result}" alt="プレビュー" class="preview-image">`;
                }
                reader.readAsDataURL(file);
            }else {
                editImagePreview.innerHTML = '';
            }
        });
    }

    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');

    if(avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    avatarPreview.innerHTML = `<img src="${e.target.result}" alt="プレビュー" class="preview-image">`;
                }
                reader.readAsDataURL(file);
            }else {
                avatarPreview.innerHTML = '';
            }
        });
    }
});
