(function () {
    function ready(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    async function geocodeAddress(query) {
        if (!query) {
            return null;
        }

        const endpoint = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query);

        try {
            const response = await fetch(endpoint, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                return null;
            }

            const data = await response.json();
            if (!Array.isArray(data) || !data.length) {
                return null;
            }

            return {
                lat: parseFloat(data[0].lat),
                lon: parseFloat(data[0].lon)
            };
        } catch (error) {
            return null;
        }
    }

    ready(async function () {
        const mapEl = document.getElementById('lbi-directory-map');
        const listEl = document.getElementById('lbi-directory-listings');

        if (!mapEl || !listEl || typeof window.L === 'undefined') {
            return;
        }

        const cards = Array.from(listEl.querySelectorAll('.lbi-directory-card'));
        if (!cards.length) {
            return;
        }

        const map = L.map(mapEl, {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([27.6648, -81.5158], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const bounds = [];
        const markerMap = new Map();

        for (const card of cards) {
            const title = (card.dataset.title || '').trim();
            const address = (card.dataset.address || '').trim();
            const city = (card.dataset.city || '').trim();
            const link = (card.dataset.link || '').trim();
            const query = address || city;

            if (!query) {
                continue;
            }

            const coords = await geocodeAddress(query);
            if (!coords) {
                continue;
            }

            const marker = L.marker([coords.lat, coords.lon]).addTo(map);
            marker.bindPopup('<strong>' + title + '</strong><br>' + query + '<br><a href="' + link + '">View Business</a>');

            L.circle([coords.lat, coords.lon], {
                radius: 8000,
                color: '#4f46e5',
                fillColor: '#4f46e5',
                fillOpacity: 0.07,
                weight: 1
            }).addTo(map);

            bounds.push([coords.lat, coords.lon]);
            markerMap.set(card, marker);

            const focusButton = card.querySelector('.lbi-map-focus-btn');
            if (focusButton) {
                focusButton.addEventListener('click', function () {
                    map.setView([coords.lat, coords.lon], 12, {
                        animate: true
                    });
                    marker.openPopup();
                });
            }
        }

        if (bounds.length) {
            map.fitBounds(bounds, {
                padding: [24, 24],
                maxZoom: 11
            });
        }

        cards.forEach(function (card) {
            card.addEventListener('mouseenter', function () {
                const marker = markerMap.get(card);
                if (marker) {
                    marker.openPopup();
                }
            });
        });
    });
})();
