let currentTabCategory;
let currentSortValue;

// Define some properties and function (if something is loaded, this function will be working)
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);  // to get URI after "?" mark
    currentTabCategory = urlParams.get('category') || 'all';        // fallback to "all" if no category
    currentSortValue = urlParams.get('sort') || 'latest';           // fallback to "latest" if no sort
    const currentPage = urlParams.get(`${currentTabCategory}_page`) || 1;
    const targetTabId = `tab-${currentTabCategory}`;
    const defaultContent = document.getElementById(targetTabId);

    // First reset all .active classes
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tag-category a').forEach(link => link.classList.remove('active'));

    // Then apply .active to correct tab and link
    if (defaultContent) {
        defaultContent.classList.add('active');
        const defaultLink = document.querySelector(`.tag-category a[href="#${targetTabId}"]`);
        if (defaultLink) {
            defaultLink.classList.add('active');
        }
    }

    // No.1: Dropdown sort handling
    const dropdown = document.getElementById('dropdown-sort');
    if (dropdown) {
        dropdown.value = currentSortValue;
        dropdown.addEventListener('change', function () {
            currentSortValue = this.value;
            sendSortRequest(currentTabCategory, currentSortValue, 1); // Reset to page 1
        });
    }

    // No.2: Tab click switching
    document.querySelectorAll('.tag-category a').forEach(tab => {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            currentTabCategory = this.dataset.category;

            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.querySelectorAll('.tag-category a').forEach(link => link.classList.remove('active'));

            document.getElementById(targetId).classList.add('active');
            this.classList.add('active');

            sendSortRequest(currentTabCategory, currentSortValue, 1);
        });
    });

    // Pagination link (event delegation)
    document.addEventListener('click', function (e) {
        if (e.target.matches('.pagination a')) {
            e.preventDefault();
            const url = new URL(e.target.href);
            const pageParam = url.searchParams.get(`${currentTabCategory}_page`) || 1;
            sendSortRequest(currentTabCategory, currentSortValue, pageParam);
        }
    });

    // Initial load (not back)
    const navigationType = performance.getEntriesByType("navigation")[0]?.type;
    const container = document.getElementById(`post-list-${currentTabCategory}`);
    if (navigationType !== 'back_forward' && container && container.children.length === 0) {
        sendSortRequest(currentTabCategory, currentSortValue, currentPage);
    }
});

// 🔁 Send sort request to server and render response
function sendSortRequest(category, sortValue, page = 1) {
    const searchInput = document.getElementById('searchInput');
    const searchValue = searchInput ? searchInput.value : '';

    const url = new URL(window.location.href);
    url.searchParams.set('search', searchValue);
    url.searchParams.set('sort', sortValue);
    url.searchParams.set('category', category);
    url.searchParams.set(`${category === 'all' ? 'all_page' : category + '_page'}`, page);
    window.history.replaceState({}, '', url);

    const bodyData = {
        search: searchValue,
        sort: sortValue,
        'data-category': category
    };

    if (category === 'all') {
        bodyData.all_page = page;
    } else {
        bodyData.page = page;
    }

    fetch('/sort', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(bodyData)
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById(`post-list-${category}`);
        if (container) {
            container.innerHTML = '';           // Clear old content
            container.innerHTML = data.html;    // Inject new HTML
        }
    })
    .catch(error => {
        console.error('Ajax error:', error);
    });
}

// When user returns using browser back button
window.addEventListener('pageshow', function (event) {
    if (event.persisted) {
        const urlParams = new URLSearchParams(window.location.search);
        const category = urlParams.get('category') || 'all';
        const sort = urlParams.get('sort') || 'latest';
        const page = urlParams.get(`${category}_page`) || 1;
        const targetTabId = `tab-${category}`;

        // to overwrite to global property
        currentTabCategory = category;
        currentSortValue = sort;

        // remove all .active classes first
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tag-category a').forEach(link => link.classList.remove('active'));

        // apply .active to the correct tab and link
        const tab = document.getElementById(targetTabId);
        const link = document.querySelector(`.tag-category a[href="#${targetTabId}"]`);
        if (tab) tab.classList.add('active');
        if (link) link.classList.add('active');

        // restore dropdown
        const dropdown = document.getElementById('dropdown-sort');
        if (dropdown) {
            dropdown.value = sort;
        }

        // retrieve
        sendSortRequest(category, sort, page);
    }
});