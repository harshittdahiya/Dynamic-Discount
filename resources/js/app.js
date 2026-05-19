document.addEventListener('DOMContentLoaded', () => {
	const revealNodes = document.querySelectorAll('.reveal');
	const searchForm = document.querySelector('[data-search-suggest-form]');
	const searchInput = document.querySelector('[data-search-suggest-input]');
	const searchPanel = document.querySelector('[data-search-suggest-panel]');
	let searchTimer = null;

	if (!revealNodes.length) {
		// continue; search autocomplete may still be active on pages without reveal nodes
	}

	const observer = new IntersectionObserver(
		(entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		},
		{
			threshold: 0.12,
			rootMargin: '0px 0px -40px 0px',
		}
	);

	revealNodes.forEach((node, index) => {
		node.style.transitionDelay = `${Math.min(index * 80, 380)}ms`;
		observer.observe(node);
	});

	if (!searchForm || !searchInput || !searchPanel) {
		return;
	}

	const endpoint = searchForm.dataset.searchSuggestUrl;

	const hidePanel = () => {
		searchPanel.hidden = true;
		searchPanel.innerHTML = '';
	};

	const renderPanel = (items, query) => {
		if (!items.length) {
			searchPanel.innerHTML = `
				<div class="p-3 text-muted small">
					No products found for "${query}".
				</div>
			`;
			searchPanel.hidden = false;
			return;
		}

		searchPanel.innerHTML = items.map((item) => `
			<a class="search-suggestion-item" href="${item.url}">
				<div class="search-suggestion-thumb">${item.image ? `<img src="${item.image}" alt="${item.name}">` : '<i class="bi bi-image"></i>'}</div>
				<div class="flex-grow-1">
					<div class="search-suggestion-name">${item.name}</div>
					<div class="search-suggestion-meta">${item.category} • ₹${item.price}</div>
				</div>
			</a>
		`).join('');
		searchPanel.hidden = false;
	};

	const fetchSuggestions = async (query) => {
		if (!query || query.trim().length < 1) {
			hidePanel();
			return;
		}

		const response = await fetch(`${endpoint}?search=${encodeURIComponent(query)}`, {
			headers: { 'Accept': 'application/json' },
		});

		if (!response.ok) {
			hidePanel();
			return;
		}

		const payload = await response.json();
		renderPanel(payload.items || [], query);
	};

	searchInput.addEventListener('input', () => {
		window.clearTimeout(searchTimer);
		searchTimer = window.setTimeout(() => {
			fetchSuggestions(searchInput.value);
		}, 220);
	});

	searchInput.addEventListener('focus', () => {
		if (searchInput.value.trim()) {
			fetchSuggestions(searchInput.value);
		}
	});

	document.addEventListener('click', (event) => {
		if (!searchForm.contains(event.target)) {
			hidePanel();
		}
	});

	searchForm.addEventListener('submit', () => {
		hidePanel();
	});
});
