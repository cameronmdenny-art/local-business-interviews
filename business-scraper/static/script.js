let currentScrapeId = null;
let pollInterval = null;
let mapInstance = null;
let mapMarkers = [];
let businessTypes = [];

document.addEventListener('DOMContentLoaded', function() {
    loadBusinessTypes();
    loadScrapes();
    loadSearchHistory();
    setupForm();
    document.getElementById('applyViewFilters').addEventListener('click', () => {
        if (currentScrapeId) {
            loadScrapeResults(currentScrapeId);
        }
    });
});

function setupForm() {
    document.getElementById('selectAllTypes').addEventListener('click', () => setAllTypes(true));
    document.getElementById('clearAllTypes').addEventListener('click', () => setAllTypes(false));

    const form = document.getElementById('scrapeForm');
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const location = document.getElementById('location').value.trim();
        if (!location) {
            alert('Please enter a location');
            return;
        }

        const payload = {
            location,
            max_results: Number(document.getElementById('maxResults').value || 20),
            business_types: getSelectedTypes(),
            filters: {
                min_rating: Number(document.getElementById('minRating').value || 0),
                max_rating: Number(document.getElementById('maxRating').value || 5),
                min_reviews: Number(document.getElementById('minReviews').value || 0),
                max_reviews: parseOptionalInt(document.getElementById('maxReviews').value),
                min_photos: Number(document.getElementById('minPhotos').value || 0),
                max_photos: parseOptionalInt(document.getElementById('maxPhotos').value),
                require_website: document.getElementById('requireWebsite').checked,
                require_no_website: document.getElementById('requireNoWebsite').checked,
                require_social_media: document.getElementById('requireSocial').checked,
                require_phone: document.getElementById('requirePhone').checked,
            }
        };

        await startScrape(payload);
    });
}

async function loadBusinessTypes() {
    try {
        const response = await fetch('/api/business-types');
        const data = await response.json();
        businessTypes = data.business_types || [];
        const list = document.getElementById('businessTypeList');
        list.innerHTML = businessTypes.map((businessType, index) => `
            <label class="type-item">
                <input type="checkbox" class="type-checkbox" value="${escapeHtml(businessType)}" ${index < 12 ? 'checked' : ''}>
                <span>${escapeHtml(businessType)}</span>
            </label>
        `).join('');

        list.querySelectorAll('.type-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', updateSelectedTypeCount);
        });
        updateSelectedTypeCount();
    } catch (error) {
        console.error('Failed to load business types', error);
    }
}

function getSelectedTypes() {
    return Array.from(document.querySelectorAll('.type-checkbox:checked')).map((input) => input.value);
}

function setAllTypes(checked) {
    document.querySelectorAll('.type-checkbox').forEach((input) => {
        input.checked = checked;
    });
    updateSelectedTypeCount();
}

function updateSelectedTypeCount() {
    const count = getSelectedTypes().length;
    document.getElementById('selectedTypeCount').textContent = `Selected: ${count}`;
}

async function startScrape(payload) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Starting...';

    try {
        const response = await fetch('/api/start-scrape', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Failed to start scrape');
        }

        currentScrapeId = data.scrape_id;
        document.getElementById('progressSection').style.display = 'block';
        document.getElementById('resultsFilters').style.display = 'block';
        pollScrapeStatus();
        document.getElementById('location').value = '';
    } catch (error) {
        alert(error.message);
        submitBtn.disabled = false;
        submitBtn.textContent = '🔍 Start Scrape';
    }
}

function pollScrapeStatus() {
    if (pollInterval) {
        clearInterval(pollInterval);
    }

    pollInterval = setInterval(async () => {
        try {
            const response = await fetch(`/api/scrape-status/${currentScrapeId}`);
            const scrape = await response.json();
            updateProgress(scrape);
            if (scrape.status === 'completed' || scrape.status === 'failed') {
                clearInterval(pollInterval);
                finishScrape(scrape);
            }
        } catch (error) {
            console.error(error);
        }
    }, 1500);
}

function updateProgress(scrape) {
    const statusMessage = document.getElementById('statusMessage');
    const progressFill = document.getElementById('progressFill');
    if (scrape.status === 'running') {
        statusMessage.textContent = `Scraping ${scrape.location}...`;
        progressFill.style.width = '60%';
    } else if (scrape.status === 'pending') {
        statusMessage.textContent = 'Starting scrape...';
        progressFill.style.width = '12%';
    }
}

function finishScrape(scrape) {
    const progressSection = document.getElementById('progressSection');
    const statusMessage = document.getElementById('statusMessage');
    const progressFill = document.getElementById('progressFill');
    const submitBtn = document.getElementById('submitBtn');

    if (scrape.status === 'completed') {
        statusMessage.textContent = `✅ Complete! Found ${scrape.businesses_found} businesses`;
        progressFill.style.width = '100%';
        loadScrapeResults(scrape.id);
    } else {
        statusMessage.textContent = `❌ Failed: ${scrape.error_message || 'Unknown error'}`;
        progressFill.style.width = '100%';
        progressFill.style.background = '#ff6b6b';
    }

    setTimeout(() => {
        progressSection.style.display = 'none';
        progressFill.style.width = '0%';
        progressFill.style.background = 'linear-gradient(90deg, #667eea 0%, #764ba2 100%)';
    }, 2500);

    submitBtn.disabled = false;
    submitBtn.textContent = '🔍 Start Scrape';
    loadScrapes();
}

async function loadScrapes() {
    try {
        const response = await fetch('/api/scrapes');
        const scrapes = await response.json();
        const tableBody = document.getElementById('tableBody');

        if (scrapes.length === 0) {
            tableBody.innerHTML = '<tr class="empty-state"><td colspan="6">No scrapes yet. Start one above!</td></tr>';
            return;
        }

        tableBody.innerHTML = scrapes.map(scrape => `
            <tr>
                <td data-label="Location"><strong>${scrape.location}</strong></td>
                <td data-label="Date">${formatDate(scrape.created_at)}</td>
                <td data-label="Status"><span class="status-badge status-${scrape.status}">${scrape.status}</span></td>
                <td data-label="Businesses">${scrape.businesses_found || 0}</td>
                <td data-label="Duration">${scrape.duration || 'N/A'}</td>
                <td data-label="Actions" class="actions">
                    ${scrape.csv_filename ? `<button class="btn btn-secondary" onclick="loadScrapeResults(${scrape.id})">🗺️ View</button>` : ''}
                    ${scrape.csv_filename ? `<button class="btn btn-secondary" onclick="downloadFile('${scrape.csv_filename}')">📥 Download</button>` : ''}
                    <button class="btn btn-danger" onclick="deleteScrape(${scrape.id})">🗑️ Delete</button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        console.error('Error loading scrapes:', error);
    }
}

async function loadSearchHistory() {
    try {
        const response = await fetch('/api/search-history');
        const data = await response.json();
        const searches = data.searches || [];
        const historyContainer = document.getElementById('searchHistoryContainer');
        
        if (!historyContainer) {
            return; // Container doesn't exist yet
        }
        
        if (searches.length === 0) {
            historyContainer.innerHTML = '<p style="text-align: center; color: #999;">No search history yet</p>';
            return;
        }
        
        historyContainer.innerHTML = '<div class="search-history-grid">' + 
            searches.map(search => `
                <button type="button" class="btn btn-history" onclick="searchFromHistory('${escapeHtml(search.location)}')">
                    📍 ${escapeHtml(search.location)}
                </button>
            `).join('') + 
        '</div>';
    } catch (error) {
        console.error('Error loading search history:', error);
    }
}

function searchFromHistory(location) {
    const locationInput = document.getElementById('location');
    locationInput.value = location;
    locationInput.focus();
    // Optionally auto-submit the form
    document.getElementById('scrapeForm').dispatchEvent(new Event('submit'));
}

async function loadScrapeResults(scrapeId) {
    currentScrapeId = scrapeId;
    document.getElementById('resultsFilters').style.display = 'block';
    const minRating = document.getElementById('viewMinRating').value || 0;
    const maxRating = document.getElementById('viewMaxRating').value || 5;
    const minReviews = document.getElementById('viewMinReviews').value || 0;
    const maxReviews = document.getElementById('viewMaxReviews').value;
    const minPhotos = document.getElementById('viewMinPhotos').value || 0;
    const maxPhotos = document.getElementById('viewMaxPhotos').value;
    const hasWebsite = document.getElementById('viewWebsite').value || 'any';

    const qs = new URLSearchParams({
        min_rating: minRating,
        max_rating: maxRating,
        min_reviews: minReviews,
        min_photos: minPhotos,
        has_website: hasWebsite,
    });

    if (maxReviews !== '') {
        qs.set('max_reviews', maxReviews);
    }
    if (maxPhotos !== '') {
        qs.set('max_photos', maxPhotos);
    }

    try {
        const response = await fetch(`/api/results/${scrapeId}?${qs.toString()}`);
        const payload = await response.json();
        if (!response.ok) {
            throw new Error(payload.error || 'Failed to load results');
        }
        renderResults(payload.results || []);
    } catch (error) {
        alert(error.message);
    }
}

function renderResults(results) {
    const mapSection = document.getElementById('mapResults');
    const cards = document.getElementById('resultCards');
    mapSection.style.display = 'block';

    if (!results.length) {
        cards.innerHTML = '<div class="result-card">No businesses match these display filters.</div>';
        renderMap([]);
        return;
    }

    cards.innerHTML = results.map((row) => {
        const phoneHtml = row.phone ? `<a class="phone-link" href="tel:${row.phone.replace(/\D/g, '')}">${row.phone}</a>` : 'No phone';
        const websiteHtml = row.website ? `<a class="map-link" href="${row.website}" target="_blank" rel="noopener">Website</a>` : 'No website';
        const mapsUrl = row.maps_url || `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(row.name + ' ' + row.address)}`;
        const socialMediaHtml = row.social_media ? `<div class="social-media-section"><span>📱 Social Media:</span> ${row.social_media}</div>` : '';
        
        return `
            <div class="result-card">
                <h3>${row.name || 'Unknown Business'}</h3>
                <div class="meta-row">
                    <span>⭐ ${row.rating || 'N/A'}</span>
                    <span>📝 ${row.review_count || 0} reviews</span>
                    <span>📸 ${row.photo_count || 0} photos</span>
                </div>
                <div class="meta-row">
                    <span>${phoneHtml}</span>
                    <span>${websiteHtml}</span>
                    <a class="map-link" href="${mapsUrl}" target="_blank" rel="noopener">Open in Google Maps</a>
                </div>
                ${socialMediaHtml}
                <div>${row.address || ''}</div>
            </div>
        `;
    }).join('');

    renderMap(results);
}

function renderMap(results) {
    if (!mapInstance) {
        mapInstance = L.map('map').setView([30.2672, -97.7431], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(mapInstance);
    }

    mapMarkers.forEach(marker => marker.remove());
    mapMarkers = [];

    const points = [];
    results.forEach(row => {
        const lat = Number(row.latitude);
        const lng = Number(row.longitude);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
            return;
        }
        const marker = L.marker([lat, lng]).addTo(mapInstance)
            .bindPopup(`<strong>${row.name || ''}</strong><br>${row.address || ''}<br>⭐ ${row.rating || 'N/A'}`);
        mapMarkers.push(marker);
        points.push([lat, lng]);
    });

    if (points.length) {
        const bounds = L.latLngBounds(points);
        mapInstance.fitBounds(bounds.pad(0.2));
    }
}

function downloadFile(filename) {
    window.location.href = `/api/download/${filename}`;
}

async function deleteScrape(scrapeId) {
    if (!confirm('Are you sure you want to delete this scrape?')) {
        return;
    }
    try {
        const response = await fetch(`/api/delete-scrape/${scrapeId}`, { method: 'DELETE' });
        if (response.ok) {
            loadScrapes();
            if (currentScrapeId === scrapeId) {
                document.getElementById('mapResults').style.display = 'none';
            }
        }
    } catch (error) {
        console.error(error);
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
}

function parseOptionalInt(value) {
    if (value === '' || value === null || value === undefined) {
        return null;
    }
    const parsed = Number(value);
    return Number.isNaN(parsed) ? null : parsed;
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
