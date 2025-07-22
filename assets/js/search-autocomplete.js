document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const suggestionsBox = document.getElementById('search-suggestions');

    let debounceTimeout = null;

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        if (debounceTimeout) clearTimeout(debounceTimeout);
        if (!query) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.style.display = 'none';
            return;
        }
        debounceTimeout = setTimeout(() => {
            fetch(`/php-project/search_suggest.php?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        suggestionsBox.innerHTML = data.map(post =>
                            `<div class="suggestion-item" data-id="${post.id}">${post.title}</div>`
                        ).join('');
                        suggestionsBox.style.display = 'block';
                    } else {
                        suggestionsBox.innerHTML = '<div class="no-suggestion">Không tìm thấy bài viết</div>';
                        suggestionsBox.style.display = 'block';
                    }
                });
        }, 200);
    });

    suggestionsBox.addEventListener('click', function(e) {
        if (e.target.classList.contains('suggestion-item')) {
            const postId = e.target.getAttribute('data-id');
            if (postId) {
                window.location.href = `/php-project/posts/post.php?id=${postId}`;
            }
        }
    });

    // Ẩn gợi ý khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
            suggestionsBox.style.display = 'none';
        }
    });
}); 