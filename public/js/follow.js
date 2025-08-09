document.addEventListener('click', function (event) {
    const button = event.target.closest('.follow-button');
    if (!button) return;

    const userId = button.getAttribute('data-id');
    const followed = button.getAttribute('data-followed') === '1';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const method = followed ? 'DELETE' : 'POST';
    const url = `/follow/${userId}/${followed ? 'delete' : 'store'}`;

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json', // ← これ大事！
            'X-CSRF-TOKEN': csrfToken
        },
    })
    .then(async response => {
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Follow failed:', errorText);
            return;
        }

        const data = await response.json();
        const newFollowed = !followed;

        button.setAttribute('data-followed', newFollowed ? '1' : '0');
        button.classList.remove(newFollowed ? 'btn-follow' : 'btn-following');
        button.classList.add(newFollowed ? 'btn-following' : 'btn-follow');
        button.innerHTML = newFollowed ? 'Following' : 'Follow';
    })
    .catch(error => {
        console.error('Follow Ajax Error', error);
    });
});