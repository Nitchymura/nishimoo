document.addEventListener('click', function (event) { // to respond when added something by Ajax
    const button = event.target.closest('.like-button'); // to retrieve a data from button
    if (!button) return;

    const postId = button.getAttribute('data-id');
    const postType = button.getAttribute('data-type').toLowerCase();
    const liked = button.getAttribute('data-liked') === '1';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const method = liked ? 'DELETE' : 'POST';
    const url = `/like/${postType}/${postId}/${liked ? 'delete' : 'store'}`;

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
    })
    .then(async response => {
        if (response.ok) {
            const data = await response.json();
            button.setAttribute('data-liked', liked ? '0' : '1');
            button.innerHTML = liked
                ? '<i class="fa-regular fa-heart"></i>'
                : '<i class="fa-solid fa-heart text-danger"></i>';
            
            const likeCountElement = document.querySelector(`.like-count[data-id="${postId}"]`);
            if(likeCountElement && data.likes_count !== undefined){
                likeCountElement.textContent = data.likes_count;
            }
        } else {
            console.error('Like failed');
        }
    })
    .catch(error => {
        console.error('AJAX Error:', error);
    });
});